<?php

namespace App\Filament\Rw\Resources\SetoranRWS\Pages;

use App\Filament\Rw\Resources\SetoranRWS\SetoranRWResource;
use App\Filament\Rw\Resources\SetoranRWS\Widgets\SetoranRWOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSetoranRWS extends ListRecords
{
    protected static string $resource = SetoranRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SetoranRWOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'valid' => Tab::make('Valid')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_validasi', 'valid')),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_validasi', 'pending')),
            'ditolak' => Tab::make('Ditolak')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_validasi', 'ditolak')),
        ];
    }
}
