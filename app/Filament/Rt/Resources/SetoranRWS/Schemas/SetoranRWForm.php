<?php

namespace App\Filament\Rt\Resources\SetoranRWS\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SetoranRWForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make("periode")
                ->native(false)
                ->displayFormat("Y-m")
                ->closeOnDateSelection()
                ->placeholder("Tahun-Bulan")
                ->required(),
            DatePicker::make("tanggal_setor")->required(),
            TextInput::make("jumlah_setor")
                ->numeric()
                ->prefix("Rp")
                ->required(),
        ]);
    }
}
