<?php

namespace App\Filament\Rt\Resources\IuranWargas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IuranWargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("warga.nama_kepala_keluarga")
                    ->label("Nama Warga")
                    ->sortable(),
                TextColumn::make("jenisIuran.jenis_iuran")
                    ->label("Jenis Iuran")
                    ->sortable(),
                TextColumn::make("status")
                    ->badge()
                    ->color(
                        fn(string $state): string => match ($state) {
                            "belum bayar" => "warning",
                            "dibayar" => "success",
                            "telat" => "danger",
                            default => "gray",
                        },
                    )
                    ->icon(
                        fn(string $state): string => match ($state) {
                            "belum bayar" => "heroicon-m-clock",
                            "dibayar" => "heroicon-m-check-circle",
                            "telat" => "heroicon-m-x-circle",
                            default => "heroicon-o-question-mark-circle",
                        },
                    ),
                TextColumn::make("periode")->searchable()->sortable(),
                TextColumn::make("tanggal_bayar")->date()->sortable(),
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
