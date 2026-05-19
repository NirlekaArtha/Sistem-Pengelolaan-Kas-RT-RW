<?php

namespace App\Filament\Rt\Resources\Wargas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_rt')
                    ->numeric(),
                TextEntry::make('id_user')
                    ->numeric(),
                TextEntry::make('nama_kepala_keluarga'),
                TextEntry::make('alamat'),
                TextEntry::make('no_telepon'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
