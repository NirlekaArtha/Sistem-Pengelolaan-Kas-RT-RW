<?php

namespace Tests\Feature;

use App\Enums\SlipGajiStatus;
use App\Models\Petugas;
use App\Models\RW;
use App\Models\SlipGaji;
use App\Services\GenerateMonthlySlipGajiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class GenerateMonthlySlipGajiServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_next_period_unpaid_salary_slips_on_the_25th(): void
    {
        $rw = RW::factory()->create();
        $petugasSatu = Petugas::factory()->create([
            'id_rw' => $rw->id,
            'gaji_pokok' => 1500000,
        ]);
        $petugasDua = Petugas::factory()->create([
            'id_rw' => $rw->id,
            'gaji_pokok' => 2000000,
        ]);

        $result = GenerateMonthlySlipGajiService::run(Carbon::parse('2026-06-25'));

        $this->assertSame(2, $result['created_unpaid']);
        $this->assertSame('2026-07', $result['period']);
        $this->assertDatabaseHas('slip_gajis', [
            'id_petugas' => $petugasSatu->id,
            'total' => 1500000,
            'periode' => '2026-07',
            'status' => SlipGajiStatus::BELUM_DIBAYAR->value,
        ]);
        $this->assertDatabaseHas('slip_gajis', [
            'id_petugas' => $petugasDua->id,
            'total' => 2000000,
            'periode' => '2026-07',
            'status' => SlipGajiStatus::BELUM_DIBAYAR->value,
        ]);
    }

    public function test_it_does_not_duplicate_existing_salary_slips(): void
    {
        $rw = RW::factory()->create();
        $petugas = Petugas::factory()->create([
            'id_rw' => $rw->id,
            'gaji_pokok' => 1500000,
        ]);

        SlipGaji::create([
            'id_petugas' => $petugas->id,
            'total' => 1500000,
            'periode' => '2026-07',
            'status' => SlipGajiStatus::BELUM_DIBAYAR,
        ]);

        $result = GenerateMonthlySlipGajiService::run(Carbon::parse('2026-06-25'));

        $this->assertSame(0, $result['created_unpaid']);
        $this->assertDatabaseCount('slip_gajis', 1);
    }

    public function test_monthly_command_runs_the_service(): void
    {
        $rw = RW::factory()->create();
        Petugas::factory()->create(['id_rw' => $rw->id]);

        $this->artisan('slip-gaji:generate-monthly', ['--date' => '2026-06-25'])
            ->expectsOutput(
                'Periode 2026-07: 1 slip gaji belum dibayar dibuat.',
            )
            ->assertSuccessful();

        $this->assertDatabaseHas('slip_gajis', [
            'periode' => '2026-07',
            'status' => SlipGajiStatus::BELUM_DIBAYAR->value,
        ]);
    }
}
