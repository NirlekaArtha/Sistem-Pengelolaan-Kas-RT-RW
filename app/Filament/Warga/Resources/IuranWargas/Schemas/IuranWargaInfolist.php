<?php

namespace App\Filament\Warga\Resources\IuranWargas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IuranWargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("Informasi Iuran")
                ->description("Detail lengkap catatan iuran warga")
                ->icon("heroicon-o-banknotes")
                ->columns(2)
                ->schema([
                    TextEntry::make("jenisIuran.jenis_iuran")
                        ->label("Jenis Iuran")
                        ->icon("heroicon-m-tag")
                        ->weight("bold")
                        ->columnSpan(2),

                    TextEntry::make("periode")
                        ->label("Periode")
                        ->icon("heroicon-m-calendar")
                        ->formatStateUsing(
                            fn($state) => $state
                                ? \Carbon\Carbon::createFromFormat(
                                    "Y-m",
                                    $state,
                                )->translatedFormat("F Y")
                                : "-",
                        ),

                    TextEntry::make("tanggal_bayar")
                        ->label("Tanggal Bayar")
                        ->icon("heroicon-m-calendar-days")
                        ->date("d F Y")
                        ->placeholder("Belum dibayar"),

                    TextEntry::make("status")
                        ->label("Status Pembayaran")
                        ->badge()
                        ->color(
                            fn($state): string => match ($state) {
                                "dibayar" => "success",
                                "telat" => "danger",
                                "belum bayar" => "warning",
                                default => "gray",
                            },
                        )
                        ->icon(
                            fn($state): string => match ($state) {
                                "dibayar" => "heroicon-m-check-circle",
                                "telat" => "heroicon-m-x-circle",
                                "belum bayar" => "heroicon-m-clock",
                                default => "heroicon-m-question-mark-circle",
                            },
                        )
                        ->formatStateUsing(
                            fn($state): string => match ($state) {
                                "dibayar" => "Dibayar",
                                "telat" => "Telat",
                                "belum bayar" => "Belum Bayar",
                                default => (string) $state,
                            },
                        ),

                    TextEntry::make("jenisIuran.jumlah")
                        ->label("Jumlah Iuran")
                        ->icon("heroicon-m-currency-dollar")
                        ->money("IDR")
                        ->placeholder("-"),
                ]),
        ]);
    }
}
