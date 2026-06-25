<?php

namespace Tests\Feature;

use App\Enums\IuranWargaStatus;
use App\Models\IuranWarga;
use App\Models\JenisIuranWarga;
use App\Models\RT;
use App\Models\RW;
use App\Models\Warga;
use App\Services\GenerateMonthlyIuranWargaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class GenerateMonthlyIuranWargaServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_marks_previous_unpaid_iuran_as_telat_and_generates_current_unpaid_iuran(): void
    {
        [$rt, $warga] = $this->makeRtAndWarga();

        $jenisKebersihan = JenisIuranWarga::factory()->create([
            'id_rt' => $rt->id,
            'jenis_iuran' => 'Iuran Kebersihan',
        ]);
        $jenisKeamanan = JenisIuranWarga::factory()->create([
            'id_rt' => $rt->id,
            'jenis_iuran' => 'Iuran Keamanan',
        ]);

        IuranWarga::create([
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'id_jenis_iuran' => $jenisKebersihan->id,
            'periode' => '2026-06',
            'tanggal_bayar' => null,
            'status' => IuranWargaStatus::BELUM_BAYAR,
        ]);
        IuranWarga::create([
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'id_jenis_iuran' => $jenisKeamanan->id,
            'periode' => '2026-06',
            'tanggal_bayar' => '2026-06-15',
            'status' => IuranWargaStatus::DIBAYAR,
        ]);

        $result = GenerateMonthlyIuranWargaService::run(Carbon::parse('2026-07-01'));

        $this->assertSame(1, $result['marked_late']);
        $this->assertSame(2, $result['created_unpaid']);
        $this->assertDatabaseHas('iuran_wargas', [
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'id_jenis_iuran' => $jenisKebersihan->id,
            'periode' => '2026-06',
            'status' => IuranWargaStatus::TELAT->value,
        ]);
        $this->assertDatabaseHas('iuran_wargas', [
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'id_jenis_iuran' => $jenisKebersihan->id,
            'periode' => '2026-07',
            'status' => IuranWargaStatus::BELUM_BAYAR->value,
        ]);
        $this->assertDatabaseHas('iuran_wargas', [
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'id_jenis_iuran' => $jenisKeamanan->id,
            'periode' => '2026-07',
            'status' => IuranWargaStatus::BELUM_BAYAR->value,
        ]);
    }

    public function test_it_does_not_duplicate_current_period_iuran_that_already_exists(): void
    {
        [$rt, $warga] = $this->makeRtAndWarga();

        $jenisIuran = JenisIuranWarga::factory()->create([
            'id_rt' => $rt->id,
            'jenis_iuran' => 'Iuran Kebersihan',
        ]);

        IuranWarga::create([
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'id_jenis_iuran' => $jenisIuran->id,
            'periode' => '2026-07',
            'tanggal_bayar' => null,
            'status' => IuranWargaStatus::BELUM_BAYAR,
        ]);

        $result = GenerateMonthlyIuranWargaService::run(Carbon::parse('2026-07-01'));

        $this->assertSame(0, $result['marked_late']);
        $this->assertSame(0, $result['created_unpaid']);
        $this->assertDatabaseCount('iuran_wargas', 1);
    }

    public function test_monthly_command_runs_the_service(): void
    {
        [$rt, $warga] = $this->makeRtAndWarga();

        $jenisIuran = JenisIuranWarga::factory()->create([
            'id_rt' => $rt->id,
            'jenis_iuran' => 'Iuran Kebersihan',
        ]);

        IuranWarga::create([
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'id_jenis_iuran' => $jenisIuran->id,
            'periode' => '2026-06',
            'tanggal_bayar' => null,
            'status' => IuranWargaStatus::BELUM_BAYAR,
        ]);

        $this->artisan('iuran-warga:generate-monthly', ['--date' => '2026-07-01'])
            ->expectsOutput(
                'Periode 2026-07: 1 iuran belum bayar dibuat, 1 iuran periode 2026-06 ditandai telat.',
            )
            ->assertSuccessful();

        $this->assertDatabaseHas('iuran_wargas', [
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'id_jenis_iuran' => $jenisIuran->id,
            'periode' => '2026-07',
            'status' => IuranWargaStatus::BELUM_BAYAR->value,
        ]);
    }

    /**
     * @return array{0: RT, 1: Warga}
     */
    protected function makeRtAndWarga(): array
    {
        $rw = RW::factory()->create();
        $rt = RT::factory()->create(['id_rw' => $rw->id]);
        $warga = Warga::factory()->create(['id_rt' => $rt->id]);

        return [$rt, $warga];
    }
}
