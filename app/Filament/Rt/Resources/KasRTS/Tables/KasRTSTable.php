<?php

namespace App\Filament\Rt\Resources\KasRTS\Tables;

use App\Enums\KasTipe;
use App\Filament\Rt\Resources\KasRTS\Pages\ViewKasRT;
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

class KasRTSTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sumber_tujuan')
                    ->label('Sumber/Tujuan')
                    ->searchable(),
                TextColumn::make('tipe')
                    ->searchable()
                    ->badge(),
                TextColumn::make('jenis')
                    ->searchable()
                    ->badge(),
                TextColumn::make('jumlah')
                    ->prefix('Rp ')
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: ',',
                        decimalSeparator: '.',
                    )
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->searchable()
                    ->limit(20)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen((string) $state) <= 20) {
                            return null;
                        }

                        return $state;
                    }),
                TextColumn::make('tanggal')->date()->sortable(),
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
                SelectFilter::make('tipe')
                    ->label('Tipe')
                    ->options(KasTipe::class),
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
                EditAction::make()->iconButton()->tooltip('Edit'),
                DeleteAction::make()->iconButton()->tooltip('Hapus'),
            ])
            ->actionsColumnLabel('Aksi')
            ->recordUrl(
                fn ($record): string => ViewKasRT::getUrl([
                    'record' => $record,
                ]),
            )
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
