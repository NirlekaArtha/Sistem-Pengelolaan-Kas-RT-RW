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
                ->action(function () {
                    $rwId = auth()->user()?->rw?->id;
                    $currentYear = date('Y');
                    
                    $records = KasBulananRW::where('id_rw', $rwId)
                        ->where('periode', 'like', "{$currentYear}-%")
                        ->orderBy('periode', 'asc')
                        ->get();

                    $headers = [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => "attachment; filename=\"laporan-tahunan-kas-bulanan-rw-{$currentYear}.csv\"",
                    ];

                    $callback = function () use ($records, $currentYear) {
                        $file = fopen('php://output', 'w');
                        
                        // Add BOM for Excel compatibility with UTF-8
                        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                        
                        fputcsv($file, ['Laporan Tahunan Kas Bulanan RW - Tahun ' . $currentYear]);
                        fputcsv($file, []);
                        fputcsv($file, ['Periode', 'Total Pendapatan', 'Total Pengeluaran', 'Total Pendapatan Bersih', 'Saldo Awal', 'Saldo Akhir']);
                        
                        $grandPendapatan = 0;
                        $grandPengeluaran = 0;
                        $grandBersih = 0;

                        foreach ($records as $record) {
                            fputcsv($file, [
                                $record->periode,
                                'Rp ' . number_format($record->total_pendapatan, 0, ',', '.'),
                                'Rp ' . number_format($record->total_pengeluaran, 0, ',', '.'),
                                'Rp ' . number_format($record->total_pendapatan_bersih, 0, ',', '.'),
                                'Rp ' . number_format($record->saldo_awal, 0, ',', '.'),
                                'Rp ' . number_format($record->saldo_akhir, 0, ',', '.')
                            ]);
                            $grandPendapatan += $record->total_pendapatan;
                            $grandPengeluaran += $record->total_pengeluaran;
                            $grandBersih += $record->total_pendapatan_bersih;
                        }

                        fputcsv($file, []);
                        fputcsv($file, [
                            'TOTAL TAHUN INI',
                            'Rp ' . number_format($grandPendapatan, 0, ',', '.'),
                            'Rp ' . number_format($grandPengeluaran, 0, ',', '.'),
                            'Rp ' . number_format($grandBersih, 0, ',', '.'),
                            '',
                            ''
                        ]);

                        fclose($file);
                    };

                    return response()->stream($callback, 200, $headers);
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
