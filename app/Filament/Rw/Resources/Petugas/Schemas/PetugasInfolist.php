<?php

namespace App\Filament\Rw\Resources\Petugas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PetugasInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Petugas')
                ->description('Data dasar petugas RW')
                ->icon('heroicon-o-identification')
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    TextEntry::make('rw.nomor_rw')
                        ->label('RW')
                        ->badge()
                        ->color('info'),

                    TextEntry::make('tugas')->badge(),

                    TextEntry::make('nama')->label('Nama Petugas'),

                    TextEntry::make('gaji_pokok')
                        ->label('Gaji Pokok')
                        ->money('IDR'),

                    TextEntry::make('alamat')
                        ->label('Alamat')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
