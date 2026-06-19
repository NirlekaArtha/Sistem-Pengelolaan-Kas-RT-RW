<?php

namespace App\Filament\Rt\Resources\SetoranRWS\Tables;

use App\Models\SetoranRW;
use Filament\Forms\Components\DatePicker;
use App\Filament\Rt\Resources\SetoranRWS\Pages\ViewSetoranRW;
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

class SetoranRWSTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("periode")->searchable(),
                TextColumn::make("tanggal_setor")->date()->sortable(),
                TextColumn::make("jumlah_setor")
                    ->prefix("Rp ")
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: ",",
                        decimalSeparator: ".",
                    )
                    ->sortable(),
                TextColumn::make("status_validasi")
                    ->searchable()
                    ->badge()
                    ->color(
                        fn(string $state): string => match ($state) {
                            "valid" => "success",
                            "pending" => "warning",
                            "ditolak" => "danger",
                            default => "gray",
                        },
                    )
                    ->icon(
                        fn(string $state): string => match ($state) {
                            "valid" => "heroicon-o-check-circle",
                            "pending" => "heroicon-o-clock",
                            "ditolak" => "heroicon-o-x-circle",
                            default => "heroicon-o-question-mark-circle",
                        },
                    ),
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
                    ->options(fn (): array => SetoranRW::query()
                        ->select("periode")
                        ->distinct()
                        ->orderBy("periode", "desc")
                        ->pluck("periode", "periode")
                        ->all())
                    ->searchable(),
                SelectFilter::make("status_validasi")
                    ->label("Status Validasi")
                    ->options([
                        "pending" => "Pending",
                        "valid" => "Valid",
                        "ditolak" => "Ditolak",
                    ]),
                Filter::make("tanggal_setor")
                    ->form([
                        DatePicker::make("from")->label("Dari Tanggal"),
                        DatePicker::make("until")->label("Sampai Tanggal"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data["from"] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    "tanggal_setor",
                                    ">=",
                                    $date,
                                ),
                            )
                            ->when(
                                $data["until"] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate(
                                    "tanggal_setor",
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
                fn($record): string => ViewSetoranRW::getUrl([
                    "record" => $record,
                ]),
            )
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
