<?php

namespace App\Filament\Rw\Resources\Petugas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PetugasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_rw')
                    ->required()
                    ->numeric(),
                Select::make('tugas')
                    ->options(['satpam' => 'Satpam', 'kebersihan' => 'Kebersihan', 'sampah' => 'Sampah'])
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('alamat')
                    ->required(),
                TextInput::make('gaji_pokok')
                    ->required()
                    ->numeric(),
            ]);
    }
}
