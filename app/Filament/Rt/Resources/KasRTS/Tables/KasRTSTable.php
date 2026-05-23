<?php

namespace App\Filament\Rt\Resources\KasRTS\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KasRTSTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("sumber_tujuan")
                    ->label("Sumber/Tujuan")
                    ->searchable(),
                TextColumn::make("tipe")
                    ->badge()
                    ->color(
                        fn(string $state): string => match ($state) {
                            "masuk" => "success",
                            "keluar" => "danger",
                            default => "gray",
                        },
                    )
                    ->icon(
                        fn(string $state): string => match ($state) {
                            "masuk" => "heroicon-o-arrow-trending-up",
                            "keluar" => "heroicon-o-arrow-trending-down",
                            default => "heroicon-o-question-mark-circle",
                        },
                    ),
                TextColumn::make("jenis")->badge()->color("info"),
                TextColumn::make("jumlah")
                    ->prefix("Rp ")
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: ",",
                        decimalSeparator: ".",
                    )
                    ->sortable(),
                TextColumn::make("keterangan")
                    ->searchable()
                    ->limit(20)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen((string) $state) <= 20) {
                            return null;
                        }
                        return $state;
                    }),
                TextColumn::make("tanggal")->date()->sortable(),
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
                EditAction::make()->iconButton()->tooltip("Edit"),
                DeleteAction::make()->iconButton()->tooltip("Hapus"),
            ])
            ->actionsColumnLabel("Aksi")
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
