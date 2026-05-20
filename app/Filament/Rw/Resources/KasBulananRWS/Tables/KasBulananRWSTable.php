<?php

namespace App\Filament\Rw\Resources\KasBulananRWS\Tables;

use App\Filament\Rw\Resources\KasBulananRWS\Pages\EditKasBulananRW;
use App\Models\KasBulananRW;
use App\Models\KasRW;
use App\Models\SlipGaji;
use App\Models\SetoranRW;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KasBulananRWSTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('periode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_pendapatan')
                    ->prefix('Rp ')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: ',', decimalSeparator: '.')
                    ->sortable(),
                TextColumn::make('total_pengeluaran')
                    ->prefix('Rp ')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: ',', decimalSeparator: '.')
                    ->sortable(),
                TextColumn::make('total_pendapatan_bersih')
                    ->prefix('Rp ')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: ',', decimalSeparator: '.')
                    ->sortable(),
                TextColumn::make('saldo_awal')
                    ->prefix('Rp ')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: ',', decimalSeparator: '.')
                    ->sortable(),
                TextColumn::make('saldo_akhir')
                    ->prefix('Rp ')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: ',', decimalSeparator: '.')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('recalculate')
                    ->label('recalculate')
                    ->icon('heroicon-o-arrow-path')
                    ->iconButton()
                    ->tooltip('Kalkulasi Ulang')
                    ->color('info')
                    ->action(function (KasBulananRW $record) {
                        $rwId = $record->id_rw;

                        $totalPendapatanKasHarian = KasRW::where('id_rw', $rwId)
                            ->where('tipe', 'masuk')
                            ->where('tanggal', 'like', "{$record->periode}-%")
                            ->sum('jumlah');

                        $totalPengeluaranKasHarian = KasRW::where('id_rw', $rwId)
                            ->where('tipe', 'keluar')
                            ->where('tanggal', 'like', "{$record->periode}-%")
                            ->sum('jumlah');

                        $totalPengeluaranGajiPetugas = SlipGaji::whereHas('petugas', function ($q) use ($rwId) {
                                $q->where('id_rw', $rwId);
                            })
                            ->where('tanggal', 'like', "{$record->periode}-%")
                            ->sum('total');

                        $totalPemasukanSetoranRT = SetoranRW::where('id_rw', $rwId)
                            ->where('periode', $record->periode)
                            ->where('status_validasi', 'valid')
                            ->sum('jumlah_setor');

                        $record->total_pendapatan = $totalPendapatanKasHarian + $totalPemasukanSetoranRT;
                        $record->total_pengeluaran = $totalPengeluaranKasHarian + $totalPengeluaranGajiPetugas;
                        $record->total_pendapatan_bersih = $record->total_pendapatan - $record->total_pengeluaran;
                        $record->saldo_akhir = $record->saldo_awal + $record->total_pendapatan_bersih;
                        $record->save();

                        Notification::make()
                            ->title('Kalkulasi Ulang Berhasil')
                            ->body("Data kas bulanan periode {$record->periode} telah diperbarui.")
                            ->success()
                            ->send();
                    }),
                Action::make('export')
                    ->label('export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->iconButton()
                    ->tooltip('Export')
                    ->url(fn (KasBulananRW $record): string => route('rw.kas-bulanan.preview', ['record' => $record])),
            ])
            ->actionsColumnLabel('aksi')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('periode', 'desc')
            ->recordUrl(fn (KasBulananRW $record): string => EditKasBulananRW::getUrl(['record' => $record]));
    }
}

