<?php

namespace App\Filament\Rt\Resources\JenisIuranWargas\Pages;

use App\Filament\Rt\Resources\JenisIuranWargas\JenisIuranWargaResource;
use App\Filament\Rt\Resources\JenisIuranWargas\Widgets\JenisIuranWargaOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJenisIuranWargas extends ListRecords
{
    protected static string $resource = JenisIuranWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }

    protected function getHeaderWidgets(): array
    {
        return [JenisIuranWargaOverview::class];
    }
}
