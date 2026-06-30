<?php

namespace App\Filament\Rt\Resources\IuranWargas\Tables;

use App\Enums\IuranWargaStatus;
use App\Filament\Rt\Resources\IuranWargas\Pages\ViewIuranWarga;
use App\Models\IuranWarga;
use App\Support\Periode;
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

class IuranWargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('warga.nama_kepala_keluarga')
                    ->label('Nama Warga')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenisIuran.jenis_iuran')
                    ->label('Jenis Iuran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable()
                    ->badge(),
                TextColumn::make('periode')
                    ->formatStateUsing(fn ($state) => Periode::label($state))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_bayar')->date()->sortable(),
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
                    ->options(fn (): array => Periode::selectOptions(
                        IuranWarga::query()
                            ->select('periode')
                            ->distinct()
                            ->orderBy('periode', 'desc')
                            ->pluck('periode')
                            ->all(),
                    ))
                    ->searchable(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(IuranWargaStatus::class),
                Filter::make('tanggal_bayar')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    'tanggal_bayar',
                                    '>=',
                                    $date,
                                ),
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    'tanggal_bayar',
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
                fn ($record): string => ViewIuranWarga::getUrl([
                    'record' => $record,
                ]),
            )
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
