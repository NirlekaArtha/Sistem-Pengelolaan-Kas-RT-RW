<?php

use App\Services\GenerateMonthlyIuranWargaService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command(
    'iuran-warga:generate-monthly {--date= : Tanggal acuan format Y-m-d}',
    function (): int {
        $dateOption = $this->option('date');

        $result = GenerateMonthlyIuranWargaService::run(
            filled($dateOption) ? Carbon::parse($dateOption) : null,
        );

        $this->info(
            "Periode {$result['period']}: {$result['created_unpaid']} iuran belum bayar dibuat, {$result['marked_late']} iuran periode {$result['previous_period']} ditandai telat.",
        );

        return 0;
    },
)->purpose('Tandai iuran bulan lalu yang belum dibayar menjadi telat dan buat iuran bulan berjalan.');

Schedule::command('iuran-warga:generate-monthly')
    ->monthlyOn(1, '00:05')
    ->withoutOverlapping();
