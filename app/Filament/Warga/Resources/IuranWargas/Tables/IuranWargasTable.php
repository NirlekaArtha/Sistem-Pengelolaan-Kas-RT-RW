<?php

namespace App\Filament\Warga\Resources\IuranWargas\Tables;

use App\Enums\IuranWargaStatus;
use App\Models\IuranWarga;
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
                    ->badge(),
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
