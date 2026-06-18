<?php

namespace App\Observers;

use App\Models\KasRW;
use App\Services\KasBulananRwService;
use Carbon\Carbon;

class KasRwObserver
{
    /**
     * Handle the KasRW "created" event.
     * RecalculateChain KasBulananRW for the period of the new entry.
     */
    public function created(KasRW $kasRW): void
    {
        $periode = $kasRW->tanggal->format("Y-m");
        KasBulananRwService::recalculateChain($kasRW->id_rw, $periode);
    }

    /**
     * Handle the KasRW "updated" event.
     * If tanggal or id_rw changed, also recalculateChain the OLD period/RW.
     */
    public function updated(KasRW $kasRW): void
    {
        if ($kasRW->wasChanged("tanggal") || $kasRW->wasChanged("id_rw")) {
            $oldRwId = $kasRW->getOriginal("id_rw") ?? $kasRW->id_rw;
            $oldPeriode = Carbon::parse($kasRW->getOriginal("tanggal"))->format(
                "Y-m",
            );
            KasBulananRwService::recalculateChain($oldRwId, $oldPeriode);
        }

        $periode = $kasRW->tanggal->format("Y-m");
        KasBulananRwService::recalculateChain($kasRW->id_rw, $periode);
    }

    /**
     * Handle the KasRW "deleted" event.
     * RecalculateChain KasBulananRW for the period the deleted entry belonged to.
     */
    public function deleted(KasRW $kasRW): void
    {
        $periode = $kasRW->tanggal->format("Y-m");
        KasBulananRwService::recalculateChain($kasRW->id_rw, $periode);
    }
}
