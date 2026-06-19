<?php

namespace App\Filament\Rt\Resources\KasRTS\Schemas;

use App\Enums\KasJenis;
use App\Enums\KasTipe;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KasRTForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Kas Harian')
                ->description('Form input transaksi kas harian RT')
                ->icon('heroicon-o-currency-dollar')
                ->columns(2)
                ->schema([
                    ToggleButtons::make('tipe')
                        ->options(KasTipe::class)
                        ->inline()
                        ->required()
                        ->columnSpanFull(),
                    Select::make('jenis')
                        ->options(KasJenis::class)
                        ->searchable()
                        ->required(),
                    TextInput::make('jumlah')->numeric()->prefix('Rp')->required(),
                    TextInput::make('sumber_tujuan')
                        ->label('Sumber/Tujuan')
                        ->required(),
                    DatePicker::make('tanggal')->required(),
                    Textarea::make('keterangan')->default(null)->columnSpanFull(),
                ]),
        ]);
    }
}
