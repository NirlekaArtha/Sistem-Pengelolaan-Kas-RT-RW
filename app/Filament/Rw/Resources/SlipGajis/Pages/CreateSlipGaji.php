<?php

namespace App\Filament\Rw\Resources\SlipGajis\Pages;

use App\Enums\SlipGajiStatus;
use App\Filament\Rw\Resources\SlipGajis\SlipGajiResource;
use App\Models\Petugas;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateSlipGaji extends CreateRecord
{
    protected static string $resource = SlipGajiResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $petugas = Petugas::findOrFail($data['id_petugas']);
        $periode = Carbon::createFromFormat('Y-m', $data['periode']);

        $data['total'] = $petugas->gaji_pokok;
        $data['tanggal'] = $periode->day(25)->toDateString();
        $data['status'] = SlipGajiStatus::BELUM_DIBAYAR;

        unset($data['periode']);

        return $data;
    }
}
