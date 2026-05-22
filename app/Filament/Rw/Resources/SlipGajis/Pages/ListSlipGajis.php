<?php

namespace App\Filament\Rw\Resources\SlipGajis\Pages;

use App\Filament\Rw\Resources\SlipGajis\SlipGajiResource;
use App\Filament\Rw\Resources\SlipGajis\Widgets\SlipGajiOverview;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSlipGajis extends ListRecords
{
    protected static string $resource = SlipGajiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportSemuaSlipGaji')
                ->label('Export Semua Slip Gaji')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->url(route('rw.slip-gaji.preview-all'))
                ->openUrlInNewTab(),
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SlipGajiOverview::class,
        ];
    }
}
