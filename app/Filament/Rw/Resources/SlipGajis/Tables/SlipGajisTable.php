<?php

namespace App\Filament\Rw\Resources\SlipGajis\Tables;

use App\Filament\Rw\Resources\SlipGajis\Pages\ViewSlipGaji;
use App\Models\SlipGaji;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: '.',
                        decimalSeparator: ',',
                    )
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
                SelectFilter::make('petugas')
                    ->label('Petugas')
                    ->relationship('petugas', 'nama')
                    ->searchable(),
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    'tanggal',
                                    '>=',
                                    $date,
                                ),
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    'tanggal',
                                    '<=',
                                    $date,
                                ),
                            );
                    }),
            ])
            ->actions([
                ViewAction::make()->iconButton()->tooltip('Lihat'),
                Action::make('previewPdf')
                    ->label('Preview PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->iconButton()
                    ->tooltip('Preview & Export PDF')
                    ->url(
                        fn (SlipGaji $record): string => route(
                            'rw.slip-gaji.preview',
                            ['record' => $record],
                        ),
                    )
                    ->openUrlInNewTab(),
                EditAction::make()->iconButton()->tooltip('Edit'),
                DeleteAction::make()->iconButton()->tooltip('Hapus'),
            ])
            ->actionsColumnLabel('Aksi')
            ->recordUrl(
                fn (SlipGaji $record): string => ViewSlipGaji::getUrl([
                    'record' => $record,
                ]),
            )
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
