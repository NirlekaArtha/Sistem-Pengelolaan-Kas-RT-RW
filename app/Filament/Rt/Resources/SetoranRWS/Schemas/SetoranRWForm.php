<?php

namespace App\Filament\Rt\Resources\SetoranRWS\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SetoranRWForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pengajuan Setoran RW')
                ->description('Form pengajuan setoran RW dari RT')
                ->icon('heroicon-o-banknotes')
                ->columns(2)
                ->schema([
                    TextInput::make('periode')
                        ->label('Periode')
                        ->type('month')
                        ->placeholder('YYYY-MM')
                        ->rule('date_format:Y-m')
                        ->required(),
                    DatePicker::make('tanggal_setor')->required(),
                    TextInput::make('jumlah_setor')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),
                ]),
        ]);
    }
}
