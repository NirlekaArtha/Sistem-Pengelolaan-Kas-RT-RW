<?php

namespace App\Filament\Rw\Resources\Kasbons\Tables;

use App\Filament\Rw\Resources\Kasbons\Pages\EditKasbon;
use App\Models\Kasbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KasbonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('petugas.nama')
                    ->label('Nama Petugas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah')
                    ->prefix('Rp ')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: ',', decimalSeparator: '.')
                    ->sortable(),
                TextColumn::make('tanggal')
                    ->date()
                    ->searchable()
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
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit'),
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Hapus'),
            ])
            ->actionsColumnLabel('Aksi')
            ->recordUrl(fn (Kasbon $record): string => EditKasbon::getUrl(['record' => $record]))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

