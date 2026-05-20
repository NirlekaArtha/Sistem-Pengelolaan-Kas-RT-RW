<?php

namespace App\Filament\Rw\Resources\KasRWS\Pages;

use App\Filament\Rw\Resources\KasRWS\KasRWResource;
use App\Filament\Rw\Resources\KasRWS\Widgets\KasRWOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKasRWS extends ListRecords
{
    protected static string $resource = KasRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Kas Harian'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            KasRWOverview::class,
        ];
    }
}
