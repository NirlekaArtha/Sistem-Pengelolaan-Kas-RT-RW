<?php

namespace App\Filament\Rw\Resources\SlipGajis\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SlipGajiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_petugas')
                    ->numeric(),
                TextEntry::make('total')
                    ->numeric(),
                TextEntry::make('tanggal')
                    ->date(),
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
