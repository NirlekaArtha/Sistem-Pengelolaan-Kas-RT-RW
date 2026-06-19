<?php

namespace App\Filament\Rt\Resources\IuranWargas\Schemas;

use App\Enums\IuranWargaStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IuranWargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Form Iuran Warga')
                ->description('Form input iuran warga untuk RT')
                ->icon('heroicon-o-document-text')
                ->columns(2)
                ->schema([
                    ToggleButtons::make('status')
                        ->options(IuranWargaStatus::class)
                        ->inline()
                        ->required()
                        ->columnSpanFull(),
                    Select::make('id_warga')
                        ->relationship(
                            'warga',
                            'nama_kepala_keluarga',
                            fn ($query) => $query->where(
                                'id_rt',
                                auth()->user()?->rt?->id,
                            ),
                        )
                        ->label('Nama Warga')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('id_jenis_iuran')
                        ->relationship(
                            'jenisIuran',
                            'jenis_iuran',
                            fn ($query) => $query->where(
                                'id_rt',
                                auth()->user()?->rt?->id,
                            ),
                        )
                        ->label('Jenis Iuran')
                        ->required()
                        ->searchable()
                        ->preload(),
                    DatePicker::make('periode')
                        ->label('Periode')
                        ->native(false)
                        ->placeholder('tahun-bulan')
                        ->displayFormat('Y-m')
                        ->format('Y-m')
                        ->closeOnDateSelection()
                        ->required(),
                    DatePicker::make('tanggal_bayar'),
                ]),
        ]);
    }
}
