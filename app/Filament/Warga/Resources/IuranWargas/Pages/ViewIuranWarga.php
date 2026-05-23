<?php

namespace App\Filament\Warga\Resources\IuranWargas\Pages;

use App\Filament\Warga\Resources\IuranWargas\IuranWargaResource;
use Filament\Resources\Pages\ViewRecord;

class ViewIuranWarga extends ViewRecord
{
    protected static string $resource = IuranWargaResource::class;

    // Warga tidak bisa mengedit iuran sendiri — header actions dikosongkan
    protected function getHeaderActions(): array
    {
        return [];
    }
}
