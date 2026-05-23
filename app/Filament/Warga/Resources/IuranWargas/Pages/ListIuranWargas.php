<?php

namespace App\Filament\Warga\Resources\IuranWargas\Pages;

use App\Filament\Warga\Resources\IuranWargas\IuranWargaResource;
use App\Filament\Warga\Resources\IuranWargas\Widgets\IuranWargaOverview;
use Filament\Resources\Pages\ListRecords;

class ListIuranWargas extends ListRecords
{
    protected static string $resource = IuranWargaResource::class;

    // Warga tidak bisa menambah iuran sendiri — header actions dikosongkan
    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            IuranWargaOverview::class,
        ];
    }
}
