<?php

namespace App\Filament\Rw\Resources\KasBulananRWS\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KasBulananRWForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('id_rw')
                    ->default(fn () => auth()->user()?->rw?->id)
                    ->required(),
                DatePicker::make('periode')
                    ->native(false)
                    ->displayFormat('Y-m')
                    ->format('Y-m')
                    ->required(),
                TextInput::make('total_pendapatan')
                    ->required()
                    ->numeric(),
                TextInput::make('total_pengeluaran')
                    ->required()
                    ->numeric(),
                TextInput::make('total_pendapatan_bersih')
                    ->required()
                    ->numeric(),
                TextInput::make('saldo_awal')
                    ->required()
                    ->numeric(),
                TextInput::make('saldo_akhir')
                    ->required()
                    ->numeric(),
            ]);
    }
}
