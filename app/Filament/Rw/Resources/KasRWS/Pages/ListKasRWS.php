<?php

namespace App\Filament\Rw\Resources\KasRWS\Pages;

use App\Enums\KasTipe;
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
            KasTipe::MASUK->value => Tab::make(KasTipe::MASUK->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tipe', KasTipe::MASUK->value)),
            KasTipe::KELUAR->value => Tab::make(KasTipe::KELUAR->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tipe', KasTipe::KELUAR->value)),
        ];
    }
}
