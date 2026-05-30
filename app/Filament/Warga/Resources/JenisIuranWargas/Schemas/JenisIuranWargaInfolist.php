<?php

namespace App\Filament\Warga\Resources\JenisIuranWargas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JenisIuranWargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("Informasi Jenis Iuran")
                ->description("Detail jenis iuran wajib di RT Anda")
                ->icon("heroicon-o-tag")
                ->columns(2)
                ->schema([
                    TextEntry::make("jenis_iuran")
                        ->label("Jenis Iuran")
                        ->icon("heroicon-m-tag")
                        ->weight("bold")
                        ->columnSpan(2),

                    TextEntry::make("jumlah")
                        ->label("Jumlah Iuran")
                        ->icon("heroicon-m-currency-dollar")
                        ->money("IDR")
                        ->placeholder("-"),
                ]),

            Section::make("Informasi Waktu")
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
