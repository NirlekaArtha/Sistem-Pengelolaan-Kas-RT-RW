<?php

namespace App\Filament\Rt\Resources\JenisIuranWargas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JenisIuranWargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_rt')
                    ->required()
                    ->numeric(),
                TextInput::make('jenis_iuran')
                    ->required(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
            ]);
    }
}
