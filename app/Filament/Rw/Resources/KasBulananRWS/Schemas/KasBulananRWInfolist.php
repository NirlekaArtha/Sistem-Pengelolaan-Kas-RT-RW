<?php

namespace App\Filament\Rw\Resources\KasBulananRWS\Schemas;

use Carbon\Carbon;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KasBulananRWInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Umum')
                ->description('Data periode laporan kas')
                ->icon('heroicon-o-information-circle')
                ->columns(1)
                ->schema([
                    TextEntry::make('periode')
                        ->label('Periode')
                        ->formatStateUsing(
                            fn ($state) => $state
                                ? Carbon::createFromFormat(
                                    'Y-m',
                                    $state,
                                )->translatedFormat('F Y')
                                : '-',
                        ),
                ]),

            Section::make('Ringkasan Arus Kas')
                ->description(
                    'Detail perputaran dana dan akumulasi saldo akhir',
                )
                ->icon('heroicon-o-arrows-right-left')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('saldo_awal')
                            ->label('Saldo Awal')
                            ->money('IDR')
                            ->color('gray'),

                        TextEntry::make('total_pendapatan')
                            ->label('Total Pendapatan (+)')
                            ->money('IDR')
                            ->color('success'),

                        TextEntry::make('total_pengeluaran')
                            ->label('Total Pengeluaran (-)')
                            ->money('IDR')
                            ->color('danger'),
                    ]),

                    Grid::make(3)
                        ->extraAttributes([
                            'class' => 'border-t pt-4 mt-4 border-gray-200 dark:border-gray-700',
                        ])
                        ->schema([
                            TextEntry::make('total_pendapatan_bersih')
                                ->label('Pendapatan Bersih (Net)')
                                ->money('IDR')
                                ->weight('bold'),

                            TextEntry::make('saldo_akhir')
                                ->label('Saldo Akhir')
                                ->money('IDR')
                                ->weight('bold')
                                ->color('primary'),
                        ]),
                ]),
        ]);
    }
}
