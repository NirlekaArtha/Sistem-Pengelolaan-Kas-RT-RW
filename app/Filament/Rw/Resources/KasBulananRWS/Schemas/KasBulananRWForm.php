<?php

namespace App\Filament\Rw\Resources\KasBulananRWS\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
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

                Section::make('Periode dan Ringkasan')
                    ->description('Data utama laporan kas bulanan RW')
                    ->icon('heroicon-o-calendar-days')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('periode')
                            ->native(false)
                            ->displayFormat('Y-m')
                            ->format('Y-m')
                            ->required(),

                        TextInput::make('saldo_awal')
                            ->label('Saldo Awal')
                            ->prefix('Rp')
                            ->required()
                            ->numeric(),

                        TextInput::make('total_pendapatan')
                            ->label('Total Pendapatan')
                            ->prefix('Rp')
                            ->required()
                            ->numeric(),

                        TextInput::make('total_pengeluaran')
                            ->label('Total Pengeluaran')
                            ->prefix('Rp')
                            ->required()
                            ->numeric(),

                        TextInput::make('total_pendapatan_bersih')
                            ->label('Pendapatan Bersih')
                            ->prefix('Rp')
                            ->required()
                            ->numeric(),

                        TextInput::make('saldo_akhir')
                            ->label('Saldo Akhir')
                            ->prefix('Rp')
                            ->required()
                            ->numeric(),
                    ]),
            ]);
    }
}
