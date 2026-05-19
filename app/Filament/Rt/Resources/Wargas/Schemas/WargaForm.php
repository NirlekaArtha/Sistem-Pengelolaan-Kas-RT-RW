<?php

namespace App\Filament\Rt\Resources\Wargas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_rt')
                    ->required()
                    ->numeric(),
                TextInput::make('id_user')
                    ->required()
                    ->numeric(),
                TextInput::make('nama_kepala_keluarga')
                    ->required(),
                TextInput::make('alamat')
                    ->required(),
                TextInput::make('no_telepon')
                    ->tel()
                    ->required(),
            ]);
    }
}
