<?php

namespace App\Observers;

use App\Models\KasBulananRT;
use App\Services\KasBulananRtService;
use Carbon\Carbon;

class KasBulananRtObserver
{
    /**
     * Handle the KasBulananRT "updated" event.
     *
     * When a KasBulananRT record is saved normally (not via saveQuietly),
     * cascade-recalculate all subsequent months so their saldo_awal stays
     * in sync with the updated saldo_akhir.
     *
     * This observer is NOT triggered by saveQuietly() calls inside the service,
     * which prevents infinite cascade loops.
     */
    public function updated(KasBulananRT $kasBulananRT): void
    {
        // Only cascade when saldo_akhir actually changed, as that is what
        // propagates to the next month's saldo_awal.
        if (! $kasBulananRT->wasChanged('saldo_akhir')) {
            return;
        }

        $nextPeriode = Carbon::createFromFormat('Y-m', $kasBulananRT->periode)
            ->addMonth()
            ->format('Y-m');

        KasBulananRtService::recalculateChain(
            $kasBulananRT->id_rt,
            $nextPeriode,
        );
    }
}
