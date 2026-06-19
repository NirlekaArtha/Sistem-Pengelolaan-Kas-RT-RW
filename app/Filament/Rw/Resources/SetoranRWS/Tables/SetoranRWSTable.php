<?php

namespace App\Filament\Rw\Resources\SetoranRWS\Tables;

use App\Enums\SetoranStatusValidasi;
use App\Filament\Rw\Resources\SetoranRWS\Pages\ViewSetoranRW;
use App\Models\SetoranRW;
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

class SetoranRWSTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rt.nama')
                    ->label('Nama RT')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('periode')
                    ->label('Periode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_setor')
                    ->label('Tanggal Setor')
                    ->date()
                    ->sortable(),
                TextColumn::make('jumlah_setor')
                    ->label('Jumlah Setor')
                    ->prefix('Rp ')
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: '.',
                        decimalSeparator: ',',
                    )
                    ->sortable(),
                TextColumn::make('status_validasi')
                    ->label('Status Validasi')
                    ->searchable()
                    ->badge(),
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
                SelectFilter::make('periode')
                    ->label('Periode')
                    ->options(fn (): array => SetoranRW::query()
                        ->select('periode')
                        ->distinct()
                        ->orderBy('periode', 'desc')
                        ->pluck('periode', 'periode')
                        ->all())
                    ->searchable(),
                SelectFilter::make('rt')
                    ->label('RT')
                    ->relationship('rt', 'nama')
                    ->searchable(),
                SelectFilter::make('status_validasi')
                    ->label('Status Validasi')
                    ->options(SetoranStatusValidasi::class),
                Filter::make('tanggal_setor')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    'tanggal_setor',
                                    '>=',
                                    $date,
                                ),
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    'tanggal_setor',
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
                fn (SetoranRW $record): string => ViewSetoranRW::getUrl([
                    'record' => $record,
                ]),
            )
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
