<?php

namespace App\Filament\Rw\Resources\SlipGajis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlipGajiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('file_path')
                    ->default(null),

                Section::make('Data Slip Gaji')
                    ->description('Pengajuan dan periode slip gaji petugas')
                    ->icon('heroicon-o-document-currency-dollar')
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

                        TextInput::make('total')
                            ->label('Total Gaji')
                            ->prefix('Rp')
                            ->numeric()
                            ->required(),

                        DatePicker::make('tanggal')
                            ->label('Periode')
                            ->native(false)
                            ->displayFormat('F Y')
                            ->required(),
                    ]),
            ]);
    }
}
