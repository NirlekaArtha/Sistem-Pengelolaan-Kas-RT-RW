<?php

namespace App\Filament\Rw\Resources\Petugas\Pages;

use App\Enums\PetugasTugas;
use App\Filament\Rw\Resources\Petugas\PetugasResource;
use App\Filament\Rw\Resources\Petugas\Widgets\PetugasOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
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
            PetugasTugas::SATPAM->value => Tab::make(PetugasTugas::SATPAM->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tugas', PetugasTugas::SATPAM->value)),
            PetugasTugas::KEBERSIHAN->value => Tab::make(PetugasTugas::KEBERSIHAN->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tugas', PetugasTugas::KEBERSIHAN->value)),
            PetugasTugas::SAMPAH->value => Tab::make(PetugasTugas::SAMPAH->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tugas', PetugasTugas::SAMPAH->value)),
        ];
    }
}
