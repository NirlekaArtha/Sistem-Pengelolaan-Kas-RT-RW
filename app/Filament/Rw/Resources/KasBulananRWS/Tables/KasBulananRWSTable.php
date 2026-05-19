<?php

namespace App\Filament\Rw\Resources\KasBulananRWS\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KasBulananRWSTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_rw')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('periode')
                    ->searchable(),
                TextColumn::make('total_pendapatan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_pengeluaran')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_pendapatan_bersih')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('saldo_awal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('saldo_akhir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('file_path')
                    ->searchable(),
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
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
