<?php

namespace App\Filament\Rt\Resources\KasRTS\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class KasRTForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_rt')
                    ->required()
                    ->numeric(),
                Select::make('tipe')
                    ->options(['masuk' => 'Masuk', 'keluar' => 'Keluar'])
                    ->required(),
                Select::make('jenis')
                    ->options([
            'donasi' => 'Donasi',
            'sponsorship' => 'Sponsorship',
            'hibah' => 'Hibah',
            'hasil usaha' => 'Hasil usaha',
            'operasional' => 'Operasional',
            'kegiatan' => 'Kegiatan',
            'lainnya' => 'Lainnya',
        ])
                    ->required(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                TextInput::make('sumber_tujuan')
                    ->required(),
                Textarea::make('keterangan')
                    ->default(null)
                    ->columnSpanFull(),
                DatePicker::make('tanggal')
                    ->required(),
            ]);
    }
}
