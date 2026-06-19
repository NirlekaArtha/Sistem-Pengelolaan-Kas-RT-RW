<?php

namespace App\Filament\Warga\Resources\JenisIuranWargas\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JenisIuranWargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis_iuran')
                    ->label('Jenis Iuran')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jumlah')
                    ->label('Jumlah Iuran')
                    ->money('IDR')
                    ->sortable(),
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
            ->recordAction('view')
            ->toolbarActions([]);
    }
}
