<?php

namespace App\Observers;

use App\Models\KasBulananRW;
use App\Services\KasBulananRwService;
use Carbon\Carbon;

class KasBulananRwObserver
{
    /**
     * Handle the KasBulananRW "updated" event.
     *
     * When a KasBulananRW record is saved normally (not via saveQuietly),
     * cascade-recalculate all subsequent months so their saldo_awal stays
     * in sync with the updated saldo_akhir.
     *
     * This observer is NOT triggered by saveQuietly() calls inside the service,
     * which prevents infinite cascade loops.
     */
    public function updated(KasBulananRW $kasBulananRW): void
    {
        // Only cascade when saldo_akhir actually changed, as that is what
        // propagates to the next month's saldo_awal.
        if (! $kasBulananRW->wasChanged('saldo_akhir')) {
            return;
        }

        $nextPeriode = Carbon::createFromFormat('Y-m', $kasBulananRW->periode)
            ->addMonth()
            ->format('Y-m');

        KasBulananRwService::recalculateChain(
            $kasBulananRW->id_rw,
            $nextPeriode,
        );
    }
}
