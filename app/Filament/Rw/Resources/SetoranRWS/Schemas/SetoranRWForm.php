<?php

namespace App\Filament\Rw\Resources\SetoranRWS\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class SetoranRWForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_rt')
                    ->relationship(
                        'rt',
                        'nama',
                        fn ($query) => $query->where('id_rw', auth()->user()?->rw?->id)
                    )
                    ->label('Nama RT')
                    ->searchable()
                    ->preload()
                    ->required(),
                Hidden::make('id_rw')
                    ->default(fn () => auth()->user()?->rw?->id)
                    ->required(),
                DatePicker::make('periode')
                    ->label('Periode')
                    ->native(false)
                    ->displayFormat('Y-m')
                    ->format('Y-m')
                    ->required(),
                DatePicker::make('tanggal_setor')
                    ->label('Tanggal Setor')
                    ->required(),
                TextInput::make('jumlah_setor')
                    ->label('Jumlah Setor')
                    ->numeric()
                    ->required(),
                ToggleButtons::make('status_validasi')
                    ->label('Status Validasi')
                    ->options([
                        'pending' => 'Pending',
                        'valid' => 'Valid',
                        'ditolak' => 'Ditolak',
                    ])
                    ->colors([
                        'pending' => 'warning',
                        'valid' => 'success',
                        'ditolak' => 'danger',
                    ])
                    ->icons([
                        'pending' => 'heroicon-o-clock',
                        'valid' => 'heroicon-o-check-circle',
                        'ditolak' => 'heroicon-o-x-circle',
                    ])
                    ->inline()
                    ->required(),
            ]);
    }
}
