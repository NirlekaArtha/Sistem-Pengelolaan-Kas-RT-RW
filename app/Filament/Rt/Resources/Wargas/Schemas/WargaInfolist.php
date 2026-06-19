<?php

namespace App\Filament\Rt\Resources\Wargas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Akun Login')
                ->description('Informasi akun warga yang terhubung ke data ini')
                ->icon('heroicon-o-user-circle')
                ->columns(2)
                ->schema([
                    TextEntry::make('user.name')
                        ->label('Username')
                        ->placeholder('-'),

                    TextEntry::make('user.email')
                        ->label('Email')
                        ->placeholder('-')
                        ->columnSpanFull(),
                ]),

            Section::make('Data Warga')
                ->description('Detail kepala keluarga')
                ->icon('heroicon-o-home')
                ->columns(2)
                ->schema([
                    TextEntry::make('nama_kepala_keluarga')->label(
                        'Nama Kepala Keluarga',
                    ),

                    TextEntry::make('rt.nomor_rt')
                        ->label('RT')
                        ->placeholder('-'),

                    TextEntry::make('alamat')
                        ->label('Alamat')
                        ->columnSpanFull(),

                    TextEntry::make('no_telepon')->label('No. Telepon'),
                ]),
        ]);
    }
}
