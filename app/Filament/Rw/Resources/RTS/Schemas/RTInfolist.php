<?php

namespace App\Filament\Rw\Resources\RTS\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RTInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Section 1: Informasi RT (Atas)
            Section::make("Informasi RT")
                ->description("Detail data RT beserta akun yang terhubung")
                ->icon("heroicon-o-home")
                ->columns(2)
                ->schema([
                    TextEntry::make("user.name")
                        ->label("Akun")
                        ->placeholder("-"),

                    TextEntry::make("nomor_rt")
                        ->label("Nomor RT")
                        ->badge()
                        ->color("success"),

                    TextEntry::make("nama")->label("Nama RT"),

                    TextEntry::make("alamat")
                        ->label("Alamat")
                        ->columnSpanFull(),

                    TextEntry::make("no_telepon")->label("No. Telepon"),
                ]),

            // Section 2: Waktu (Bawah)
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
        ]);
    }
}
