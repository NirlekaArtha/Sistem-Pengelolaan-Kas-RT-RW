<?php

namespace App\Filament\Rt\Resources\KasBulananRTS\Tables;

use App\Filament\Rt\Resources\KasBulananRTS\Pages\ViewKasBulananRT;
use App\Models\KasBulananRT;
use App\Services\KasBulananRtService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KasBulananRTSTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("periode")->searchable()->sortable(),
                TextColumn::make("total_pendapatan")
                    ->prefix("Rp ")
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: ",",
                        decimalSeparator: ".",
                    )
                    ->sortable(),
                TextColumn::make("total_pengeluaran")
                    ->prefix("Rp ")
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: ",",
                        decimalSeparator: ".",
                    )
                    ->sortable(),
                TextColumn::make("saldo_awal")
                    ->prefix("Rp ")
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: ",",
                        decimalSeparator: ".",
                    )
                    ->sortable(),
                TextColumn::make("saldo_akhir")
                    ->prefix("Rp ")
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: ",",
                        decimalSeparator: ".",
                    )
                    ->sortable(),
                TextColumn::make("total_pendapatan_bersih")
                    ->prefix("Rp ")
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: ",",
                        decimalSeparator: ".",
                    )
                    ->sortable(),
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
                    ->options(
                        fn(): array => KasBulananRT::query()
                            ->select("periode")
                            ->distinct()
                            ->orderBy("periode", "desc")
                            ->pluck("periode", "periode")
                            ->all(),
                    )
                    ->searchable(),
            ])
            ->actions([
                ViewAction::make()->iconButton()->tooltip("Lihat"),
                Action::make("recalculate")
                    ->label("recalculate")
                    ->icon("heroicon-o-arrow-path")
                    ->iconButton()
                    ->tooltip("Kalkulasi Ulang")
                    ->color("info")
                    ->action(function (KasBulananRT $record) {
                        KasBulananRtService::recalculateChain(
                            $record->id_rt,
                            $record->periode,
                        );

                        Notification::make()
                            ->title("Kalkulasi Ulang Berhasil")
                            ->body(
                                "Data kas bulanan periode {$record->periode} dan bulan-bulan setelahnya telah diperbarui.",
                            )
                            ->success()
                            ->send();
                    }),
                Action::make("export")
                    ->label("Preview PDF")
                    ->icon("heroicon-o-document-arrow-down")
                    ->color("success")
                    ->iconButton()
                    ->tooltip("Preview & Export PDF")
                    ->url(
                        fn(KasBulananRT $record): string => route(
                            "rt.kas-bulanan.preview",
                            ["record" => $record],
                        ),
                    )
                    ->openUrlInNewTab(),
            ])
            ->actionsColumnLabel("aksi")
            ->recordUrl(
                fn(KasBulananRT $record): string => ViewKasBulananRT::getUrl([
                    "record" => $record,
                ]),
            )
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
