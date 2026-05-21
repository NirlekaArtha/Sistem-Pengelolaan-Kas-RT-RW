<?php

namespace App\Filament\Rw\Resources\Petugas\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PetugasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('id_rw')
                    ->default(fn () => auth()->user()?->rw?->id)
                    ->required(),
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
