<?php

namespace App\Filament\Rw\Resources\Kasbons\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KasbonInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_petugas')
                    ->numeric(),
                TextEntry::make('jumlah')
                    ->numeric(),
                TextEntry::make('tanggal')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
