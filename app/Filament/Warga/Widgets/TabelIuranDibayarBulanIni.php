<?php

namespace App\Filament\Warga\Widgets;

use App\Models\IuranWarga;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class TabelIuranDibayarBulanIni extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Iuran Dibayar Bulan Ini';

    public function table(Table $table): Table
    {
        $warga = auth()->user()?->warga;
        $currentPeriode = now()->format('Y-m');

        return $table
            ->query(
                IuranWarga::query()
                    ->when($warga, fn(Builder $q) => $q->where('id_warga', $warga->id))
                    ->where('periode', $currentPeriode)
                    ->where('status', 'dibayar')
                    ->with(['jenisIuran'])
                    ->orderBy('tanggal_bayar', 'desc'),
            )
            ->columns([
                TextColumn::make('jenisIuran.jenis_iuran')
                    ->label('Jenis Iuran')
                    ->sortable(),

                TextColumn::make('jenisIuran.jumlah')
                    ->label('Nominal')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-check-circle')
                    ->formatStateUsing(fn() => 'Lunas'),
            ])
            ->emptyStateHeading('Belum Ada Iuran Dibayar')
            ->emptyStateDescription('Iuran yang sudah dibayar bulan ' . Carbon::now()->translatedFormat('F Y') . ' akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-banknotes')
            ->paginated(false);
    }
}
