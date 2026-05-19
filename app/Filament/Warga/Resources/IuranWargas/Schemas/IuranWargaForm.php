<?php

namespace App\Filament\Warga\Resources\IuranWargas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class IuranWargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_warga')
                    ->required()
                    ->numeric(),
                TextInput::make('id_jenis_iuran')
                    ->required()
                    ->numeric(),
                TextInput::make('id_rt')
                    ->required()
                    ->numeric(),
                TextInput::make('periode')
                    ->required(),
                DatePicker::make('tanggal_bayar'),
                Select::make('status')
                    ->options(['belum bayar' => 'Belum bayar', 'dibayar' => 'Dibayar', 'telat' => 'Telat'])
                    ->default('belum bayar')
                    ->required(),
            ]);
    }
}
