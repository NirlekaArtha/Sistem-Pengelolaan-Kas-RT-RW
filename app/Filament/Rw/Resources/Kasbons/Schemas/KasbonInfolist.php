<?php

namespace App\Filament\Rw\Resources\Kasbons\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KasbonInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("Informasi Kasbon")
                ->description("Detail pengajuan kasbon petugas")
                ->icon("heroicon-o-currency-dollar")
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextEntry::make("petugas.nama")->label("Nama Petugas"),

                    TextEntry::make("petugas.tugas")->label("Tugas")->badge(),

                    TextEntry::make("jumlah")
                        ->label("Jumlah")
                        ->money("IDR")
                        ->color("danger")
                        ->weight("bold"),

                    TextEntry::make("tanggal")->label("Tanggal")->date("d F Y"),
                ]),
        ]);
    }
}
