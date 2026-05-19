<?php

namespace App\Filament\Rt\Resources\KasMasukRTS\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KasMasukRTInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_rt')
                    ->numeric(),
                TextEntry::make('jenis')
                    ->badge(),
                TextEntry::make('jumlah')
                    ->numeric(),
                TextEntry::make('sumber'),
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
