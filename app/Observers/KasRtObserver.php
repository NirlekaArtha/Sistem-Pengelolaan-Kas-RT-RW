<?php

namespace App\Observers;

use App\Models\KasRT;
use App\Services\KasBulananRtService;
use Carbon\Carbon;

class KasRtObserver
{
    /**
     * Handle the KasRT "created" event.
     * RecalculateChain KasBulananRT for the period of the new entry.
     */
    public function created(KasRT $kasRT): void
    {
        $periode = $kasRT->tanggal->format("Y-m");
        KasBulananRtService::recalculateChain($kasRT->id_rt, $periode);
    }

    /**
     * Handle the KasRT "updated" event.
     * If tanggal or id_rt changed, also recalculateChain the OLD period/RT.
     */
    public function updated(KasRT $kasRT): void
    {
        if ($kasRT->wasChanged("tanggal") || $kasRT->wasChanged("id_rt")) {
            $oldRtId = $kasRT->getOriginal("id_rt") ?? $kasRT->id_rt;
            $oldPeriode = Carbon::parse($kasRT->getOriginal("tanggal"))->format(
                "Y-m",
            );
            KasBulananRtService::recalculateChain($oldRtId, $oldPeriode);
        }

        $periode = $kasRT->tanggal->format("Y-m");
        KasBulananRtService::recalculateChain($kasRT->id_rt, $periode);
    }

    /**
     * Handle the KasRT "deleted" event.
     * RecalculateChain KasBulananRT for the period the deleted entry belonged to.
     */
    public function deleted(KasRT $kasRT): void
    {
        $periode = $kasRT->tanggal->format("Y-m");
        KasBulananRtService::recalculateChain($kasRT->id_rt, $periode);
    }
}
