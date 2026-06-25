<?php

namespace App\Services;

use App\Enums\IuranWargaStatus;
use App\Models\IuranWarga;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class IuranWargaBulkCreateService
{
    /**
     * @param  array<string, mixed>  $data
     * @return Collection<int, IuranWarga>
     */
    public static function create(array $data): Collection
    {
        $selectedJenisIuranIds = collect($data['detail_iuran'] ?? [])
            ->filter(fn (array $item): bool => (bool) ($item['is_selected'] ?? false))
            ->pluck('id_jenis_iuran')
            ->filter()
            ->map(fn (mixed $id): int => (int) $id)
            ->values();

        if ($selectedJenisIuranIds->isEmpty()) {
            throw ValidationException::withMessages([
                'detail_iuran' => 'Pilih minimal satu jenis iuran yang ingin dicatat.',
            ]);
        }

        $existingIurans = IuranWarga::query()
            ->where('id_rt', $data['id_rt'])
            ->where('id_warga', $data['id_warga'])
            ->where('periode', $data['periode'])
            ->whereIn('id_jenis_iuran', $selectedJenisIuranIds)
            ->with('jenisIuran:id,jenis_iuran')
            ->get()
            ->keyBy('id_jenis_iuran');

        $paidIurans = $existingIurans
            ->filter(
                fn (IuranWarga $iuran): bool => $iuran->status === IuranWargaStatus::DIBAYAR,
            );

        if ($paidIurans->isNotEmpty()) {
            $jenisIuranNames = $paidIurans
                ->pluck('jenisIuran.jenis_iuran')
                ->filter()
                ->implode(', ');

            throw ValidationException::withMessages([
                'detail_iuran' => 'Iuran berikut sudah dibayar untuk KK dan periode ini: '.$jenisIuranNames.'.',
            ]);
        }

        return DB::transaction(function () use (
            $data,
            $selectedJenisIuranIds,
            $existingIurans,
        ): Collection {
            return new Collection(
                $selectedJenisIuranIds
                    ->map(
                        function (int $jenisIuranId) use (
                            $data,
                            $existingIurans,
                        ): IuranWarga {
                            /** @var IuranWarga|null $existingIuran */
                            $existingIuran = $existingIurans->get($jenisIuranId);

                            if ($existingIuran) {
                                $existingIuran->update([
                                    'tanggal_bayar' => $data['tanggal_bayar'] ?? null,
                                    'status' => IuranWargaStatus::DIBAYAR,
                                ]);

                                return $existingIuran->fresh();
                            }

                            return IuranWarga::create([
                                'id_warga' => $data['id_warga'],
                                'id_jenis_iuran' => $jenisIuranId,
                                'id_rt' => $data['id_rt'],
                                'periode' => $data['periode'],
                                'tanggal_bayar' => $data['tanggal_bayar'] ?? null,
                                'status' => IuranWargaStatus::DIBAYAR,
                            ]);
                        },
                    )
                    ->all(),
            );
        });
    }
}
