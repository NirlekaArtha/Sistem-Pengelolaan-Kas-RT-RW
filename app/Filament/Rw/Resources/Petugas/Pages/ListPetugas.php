<?php

namespace App\Filament\Rw\Resources\Petugas\Pages;

use App\Filament\Rw\Resources\Petugas\PetugasResource;
use App\Filament\Rw\Resources\Petugas\Widgets\PetugasOverview;
use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPetugas extends ListRecords
{
    protected static string $resource = PetugasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PetugasOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'satpam' => Tab::make('Satpam')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tugas', 'satpam')),
            'kebersihan' => Tab::make('Kebersihan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tugas', 'kebersihan')),
            'sampah' => Tab::make('Sampah')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tugas', 'sampah')),
        ];
    }
}
