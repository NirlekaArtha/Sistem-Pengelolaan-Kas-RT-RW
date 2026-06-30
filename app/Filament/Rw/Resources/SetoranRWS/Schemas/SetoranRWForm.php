<?php

namespace App\Filament\Rw\Resources\SetoranRWS\Schemas;

use App\Enums\SetoranStatusValidasi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SetoranRWForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('id_rw')
                    ->default(fn () => auth()->user()?->rw?->id)
                    ->required(),

                Section::make('Pengajuan Setoran')
                    ->description('Isi data pengajuan setoran RW dari RT yang bersangkutan')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->columns(2)
                    ->schema([
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

                        TextInput::make('periode')
                            ->label('Periode')
                            ->type('month')
                            ->rule('date_format:Y-m')
                            ->required(),

                        DatePicker::make('tanggal_setor')
                            ->label('Tanggal Setor')
                            ->required(),

                        TextInput::make('jumlah_setor')
                            ->label('Jumlah Setor')
                            ->prefix('Rp')
                            ->numeric()
                            ->required(),
                    ]),

                Section::make('Status Validasi')
                    ->description('Validasi pengajuan oleh RW')
                    ->icon('heroicon-o-check-badge')
                    ->schema([
                        ToggleButtons::make('status_validasi')
                            ->label('Status Validasi')
                            ->options(SetoranStatusValidasi::class)
                            ->inline()
                            ->required(),
                    ]),
            ]);
    }
}
