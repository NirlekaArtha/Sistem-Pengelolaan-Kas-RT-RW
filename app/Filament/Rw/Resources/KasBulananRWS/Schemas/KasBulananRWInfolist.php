<?php

namespace App\Filament\Rw\Resources\KasBulananRWS\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KasBulananRWInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_rw')
                    ->numeric(),
                TextEntry::make('periode'),
                TextEntry::make('total_pendapatan')
                    ->numeric(),
                TextEntry::make('total_pengeluaran')
                    ->numeric(),
                TextEntry::make('total_pendapatan_bersih')
                    ->numeric(),
                TextEntry::make('saldo_awal')
                    ->numeric(),
                TextEntry::make('saldo_akhir')
                    ->numeric(),
                TextEntry::make('file_path')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
