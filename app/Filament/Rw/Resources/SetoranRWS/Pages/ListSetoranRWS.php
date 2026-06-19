<?php

namespace App\Filament\Rw\Resources\SetoranRWS\Pages;

use App\Enums\SetoranStatusValidasi;
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
            SetoranStatusValidasi::VALID->value => Tab::make(SetoranStatusValidasi::VALID->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_validasi', SetoranStatusValidasi::VALID->value)),
            SetoranStatusValidasi::PENDING->value => Tab::make(SetoranStatusValidasi::PENDING->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_validasi', SetoranStatusValidasi::PENDING->value)),
            SetoranStatusValidasi::DITOLAK->value => Tab::make(SetoranStatusValidasi::DITOLAK->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_validasi', SetoranStatusValidasi::DITOLAK->value)),
        ];
    }
}
