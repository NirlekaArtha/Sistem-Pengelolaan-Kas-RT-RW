<?php

namespace App\Filament\Rt\Resources\JenisIuranWargas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JenisIuranWargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Jenis Iuran')
                ->description('Detail nominal iuran yang ditetapkan RT')
                ->icon('heroicon-o-tag')
                ->columns(2)
                ->schema([
                    TextEntry::make('jenis_iuran')->label('Jenis Iuran'),

                    TextEntry::make('jumlah')->label('Nominal')->money('IDR'),

                    TextEntry::make('rt.nomor_rt')
                        ->label('RT')
                        ->placeholder('-'),
                ]),
        ]);
    }
}
