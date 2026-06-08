<?php

namespace App\Filament\Rw\Resources\SetoranRWS\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SetoranRWInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("Informasi Setoran")
                ->description("Data utama setoran")
                ->icon("heroicon-o-document-text")
                ->columns(2)
                ->schema([
                    TextEntry::make("rw.nomor_rw")
                        ->label("RW")
                        ->badge()
                        ->color("info"),

                    TextEntry::make("rt.nama")->label("RT"),

                    TextEntry::make("periode")
                        ->label("Periode")
                        ->formatStateUsing(
                            fn($state) => $state
                                ? \Carbon\Carbon::createFromFormat(
                                    "Y-m",
                                    $state,
                                )->translatedFormat("F Y")
                                : "-",
                        ),

                    TextEntry::make("tanggal_setor")
                        ->label("Tanggal Setor")
                        ->date("d F Y"),

                    TextEntry::make("jumlah_setor")
                        ->money("IDR")
                        ->weight("bold")
                        ->color("success"),
                ]),

            Section::make("Status & Validasi")
                ->description("Informasi proses validasi setoran")
                ->icon("heroicon-o-check-badge")
                ->schema([
                    TextEntry::make("status_validasi")
                        ->label("Status Validasi")
                        ->badge()
                        ->color(
                            fn($state) => match ($state) {
                                "pending" => "warning",
                                "valid" => "success",
                                "ditolak" => "danger",
                                default => "gray",
                            },
                        )
                        ->formatStateUsing(
                            fn($state) => match ($state) {
                                "pending" => "Pending",
                                "valid" => "Valid",
                                "ditolak" => "Ditolak",
                                default => (string) $state,
                            },
                        ),
                ]),
        ]);
    }
}
