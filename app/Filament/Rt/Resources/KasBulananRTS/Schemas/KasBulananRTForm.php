<?php

namespace App\Filament\Rt\Resources\KasBulananRTS\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KasBulananRTForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Ringkasan Kas Bulanan RT')
                ->description('Form laporan kas bulanan RT')
                ->icon('heroicon-o-chart-pie')
                ->columns(2)
                ->schema([
                    TextInput::make('periode')->required(),
                    TextInput::make('total_pendapatan')
                        ->prefix('Rp')
                        ->required()
                        ->numeric(),
                    TextInput::make('total_pengeluaran')
                        ->prefix('Rp')
                        ->required()
                        ->numeric(),
                    TextInput::make('saldo_awal')->prefix('Rp')->required()->numeric(),
                    TextInput::make('saldo_akhir')->prefix('Rp')->required()->numeric(),
                    TextInput::make('total_pendapatan_bersih')
                        ->prefix('Rp')
                        ->required()
                        ->numeric(),
                ]),
        ]);
    }
}
