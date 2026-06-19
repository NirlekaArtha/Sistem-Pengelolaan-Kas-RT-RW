<?php

namespace App\Filament\Rw\Resources\KasRWS\Schemas;

use App\Enums\KasTipe;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KasRWInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Transaksi')
                ->description('Data utama transaksi kas RW')
                ->icon('heroicon-o-banknotes')
                ->columns(2)
                ->schema([
                    TextEntry::make('tipe')
                        ->label('Tipe Transaksi')
                        ->badge(),

                    TextEntry::make('jenis')
                        ->label('Kategori')
                        ->badge(),

                    TextEntry::make('jumlah')
                        ->label('Nominal')
                        ->money('IDR')
                        ->weight('bold')
                        ->color(
                            fn ($record) => $record->tipe === KasTipe::MASUK
                                ? 'success'
                                : 'danger',
                        ),

                    TextEntry::make('tanggal')
                        ->label('Tanggal Transaksi')
                        ->date('d F Y'),
                ]),

            Section::make('Detail Tambahan')
                ->description('Informasi pendukung transaksi')
                ->icon('heroicon-o-document-text')
                ->schema([
                    TextEntry::make('sumber_tujuan')
                        ->label('Sumber / Tujuan')
                        ->placeholder('-'),

                    TextEntry::make('keterangan')
                        ->label('Keterangan')
                        ->placeholder('Tidak ada keterangan')
                        ->prose(),
                ]),
        ]);
    }
}
