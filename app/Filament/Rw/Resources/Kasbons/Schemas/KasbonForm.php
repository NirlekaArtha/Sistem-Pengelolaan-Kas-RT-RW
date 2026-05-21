<?php

namespace App\Filament\Rw\Resources\Kasbons\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KasbonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_petugas')
                    ->relationship(
                        'petugas',
                        'nama',
                        fn ($query) => $query->where('id_rw', auth()->user()?->rw?->id)
                    )
                    ->label('Nama Petugas')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
            ]);
    }
}

