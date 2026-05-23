<?php

namespace App\Filament\Rt\Resources\KasBulananRTS\Pages;

use App\Filament\Rt\Resources\KasBulananRTS\KasBulananRTResource;
use App\Filament\Rt\Resources\KasBulananRTS\Widgets\KasBulananRTOverview;
use App\Models\KasBulananRT;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListKasBulananRTS extends ListRecords
{
    protected static string $resource = KasBulananRTResource::class;

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
                    Select::make('tahun')
                        ->label('Pilih Tahun')
                        ->options(function () {
                            $rtId = auth()->user()?->rt?->id;
                            $years = KasBulananRT::where('id_rt', $rtId)
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
                    return redirect()->route('rt.kas-tahunan.preview', ['tahun' => $data['tahun']]);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [KasBulananRTOverview::class];
    }
}
