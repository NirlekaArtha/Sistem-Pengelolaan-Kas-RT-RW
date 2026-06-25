<?php

namespace Tests\Feature;

use App\Enums\IuranWargaStatus;
use App\Models\IuranWarga;
use App\Models\JenisIuranWarga;
use App\Models\RT;
use App\Models\RW;
use App\Models\Warga;
use App\Services\IuranWargaBulkCreateService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class IuranWargaBulkCreateServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_one_record_for_each_selected_jenis_iuran(): void
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

        $created = IuranWargaBulkCreateService::create([
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'periode' => '2026-06',
            'tanggal_bayar' => '2026-06-10',
            'detail_iuran' => [
                [
                    'id_jenis_iuran' => $jenisKebersihan->id,
                    'is_selected' => true,
                ],
                [
                    'id_jenis_iuran' => $jenisKeamanan->id,
                    'is_selected' => true,
                ],
            ],
        ]);

        $this->assertCount(2, $created);
        $this->assertDatabaseCount('iuran_wargas', 2);
        $this->assertDatabaseHas('iuran_wargas', [
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'id_jenis_iuran' => $jenisKebersihan->id,
            'periode' => '2026-06',
            'status' => IuranWargaStatus::DIBAYAR->value,
        ]);
        $this->assertDatabaseHas('iuran_wargas', [
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'id_jenis_iuran' => $jenisKeamanan->id,
            'periode' => '2026-06',
            'status' => IuranWargaStatus::DIBAYAR->value,
        ]);
    }

    public function test_it_updates_existing_non_paid_iuran_to_dibayar(): void
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

        $created = IuranWargaBulkCreateService::create([
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'periode' => '2026-06',
            'tanggal_bayar' => '2026-06-10',
            'detail_iuran' => [
                [
                    'id_jenis_iuran' => $jenisIuran->id,
                    'is_selected' => true,
                ],
            ],
        ]);

        $this->assertCount(1, $created);
        $this->assertDatabaseCount('iuran_wargas', 1);
        $this->assertDatabaseHas('iuran_wargas', [
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'id_jenis_iuran' => $jenisIuran->id,
            'periode' => '2026-06',
            'status' => IuranWargaStatus::DIBAYAR->value,
        ]);
        $this->assertSame(
            '2026-06-10',
            IuranWarga::query()->first()->tanggal_bayar?->format('Y-m-d'),
        );
    }

    public function test_it_rejects_selected_jenis_iuran_that_is_already_paid_in_the_same_period(): void
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
            'tanggal_bayar' => '2026-06-05',
            'status' => IuranWargaStatus::DIBAYAR,
        ]);

        $this->expectException(ValidationException::class);

        IuranWargaBulkCreateService::create([
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'periode' => '2026-06',
            'tanggal_bayar' => '2026-06-10',
            'detail_iuran' => [
                [
                    'id_jenis_iuran' => $jenisIuran->id,
                    'is_selected' => true,
                ],
            ],
        ]);
    }

    public function test_database_rejects_duplicate_warga_jenis_and_periode_combination(): void
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
            'tanggal_bayar' => '2026-06-05',
            'status' => IuranWargaStatus::DIBAYAR,
        ]);

        $this->expectException(QueryException::class);

        IuranWarga::create([
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'id_jenis_iuran' => $jenisIuran->id,
            'periode' => '2026-06',
            'tanggal_bayar' => '2026-06-12',
            'status' => IuranWargaStatus::DIBAYAR,
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
