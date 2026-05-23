<?php

namespace App\Filament\Rt\Resources\KasRTS\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class KasRTForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
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
                ->required()
                ->columnSpanFull(),
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
                ->searchable()
                ->required(),
            TextInput::make("jumlah")->numeric()->prefix("Rp")->required(),
            TextInput::make("sumber_tujuan")
                ->label("Sumber/Tujuan")
                ->required(),
            DatePicker::make("tanggal")->required(),
            Textarea::make("keterangan")->default(null)->columnSpanFull(),
        ]);
    }
}
