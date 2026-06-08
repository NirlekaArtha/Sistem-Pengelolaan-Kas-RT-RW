<?php

namespace App\Filament\Warga\Widgets;

use App\Models\IuranWarga;
use App\Models\JenisIuranWarga;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TabelIuranBelumBayar extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Iuran Menunggak & Belum Dibayar';

    public function table(Table $table): Table
    {
        $warga = auth()->user()?->warga;

        return $table
            ->query(
                IuranWarga::query()
                    ->when($warga, fn(Builder $q) => $q->where('id_warga', $warga->id))
                    ->whereIn('status', ['telat', 'belum bayar'])
                    ->with(['jenisIuran'])
                    ->orderBy('status', 'asc')   // telat dulu (alphabetical: belum bayar > telat)
                    ->orderBy('periode', 'asc'),  // periode terlama dulu
            )
            ->columns([
                TextColumn::make('jenisIuran.jenis_iuran')
                    ->label('Jenis Iuran')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenisIuran.jumlah')
                    ->label('Nominal')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('periode')
                    ->label('Periode')
                    ->formatStateUsing(fn($state) => \Illuminate\Support\Carbon::parse($state . '-01')
                        ->translatedFormat('F Y'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        'telat'       => 'danger',
                        'belum bayar' => 'warning',
                        default       => 'gray',
                    })
                    ->icon(fn($state): string => match ($state) {
                        'telat'       => 'heroicon-m-x-circle',
                        'belum bayar' => 'heroicon-m-clock',
                        default       => 'heroicon-m-question-mark-circle',
                    })
                    ->formatStateUsing(fn($state): string => match ($state) {
                        'telat'       => 'Tunggakan (Telat)',
                        'belum bayar' => 'Belum Dibayar',
                        default       => (string) $state,
                    }),
            ])
            ->emptyStateHeading('Semua Iuran Lunas!')
            ->emptyStateDescription('Tidak ada iuran yang menunggak atau belum dibayar.')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(10);
    }
}
