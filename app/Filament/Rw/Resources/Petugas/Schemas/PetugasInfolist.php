<?php

namespace App\Filament\Rw\Resources\Petugas\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section; // Pastikan namespace ini sesuai dengan arsitektur Anda
use Filament\Schemas\Schema;

class PetugasInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("Informasi Petugas")
                ->description("Data dasar petugas RW")
                ->icon("heroicon-o-identification")
                ->columns(2)
                ->schema([
                    TextEntry::make("rw.nomor_rw")
                        ->label("RW")
                        ->badge()
                        ->color("info"),

                    TextEntry::make("tugas")->badge()->color(
                        fn($state): string => match ($state) {
                            "satpam" => "success",
                            "kebersihan" => "warning",
                            "sampah" => "danger",
                            default => "gray",
                        },
                    ),

                    TextEntry::make("nama")->label("Nama Petugas"),

                    TextEntry::make("gaji_pokok")
                        ->label("Gaji Pokok")
                        ->money("IDR"),

                    TextEntry::make("alamat")
                        ->label("Alamat")
                        ->columnSpanFull(),
                ]),

            Section::make("Waktu")
                ->icon("heroicon-o-clock")
                ->columns(2)
                ->collapsed()
                ->schema([
                    TextEntry::make("created_at")
                        ->label("Dibuat Pada")
                        ->dateTime("d F Y, H:i")
                        ->placeholder("-"),

                    TextEntry::make("updated_at")
                        ->label("Terakhir Diperbarui")
                        ->dateTime("d F Y, H:i")
                        ->placeholder("-"),
                ]),

            Section::make("Riwayat Kasbon")
                ->description("Seluruh kasbon yang pernah diajukan petugas ini")
                ->icon("heroicon-o-currency-dollar")
                ->schema([
                    RepeatableEntry::make("kasbons")
                        ->label("") // Dikosongkan agar tampil lebih bersih
                        ->columns(2) // Membuat field sejajar seperti kolom tabel
                        ->schema([
                            TextEntry::make("tanggal")
                                ->label("Tanggal")
                                ->date("d F Y"),

                            TextEntry::make("jumlah")
                                ->label("Jumlah")
                                ->money("IDR"),
                        ]),
                ]),

            Section::make("Riwayat Slip Gaji")
                ->description(
                    "Slip gaji yang pernah diterbitkan untuk petugas ini",
                )
                ->icon("heroicon-o-document-currency-dollar")
                ->schema([
                    RepeatableEntry::make("slipGajis")
                        ->label("")
                        ->columns(3) // Tiga field sejajar untuk 3 data
                        ->schema([
                            TextEntry::make("tanggal")
                                ->label("Periode")
                                ->date("F Y"),

                            TextEntry::make("total")
                                ->label("Total")
                                ->money("IDR"),

                            TextEntry::make("file_path")
                                ->label("File")
                                ->placeholder("-"),
                        ]),
                ]),
        ]);
    }
}
