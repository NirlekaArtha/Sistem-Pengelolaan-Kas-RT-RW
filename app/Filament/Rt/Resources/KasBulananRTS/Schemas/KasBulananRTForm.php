<?php

namespace App\Filament\Rt\Resources\KasBulananRTS\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KasBulananRTForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_rt')
                    ->required()
                    ->numeric(),
                TextInput::make('periode')
                    ->required(),
                TextInput::make('total_pendapatan')
                    ->required()
                    ->numeric(),
                TextInput::make('total_pengeluaran')
                    ->required()
                    ->numeric(),
                TextInput::make('saldo_awal')
                    ->required()
                    ->numeric(),
                TextInput::make('saldo_akhir')
                    ->required()
                    ->numeric(),
                TextInput::make('total_pendapatan_bersih')
                    ->required()
                    ->numeric(),
                TextInput::make('file_path')
                    ->default(null),
            ]);
    }
}
