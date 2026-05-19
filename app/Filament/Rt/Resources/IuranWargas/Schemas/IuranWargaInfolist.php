<?php

namespace App\Filament\Rt\Resources\IuranWargas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class IuranWargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_warga')
                    ->numeric(),
                TextEntry::make('id_jenis_iuran')
                    ->numeric(),
                TextEntry::make('id_rt')
                    ->numeric(),
                TextEntry::make('periode'),
                TextEntry::make('tanggal_bayar')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('status')
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
