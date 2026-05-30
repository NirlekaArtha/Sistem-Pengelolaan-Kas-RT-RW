<?php

namespace App\Filament\Warga\Resources\JenisIuranWargas\Pages;

use App\Filament\Warga\Resources\JenisIuranWargas\JenisIuranWargaResource;
use App\Filament\Warga\Resources\JenisIuranWargas\Widgets\JenisIuranWargaOverview;
use Filament\Resources\Pages\ListRecords;

class ListJenisIuranWargas extends ListRecords
{
    protected static string $resource = JenisIuranWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            JenisIuranWargaOverview::class,
        ];
    }
}
