<?php

namespace App\Filament\Rw\Resources\KasRWS\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class KasRWForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('id_rw')
                    ->default(fn () => auth()->user()?->rw?->id)
                    ->required(),
                ToggleButtons::make('tipe')
                    ->options([
                        'masuk' => 'Masuk',
                        'keluar' => 'Keluar',
                    ])
                    ->colors([
                        'masuk' => 'success',
                        'keluar' => 'danger',
                    ])
                    ->icons([
                        'masuk' => 'heroicon-o-arrow-trending-up',
                        'keluar' => 'heroicon-o-arrow-trending-down',
                    ])
                    ->inline()
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
