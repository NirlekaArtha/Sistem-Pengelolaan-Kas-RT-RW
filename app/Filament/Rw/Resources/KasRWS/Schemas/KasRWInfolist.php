<?php

namespace App\Filament\Rw\Resources\KasRWS\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KasRWInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("Informasi Transaksi")
                ->description("Data utama transaksi kas RW")
                ->icon("heroicon-o-banknotes")
                ->columns(2)
                ->schema([
                    TextEntry::make("tipe")
                        ->label("Tipe Transaksi")
                        ->badge()
                        ->icon(
                            fn($state) => match ($state) {
                                "masuk" => "heroicon-o-arrow-trending-up",
                                "keluar" => "heroicon-o-arrow-trending-down",
                                default => "heroicon-o-question-mark-circle",
                            },
                        )
                        ->color(
                            fn($state) => match ($state) {
                                "masuk" => "success",
                                "keluar" => "danger",
                                default => "gray",
                            },
                        ),

                    TextEntry::make("jenis")
                        ->label("Kategori")
                        ->badge()
                        ->color("info"),

                    TextEntry::make("jumlah")
                        ->label("Nominal")
                        ->money("IDR")
                        ->weight("bold")
                        ->color(
                            fn($record) => $record->tipe === "masuk"
                                ? "success"
                                : "danger",
                        ),

                    TextEntry::make("tanggal")
                        ->label("Tanggal Transaksi")
                        ->date("d F Y"),
                ]),

            Section::make("Detail Tambahan")
                ->description("Informasi pendukung transaksi")
                ->icon("heroicon-o-document-text")
                ->schema([
                    TextEntry::make("sumber_tujuan")
                        ->label("Sumber / Tujuan")
                        ->placeholder("-"),

                    TextEntry::make("keterangan")
                        ->label("Keterangan")
                        ->placeholder("Tidak ada keterangan")
                        ->prose(),
                ]),
        ]);
    }
}
