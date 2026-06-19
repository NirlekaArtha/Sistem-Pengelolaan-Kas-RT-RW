<?php

namespace App\Filament\Rw\Resources\Petugas\Schemas;

use App\Enums\PetugasTugas;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
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

                Section::make('Data Petugas')
                    ->description('Informasi utama petugas RW')
                    ->icon('heroicon-o-identification')
                    ->columns(2)
                    ->schema([
                        Select::make('tugas')
                            ->options(PetugasTugas::class)
                            ->required(),

                        TextInput::make('nama')
                            ->required(),

                        TextInput::make('alamat')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Penggajian')
                    ->description('Nominal gaji pokok petugas')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        TextInput::make('gaji_pokok')
                            ->prefix('Rp')
                            ->required()
                            ->numeric(),
                    ]),
            ]);
    }
}
