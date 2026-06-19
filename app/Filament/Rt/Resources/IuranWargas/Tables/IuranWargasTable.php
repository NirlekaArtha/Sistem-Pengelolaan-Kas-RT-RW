<?php

namespace App\Filament\Rt\Resources\IuranWargas\Tables;

use App\Models\IuranWarga;
use Filament\Forms\Components\DatePicker;
use App\Filament\Rt\Resources\IuranWargas\Pages\ViewIuranWarga;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class IuranWargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("warga.nama_kepala_keluarga")
                    ->label("Nama Warga")
                    ->searchable()
                    ->sortable(),
                TextColumn::make("jenisIuran.jenis_iuran")
                    ->label("Jenis Iuran")
                    ->searchable()
                    ->sortable(),
                TextColumn::make("status")
                    ->searchable()
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
                SelectFilter::make("periode")
                    ->label("Periode")
                    ->options(fn (): array => IuranWarga::query()
                        ->select("periode")
                        ->distinct()
                        ->orderBy("periode", "desc")
                        ->pluck("periode", "periode")
                        ->all())
                    ->searchable(),
                SelectFilter::make("status")
                    ->label("Status")
                    ->options([
                        "belum bayar" => "Belum Bayar",
                        "dibayar" => "Dibayar",
                        "telat" => "Telat",
                    ]),
                Filter::make("tanggal_bayar")
                    ->form([
                        DatePicker::make("from")->label("Dari Tanggal"),
                        DatePicker::make("until")->label("Sampai Tanggal"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data["from"] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    "tanggal_bayar",
                                    ">=",
                                    $date,
                                ),
                            )
                            ->when(
                                $data["until"] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    "tanggal_bayar",
                                    "<=",
                                    $date,
                                ),
                            );
                    }),
            ])
            ->actions([
                ViewAction::make()->iconButton()->tooltip("Lihat"),
                EditAction::make()->iconButton()->tooltip("Edit"),
                DeleteAction::make()->iconButton()->tooltip("Hapus"),
            ])
            ->actionsColumnLabel("Aksi")
            ->recordUrl(
                fn($record): string => ViewIuranWarga::getUrl([
                    "record" => $record,
                ]),
            )
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
