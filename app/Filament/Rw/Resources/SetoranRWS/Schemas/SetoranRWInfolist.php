<?php

namespace App\Filament\Rw\Resources\SetoranRWS\Schemas;

use Carbon\Carbon;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SetoranRWInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Setoran')
                ->description('Data utama setoran')
                ->icon('heroicon-o-document-text')
                ->columns(2)
                ->schema([
                    TextEntry::make('rw.nomor_rw')
                        ->label('RW')
                        ->badge()
                        ->color('info'),

                    TextEntry::make('rt.nama')->label('RT'),

                    TextEntry::make('periode')
                        ->label('Periode')
                        ->formatStateUsing(
                            fn ($state) => $state
                                ? Carbon::createFromFormat(
                                    'Y-m',
                                    $state,
                                )->translatedFormat('F Y')
                                : '-',
                        ),

                    TextEntry::make('tanggal_setor')
                        ->label('Tanggal Setor')
                        ->date('d F Y'),

                    TextEntry::make('jumlah_setor')
                        ->money('IDR')
                        ->weight('bold')
                        ->color('success'),
                ]),

            Section::make('Status & Validasi')
                ->description('Informasi proses validasi setoran')
                ->icon('heroicon-o-check-badge')
                ->schema([
                    TextEntry::make('status_validasi')
                        ->label('Status Validasi')
                        ->badge(),
                ]),
        ]);
    }
}
