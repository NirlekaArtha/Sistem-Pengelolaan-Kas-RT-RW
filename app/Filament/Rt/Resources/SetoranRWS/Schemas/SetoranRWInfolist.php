<?php

namespace App\Filament\Rt\Resources\SetoranRWS\Schemas;

use App\Support\Periode;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SetoranRWInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Setoran')
                ->description('Detail pengajuan setoran RT ke RW')
                ->icon('heroicon-o-paper-airplane')
                ->columns(2)
                ->schema([
                    TextEntry::make('periode')
                        ->label('Periode')
                        ->formatStateUsing(fn ($state) => Periode::label($state)),

                    TextEntry::make('tanggal_setor')
                        ->label('Tanggal Setor')
                        ->date(),

                    TextEntry::make('jumlah_setor')
                        ->label('Jumlah Setor')
                        ->money('IDR'),

                    TextEntry::make('status_validasi')
                        ->label('Status Validasi')
                        ->badge(),
                ]),
        ]);
    }
}
