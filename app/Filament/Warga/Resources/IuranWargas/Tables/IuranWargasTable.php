<?php

namespace App\Filament\Warga\Resources\IuranWargas\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IuranWargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenisIuran.jenis_iuran')
                    ->label('Jenis Iuran')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('periode')
                    ->label('Periode')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'dibayar'     => 'success',
                        'telat'       => 'danger',
                        'belum bayar' => 'warning',
                        default       => 'gray',
                    })
                    ->icon(fn ($state): string => match ($state) {
                        'dibayar'     => 'heroicon-m-check-circle',
                        'telat'       => 'heroicon-m-x-circle',
                        'belum bayar' => 'heroicon-m-clock',
                        default       => 'heroicon-m-question-mark-circle',
                    })
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        'dibayar'     => 'Dibayar',
                        'telat'       => 'Telat',
                        'belum bayar' => 'Belum Bayar',
                        default       => (string) $state,
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->icon('heroicon-m-eye')
                    ->iconButton()
                    ->label('Lihat'),
            ])
            ->actionsColumnLabel('Aksi')
            ->toolbarActions([])
            ->defaultSort('periode', 'desc');
    }
}
