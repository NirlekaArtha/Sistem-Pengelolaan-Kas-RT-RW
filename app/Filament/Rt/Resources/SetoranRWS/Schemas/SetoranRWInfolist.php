<?php

namespace App\Filament\Rt\Resources\SetoranRWS\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SetoranRWInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_rt')
                    ->numeric(),
                TextEntry::make('id_rw')
                    ->numeric(),
                TextEntry::make('periode'),
                TextEntry::make('tanggal_setor')
                    ->date(),
                TextEntry::make('jumlah_setor')
                    ->numeric(),
                TextEntry::make('status_validasi')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
