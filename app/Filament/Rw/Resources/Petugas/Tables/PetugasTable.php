<?php

namespace App\Filament\Rw\Resources\Petugas\Tables;

use App\Filament\Rw\Resources\Petugas\Pages\ViewPetugas;
use App\Models\Petugas;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PetugasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("nama")->searchable(),
                TextColumn::make("tugas")
                    ->searchable()
                    ->badge()
                    ->color(
                        fn(string $state): string => match ($state) {
                            "satpam" => "success",
                            "kebersihan" => "info",
                            "sampah" => "danger",
                        },
                    )
                    ->icon(
                        fn(string $state): string => match ($state) {
                            "satpam" => "heroicon-o-shield-check",
                            "kebersihan" => "heroicon-o-sparkles",
                            "sampah" => "heroicon-o-trash",
                        },
                    ),
                TextColumn::make("alamat")->searchable(),
                TextColumn::make("gaji_pokok")
                    ->prefix("Rp ")
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: ",",
                        decimalSeparator: ".",
                    )
                    ->sortable(),
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
                SelectFilter::make("tugas")
                    ->label("Tugas")
                    ->options([
                        "satpam" => "Satpam",
                        "kebersihan" => "Kebersihan",
                        "sampah" => "Sampah",
                    ]),
            ])
            ->actions([
                ViewAction::make()->iconButton()->tooltip("Lihat"),
                EditAction::make()->iconButton()->tooltip("Edit"),
                DeleteAction::make()->iconButton()->tooltip("Hapus"),
            ])
            ->actionsColumnLabel("Aksi")
            ->recordUrl(
                fn(Petugas $record): string => ViewPetugas::getUrl([
                    "record" => $record,
                ]),
            )
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
