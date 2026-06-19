<?php

namespace App\Filament\Rw\Resources\KasRWS\Pages;

use App\Filament\Rw\Resources\KasRWS\KasRWResource;
use App\Filament\Rw\Resources\KasRWS\Widgets\KasRWOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

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

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'masuk' => Tab::make('Masuk')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tipe', 'masuk')),
            'keluar' => Tab::make('Keluar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tipe', 'keluar')),
        ];
    }
}
