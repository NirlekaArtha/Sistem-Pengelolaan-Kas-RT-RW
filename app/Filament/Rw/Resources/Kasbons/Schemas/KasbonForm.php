<?php

namespace App\Filament\Rw\Resources\Kasbons\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KasbonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Kasbon')
                    ->description('Pengajuan kasbon untuk petugas RW')
                    ->icon('heroicon-o-currency-dollar')
                    ->columns(2)
                    ->schema([
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
                            ->prefix('Rp')
                            ->required()
                            ->numeric(),

                        DatePicker::make('tanggal')
                            ->required(),
                    ]),
            ]);
    }
}
