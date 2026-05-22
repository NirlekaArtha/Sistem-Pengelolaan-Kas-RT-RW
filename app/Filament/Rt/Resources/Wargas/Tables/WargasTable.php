<?php

namespace App\Filament\Rt\Resources\Wargas\Tables;

use App\Filament\Rw\Resources\RTS\Pages\EditRT;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("nama_kepala_keluarga")->searchable(),
                TextColumn::make("alamat")->searchable(),
                TextColumn::make("no_telepon")->searchable(),
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
