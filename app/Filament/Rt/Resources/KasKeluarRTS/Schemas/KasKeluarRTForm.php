<?php

namespace App\Filament\Rt\Resources\KasKeluarRTS\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class KasKeluarRTForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_rt')
                    ->required()
                    ->numeric(),
                Select::make('jenis')
                    ->options(['operasional' => 'Operasional', 'kegiatan' => 'Kegiatan', 'lainnya' => 'Lainnya'])
                    ->required(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                Textarea::make('penerima')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('keterangan')
                    ->default(null)
                    ->columnSpanFull(),
                DatePicker::make('tanggal')
                    ->required(),
            ]);
    }
}
