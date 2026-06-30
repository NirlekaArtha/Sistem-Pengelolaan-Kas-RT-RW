<?php

namespace App\Filament\Rw\Resources\SlipGajis\Tables;

use App\Enums\SlipGajiStatus;
use App\Filament\Rw\Resources\SlipGajis\Pages\ViewSlipGaji;
use App\Models\SlipGaji;
use App\Support\Periode;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
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
use Illuminate\Database\Eloquent\Collection;

// NOTE: Toolbar "Export Semua" action is registered in ListSlipGajis::getHeaderActions()

class SlipGajisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("petugas.nama")
                    ->label("Nama Petugas")
                    ->searchable()
                    ->sortable(),
                TextColumn::make("total")
                    ->label("Total Gaji")
                    ->prefix("Rp ")
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: ".",
                        decimalSeparator: ",",
                    )
                    ->sortable(),
                TextColumn::make("periode")
                    ->label("Periode")
                    ->formatStateUsing(fn ($state) => Periode::label($state))
                    ->sortable(),
                TextColumn::make("status")
                    ->label("Status")
                    ->searchable()
                    ->badge(),
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
                SelectFilter::make("petugas")
                    ->label("Petugas")
                    ->relationship("petugas", "nama")
                    ->searchable(),
                SelectFilter::make("status")
                    ->label("Status")
                    ->options(SlipGajiStatus::class),
                Filter::make("periode")
                    ->form([
                        DatePicker::make("from")->label("Dari Periode"),
                        DatePicker::make("until")->label("Sampai Periode"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data["from"] ?? null,
                                fn(
                                    Builder $query,
                                    $date,
                                ): Builder => $query->where(
                                    "periode",
                                    ">=",
                                    $date instanceof \DateTimeInterface ? $date->format('Y-m') : substr((string) $date, 0, 7),
                                ),
                            )
                            ->when(
                                $data["until"] ?? null,
                                fn(
                                    Builder $query,
                                    $date,
                                ): Builder => $query->where(
                                    "periode",
                                    "<=",
                                    $date instanceof \DateTimeInterface ? $date->format('Y-m') : substr((string) $date, 0, 7),
                                ),
                            );
                    }),
            ])
            ->actions([
                ViewAction::make()->iconButton()->tooltip("Lihat"),
                Action::make("previewPdf")
                    ->label("Preview PDF")
                    ->icon("heroicon-o-document-arrow-down")
                    ->color("success")
                    ->iconButton()
                    ->tooltip("Preview & Export PDF")
                    ->url(
                        fn(SlipGaji $record): string => route(
                            "rw.slip-gaji.preview",
                            ["record" => $record],
                        ),
                    )
                    ->openUrlInNewTab(),
                Action::make("tandaiDibayar")
                    ->label("")
                    ->icon("heroicon-o-check-circle")
                    ->color("success")
                    ->requiresConfirmation()
                    ->modalHeading("Tandai slip gaji ini telah dibayar?")
                    ->modalDescription(
                        "Kas RW akan berkurang setelah status slip gaji menjadi telah dibayar.",
                    )
                    ->action(function (SlipGaji $record): void {
                        $record->update([
                            "status" => SlipGajiStatus::TELAH_DIBAYAR,
                        ]);
                    })
                    ->visible(
                        fn(SlipGaji $record): bool => $record->status ===
                            SlipGajiStatus::BELUM_DIBAYAR,
                    ),
                EditAction::make()->iconButton()->tooltip("Edit"),
                DeleteAction::make()->iconButton()->tooltip("Hapus"),
            ])
            ->actionsColumnLabel("Aksi")
            ->recordUrl(
                fn(SlipGaji $record): string => ViewSlipGaji::getUrl([
                    "record" => $record,
                ]),
            )
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make("tandaiDibayar")
                        ->label("Tandai Dibayar")
                        ->icon("heroicon-o-check-circle")
                        ->color("success")
                        ->requiresConfirmation()
                        ->modalHeading(
                            "Tandai slip gaji terpilih telah dibayar?",
                        )
                        ->modalDescription(
                            "Semua slip gaji terpilih yang belum dibayar akan diubah menjadi telah dibayar.",
                        )
                        ->action(function (Collection $records): void {
                            $records
                                ->where("status", SlipGajiStatus::BELUM_DIBAYAR)
                                ->each(
                                    fn(SlipGaji $record) => $record->update([
                                        "status" =>
                                            SlipGajiStatus::TELAH_DIBAYAR,
                                    ]),
                                );
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
