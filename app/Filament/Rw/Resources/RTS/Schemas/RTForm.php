<?php

namespace App\Filament\Rw\Resources\RTS\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RTForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_rw')
                    ->required()
                    ->numeric(),
                TextInput::make('id_user')
                    ->required()
                    ->numeric(),
                TextInput::make('nomor_rt')
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('alamat')
                    ->required(),
                TextInput::make('no_telepon')
                    ->tel()
                    ->required(),
            ]);
    }
}
