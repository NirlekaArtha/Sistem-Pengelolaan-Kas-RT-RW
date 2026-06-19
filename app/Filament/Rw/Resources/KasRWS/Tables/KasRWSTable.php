<?php

namespace App\Filament\Rw\Resources\KasRWS\Tables;

use Filament\Forms\Components\DatePicker;
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

class KasRWSTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("tipe")
                    ->searchable()
                    ->badge()
                    ->color(
                        fn(string $state): string => match ($state) {
                            "masuk" => "success",
                            "keluar" => "danger",
                            default => "gray",
                        },
                    )
                    ->icon(
                        fn(string $state): string => match ($state) {
                            "masuk" => "heroicon-o-arrow-trending-up",
                            "keluar" => "heroicon-o-arrow-trending-down",
                            default => "heroicon-o-question-mark-circle",
                        },
                    ),
                TextColumn::make("jenis")
                    ->searchable()
                    ->badge()
                    ->color("info"),
                TextColumn::make("jumlah")
                    ->prefix("Rp ")
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: ",",
                        decimalSeparator: ".",
                    )
                    ->sortable(),
                TextColumn::make("sumber_tujuan")->searchable(),
                TextColumn::make("keterangan")
                    ->searchable()
                    ->limit(20)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen((string) $state) <= 20) {
                            return null;
                        }
                        return $state;
                    }),
                TextColumn::make("tanggal")->date()->sortable(),
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
                SelectFilter::make("tipe")
                    ->label("Tipe")
                    ->options([
                        "masuk" => "Masuk",
                        "keluar" => "Keluar",
                    ]),
                Filter::make("tanggal")
                    ->form([
                        DatePicker::make("from")->label("Dari Tanggal"),
                        DatePicker::make("until")->label("Sampai Tanggal"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data["from"] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    "tanggal",
                                    ">=",
                                    $date,
                                ),
                            )
                            ->when(
                                $data["until"] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    "tanggal",
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
            ->recordAction("view")
            ->actionsColumnLabel("Aksi")
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
