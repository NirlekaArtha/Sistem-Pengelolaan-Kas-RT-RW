<?php

namespace App\Filament\Rt\Resources\IuranWargas\Schemas;

use App\Support\Periode;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IuranWargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Iuran')
                ->description('Detail data pembayaran iuran warga')
                ->icon('heroicon-o-banknotes')
                ->columns(2)
                ->schema([
                    TextEntry::make('periode')
                        ->label('Periode')
                        ->formatStateUsing(fn ($state) => Periode::label($state)),

                    TextEntry::make('status')->label('Status')->badge(),

                    TextEntry::make('tanggal_bayar')
                        ->label('Tanggal Bayar')
                        ->date()
                        ->placeholder('-'),
                ]),
        ]);
    }
}
