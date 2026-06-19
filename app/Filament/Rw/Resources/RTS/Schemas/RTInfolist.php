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
            Section::make('Akun Login')
                ->description('Informasi akun RT yang terhubung ke data ini')
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

            Section::make('Informasi RT')
                ->description('Detail data RT')
                ->icon('heroicon-o-home')
                ->columns(2)
                ->schema([
                    TextEntry::make('nomor_rt')
                        ->label('Nomor RT')
                        ->badge()
                        ->color('success'),

                    TextEntry::make('nama')->label('Nama RT'),

                    TextEntry::make('alamat')
                        ->label('Alamat')
                        ->columnSpanFull(),

                    TextEntry::make('no_telepon')->label('No. Telepon'),
                ]),
        ]);
    }
}
