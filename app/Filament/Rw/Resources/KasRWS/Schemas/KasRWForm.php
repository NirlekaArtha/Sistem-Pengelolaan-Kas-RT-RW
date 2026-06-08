<?php

namespace App\Filament\Rw\Resources\KasRWS\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KasRWForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Hidden::make("id_rw")
                ->default(fn() => auth()->user()?->rw?->id)
                ->required(),
            Section::make("Data Kas")
                ->description("Masukkan transaksi kas harian RW")
                ->columnSpanFull()
                ->icon("heroicon-o-banknotes")
                ->columns(2)
                ->schema([
                    ToggleButtons::make("tipe")
                        ->options([
                            "masuk" => "Masuk",
                            "keluar" => "Keluar",
                        ])
                        ->colors([
                            "masuk" => "success",
                            "keluar" => "danger",
                        ])
                        ->icons([
                            "masuk" => "heroicon-o-arrow-trending-up",
                            "keluar" => "heroicon-o-arrow-trending-down",
                        ])
                        ->inline()
                        ->required(),

                    Select::make("jenis")
                        ->options([
                            "donasi" => "Donasi",
                            "sponsorship" => "Sponsorship",
                            "hibah" => "Hibah",
                            "hasil usaha" => "Hasil usaha",
                            "operasional" => "Operasional",
                            "kegiatan" => "Kegiatan",
                            "lainnya" => "Lainnya",
                        ])
                        ->required(),

                    TextInput::make("jumlah")
                        ->label("Nominal")
                        ->prefix("Rp")
                        ->required()
                        ->numeric(),

                    DatePicker::make("tanggal")->required(),
                ]),

            Section::make("Keterangan")
                ->description("Sumber, tujuan, dan catatan tambahan")
                ->columnSpanFull()
                ->icon("heroicon-o-chat-bubble-left-right")
                ->schema([
                    TextInput::make("sumber_tujuan")->required(),

                    Textarea::make("keterangan")
                        ->default(null)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
