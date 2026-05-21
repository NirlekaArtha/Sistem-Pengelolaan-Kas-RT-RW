<?php

namespace App\Filament\Rw\Resources\RTS\Tables;

use App\Filament\Rw\Resources\RTS\Pages\EditRT;
use App\Models\RT;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RTSTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_rt')
                    ->label('Nomor RT')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama RT')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Nama Akun RT')
                    ->searchable()
                    ->default('-'),
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('no_telepon')
                    ->label('No. Telepon')
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
            ->actions([
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit'),
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Hapus'),
            ])
            ->actionsColumnLabel('Aksi')
            ->recordUrl(fn (RT $record): string => EditRT::getUrl(['record' => $record]))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
