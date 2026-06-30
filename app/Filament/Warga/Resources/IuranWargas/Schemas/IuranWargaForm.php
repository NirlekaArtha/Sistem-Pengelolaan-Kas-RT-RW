<?php

namespace App\Filament\Warga\Resources\IuranWargas\Schemas;

use App\Enums\IuranWargaStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IuranWargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Iuran Warga')
                    ->description('Form detail iuran warga untuk pencatatan pembayaran')
                    ->icon('heroicon-o-document-text')
                    ->columns(2)
                    ->schema([
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
                            ->type('month')
                            ->rule('date_format:Y-m')
                            ->required(),
                        DatePicker::make('tanggal_bayar'),
                        Select::make('status')
                            ->options(IuranWargaStatus::class)
                            ->default(IuranWargaStatus::BELUM_BAYAR)
                            ->required(),
                    ]),
            ]);
    }
}
