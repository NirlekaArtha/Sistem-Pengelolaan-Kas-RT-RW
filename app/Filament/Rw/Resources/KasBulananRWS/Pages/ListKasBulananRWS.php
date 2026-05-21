<?php

namespace App\Filament\Rw\Resources\KasBulananRWS\Pages;

use App\Filament\Rw\Resources\KasBulananRWS\KasBulananRWResource;
use App\Filament\Rw\Resources\KasBulananRWS\Widgets\KasBulananRWOverview;
use App\Models\KasBulananRW;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKasBulananRWS extends ListRecords
{
    protected static string $resource = KasBulananRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Kas Bulanan'),
            Action::make('exportTahunan')
                ->label('Export Laporan Tahunan')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\Select::make('tahun')
                        ->label('Pilih Tahun')
                        ->options(function () {
                            $rwId = auth()->user()?->rw?->id;
                            $years = KasBulananRW::where('id_rw', $rwId)
                                ->selectRaw('SUBSTRING(periode, 1, 4) as tahun')
                                ->distinct()
                                ->orderBy('tahun', 'desc')
                                ->pluck('tahun', 'tahun')
                                ->toArray();
                            
                            if (empty($years)) {
                                $currentYear = date('Y');
                                $years = [$currentYear => $currentYear];
                            }
                            return $years;
                        })
                        ->required()
                        ->default(date('Y')),
                ])
                ->action(function (array $data) {
                    return redirect()->route('rw.kas-tahunan.preview', ['tahun' => $data['tahun']]);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            KasBulananRWOverview::class,
        ];
    }
}
