<?php

namespace App\Filament\Warga\Resources\IuranWargas\Tables;

use App\Models\IuranWarga;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class IuranWargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenisIuran.jenis_iuran')
                    ->label('Jenis Iuran')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('periode')
                    ->label('Periode')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'dibayar'     => 'success',
                        'telat'       => 'danger',
                        'belum bayar' => 'warning',
                        default       => 'gray',
                    })
                    ->icon(fn ($state): string => match ($state) {
                        'dibayar'     => 'heroicon-m-check-circle',
                        'telat'       => 'heroicon-m-x-circle',
                        'belum bayar' => 'heroicon-m-clock',
                        default       => 'heroicon-m-question-mark-circle',
                    })
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        'dibayar'     => 'Dibayar',
                        'telat'       => 'Telat',
                        'belum bayar' => 'Belum Bayar',
                        default       => (string) $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('periode')
                    ->label('Periode')
                    ->options(fn (): array => IuranWarga::query()
                        ->select('periode')
                        ->distinct()
                        ->orderBy('periode', 'desc')
                        ->pluck('periode', 'periode')
                        ->all())
                    ->searchable(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'belum bayar' => 'Belum Bayar',
                        'dibayar' => 'Dibayar',
                        'telat' => 'Telat',
                    ]),
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
            ->recordActions([
                ViewAction::make()
                    ->icon('heroicon-m-eye')
                    ->iconButton()
                    ->label('Lihat'),
            ])
            ->actionsColumnLabel('Aksi')
            ->recordAction('view')
            ->toolbarActions([])
            ->defaultSort('periode', 'desc');
    }
}
