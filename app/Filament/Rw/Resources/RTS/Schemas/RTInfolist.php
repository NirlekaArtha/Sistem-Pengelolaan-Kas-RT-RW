<?php

namespace App\Filament\Rw\Resources\RTS\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RTInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_rw')
                    ->numeric(),
                TextEntry::make('id_user')
                    ->numeric(),
                TextEntry::make('nomor_rt'),
                TextEntry::make('nama'),
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
