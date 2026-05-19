<?php

namespace App\Filament\Rw\Resources\SetoranRWS\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SetoranRWForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_rt')
                    ->required()
                    ->numeric(),
                TextInput::make('id_rw')
                    ->required()
                    ->numeric(),
                TextInput::make('periode')
                    ->required(),
                DatePicker::make('tanggal_setor')
                    ->required(),
                TextInput::make('jumlah_setor')
                    ->required()
                    ->numeric(),
                Select::make('status_validasi')
                    ->options(['pending' => 'Pending', 'valid' => 'Valid', 'ditolak' => 'Ditolak'])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
