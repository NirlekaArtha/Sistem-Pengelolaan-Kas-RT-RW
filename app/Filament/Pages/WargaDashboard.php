<?php

namespace App\Filament\Pages;

use App\Filament\Warga\Widgets\StatsIuranWarga;
use App\Filament\Warga\Widgets\TabelIuranDibayarBulanIni;
use App\Filament\Warga\Widgets\TabelIuranBelumBayar;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class WargaDashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';
    protected int|null|array $columns = 1;

    public function getHeading(): string|null|Htmlable
    {
        $nama = auth()->user()?->warga?->nama_kepala_keluarga
            ?? auth()->user()?->name;

        return 'Selamat Datang, ' . $nama;
    }

    public function getSubheading(): string|Htmlable|null
    {
        $nomorRt = auth()->user()?->warga?->rt?->nomor_rt;

        return $nomorRt
            ? 'Informasi iuran Anda di RT ' . $nomorRt
            : 'Informasi iuran dan kewajiban bulanan Anda';
    }

    public function getWidgets(): array
    {
        return [
            StatsIuranWarga::class,
            TabelIuranDibayarBulanIni::class,
            TabelIuranBelumBayar::class,
        ];
    }
}
