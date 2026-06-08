<?php

namespace App\Filament\Rw\Resources\SlipGajis\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlipGajiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Slip Gaji')
                    ->description('Detail slip gaji petugas RW')
                    ->icon('heroicon-o-document-currency-dollar')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('petugas.nama')
                            ->label('Nama Petugas'),

                        TextEntry::make('petugas.tugas')
                            ->label('Tugas')
                            ->badge(),

                        TextEntry::make('total')
                            ->label('Total Gaji')
                            ->money('IDR'),

                        TextEntry::make('tanggal')
                            ->label('Periode')
                            ->date('d F Y'),

                        TextEntry::make('file_path')
                            ->label('File Slip')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                Section::make('Waktu')
                    ->icon('heroicon-o-clock')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d F Y, H:i')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime('d F Y, H:i')
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
