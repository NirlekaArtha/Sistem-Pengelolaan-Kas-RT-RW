<?php

namespace App\Filament\Rw\Resources\SlipGajis\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlipGajiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Slip Gaji')
                ->description('Detail slip gaji petugas RW')
                ->icon('heroicon-o-document-currency-dollar')
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    TextEntry::make('petugas.nama')->label('Nama Petugas'),

                    TextEntry::make('petugas.tugas')->label('Tugas')->badge(),

                    TextEntry::make('total')->label('Total Gaji')->money('IDR'),

                    TextEntry::make('tanggal')->label('Periode')->date('d F Y'),

                    TextEntry::make('status')->label('Status')->badge(),
                ]),
        ]);
    }
}
