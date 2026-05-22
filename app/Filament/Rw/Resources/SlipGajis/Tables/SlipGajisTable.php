<?php

namespace App\Filament\Rw\Resources\SlipGajis\Tables;

use App\Filament\Rw\Resources\SlipGajis\Pages\EditSlipGaji;
use App\Models\SlipGaji;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

// NOTE: Toolbar "Export Semua" action is registered in ListSlipGajis::getHeaderActions()

class SlipGajisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('petugas.nama')
                    ->label('Nama Petugas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Total Gaji')
                    ->prefix('Rp ')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: '.', decimalSeparator: ',')
                    ->sortable(),
                TextColumn::make('tanggal')
                    ->label('Periode')
                    ->date('F Y')
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
                Action::make('previewPdf')
                    ->label('Preview PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->iconButton()
                    ->tooltip('Preview & Export PDF')
                    ->url(fn (SlipGaji $record): string => route('rw.slip-gaji.preview', ['record' => $record]))
                    ->openUrlInNewTab(),
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit'),
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Hapus'),
            ])
            ->actionsColumnLabel('Aksi')
            ->recordUrl(fn (SlipGaji $record): string => EditSlipGaji::getUrl(['record' => $record]))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
