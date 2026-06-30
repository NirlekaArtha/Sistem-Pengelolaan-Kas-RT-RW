<?php

namespace App\Filament\Rw\Resources\SlipGajis\Schemas;

use App\Enums\SlipGajiStatus;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class SlipGajiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('file_path')
                    ->default(null),

                Hidden::make('status')
                    ->default(SlipGajiStatus::BELUM_DIBAYAR->value)
                    ->visibleOn('create'),

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
                            ->required()
                            ->hiddenOn('create'),

                        TextInput::make('periode')
                            ->label('Periode')
                            ->type('month')
                            ->rule('date_format:Y-m')
                            ->rule(fn (Get $get, $record) => Rule::unique('slip_gajis', 'periode')
                                ->ignore($record?->id)
                                ->where('id_petugas', $get('id_petugas'))
                                ->where('periode', $get('periode')))
                            ->validationMessages([
                                'unique' => 'Slip gaji petugas ini sudah ada untuk periode yang dipilih.',
                            ])
                            ->required()
                            ->dehydrated(),

                        Select::make('status')
                            ->label('Status')
                            ->options(SlipGajiStatus::class)
                            ->required()
                            ->hiddenOn('create'),
                    ]),
            ]);
    }
}
