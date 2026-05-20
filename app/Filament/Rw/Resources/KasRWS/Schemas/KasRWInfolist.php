<?php

namespace App\Filament\Rw\Resources\KasRWS\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KasRWInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tipe')
                    ->badge(),
                TextEntry::make('jenis')
                    ->badge(),
                TextEntry::make('jumlah')
                    ->numeric(),
                TextEntry::make('sumber_tujuan'),
                TextEntry::make('keterangan')
                    ->placeholder('-')
                    ->columnSpanFull(),
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
