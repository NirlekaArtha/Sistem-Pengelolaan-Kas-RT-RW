<?php

namespace App\Filament\Rt\Resources\IuranWargas\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class IuranWargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ToggleButtons::make("status")
                ->options([
                    "dibayar" => "Dibayar",
                    "belum bayar" => "Belum bayar",
                    "telat" => "Telat",
                ])
                ->colors([
                    "dibayar" => "success",
                    "belum bayar" => "warning",
                    "telat" => "danger",
                ])
                ->icons([
                    "dibayar" => "heroicon-o-document-text",
                    "telat" => "heroicon-o-clock",
                    "belum bayar" => "heroicon-m-exclamation-circle",
                ])
                ->inline()
                ->required()
                ->columnSpanFull(),
            Select::make("id_warga")
                ->relationship(
                    "warga",
                    "nama_kepala_keluarga",
                    fn($query) => $query->where(
                        "id_rt",
                        auth()->user()?->rt?->id,
                    ),
                )
                ->label("Nama Warga")
                ->searchable()
                ->preload()
                ->required(),
            Select::make("id_jenis_iuran")
                ->relationship(
                    "jenisIuran",
                    "jenis_iuran",
                    fn($query) => $query->where(
                        "id_rt",
                        auth()->user()?->rt?->id,
                    ),
                )
                ->label("Jenis Iuran")
                ->required()
                ->searchable()
                ->preload(),
            DatePicker::make("periode")
                ->label("Periode")
                ->native(false)
                ->placeholder("tahun-bulan")
                ->displayFormat("Y-m")
                ->format("Y-m")
                ->closeOnDateSelection()
                ->required(),
            DatePicker::make("tanggal_bayar"),
        ]);
    }
}
