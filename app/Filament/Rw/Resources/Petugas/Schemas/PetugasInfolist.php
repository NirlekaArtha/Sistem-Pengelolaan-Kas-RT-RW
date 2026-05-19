<?php

namespace App\Filament\Rw\Resources\Petugas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PetugasInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_rw')
                    ->numeric(),
                TextEntry::make('tugas')
                    ->badge(),
                TextEntry::make('nama'),
                TextEntry::make('alamat'),
                TextEntry::make('gaji_pokok')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
