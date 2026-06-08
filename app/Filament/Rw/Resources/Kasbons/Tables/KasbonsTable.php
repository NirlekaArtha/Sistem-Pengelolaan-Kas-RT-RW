<?php

namespace App\Filament\Rw\Resources\Kasbons\Tables;

use App\Filament\Rw\Resources\Kasbons\Pages\ViewKasbon;
use App\Models\Kasbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KasbonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("petugas.nama")
                    ->label("Nama Petugas")
                    ->searchable()
                    ->sortable(),
                TextColumn::make("jumlah")
                    ->prefix("Rp ")
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: ",",
                        decimalSeparator: ".",
                    )
                    ->sortable(),
                TextColumn::make("tanggal")->date()->searchable()->sortable(),
                TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("updated_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()->iconButton()->tooltip("Lihat"),
                EditAction::make()->iconButton()->tooltip("Edit"),
                DeleteAction::make()->iconButton()->tooltip("Hapus"),
            ])
            ->actionsColumnLabel("Aksi")
            ->recordUrl(
                fn(Kasbon $record): string => ViewKasbon::getUrl([
                    "record" => $record,
                ]),
            )
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
