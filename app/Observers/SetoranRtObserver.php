<?php

namespace App\Observers;

use App\Models\SetoranRW;
use App\Services\KasBulananRtService;

class SetoranRtObserver
{
    /**
     * Handle the SetoranRW "created" event.
     * RecalculateChain KasBulananRT for the setoran's RT and period.
     */
    public function created(SetoranRW $setoranRW): void
    {
        if (!$setoranRW->id_rt) {
            return;
        }

        KasBulananRtService::recalculateChain(
            $setoranRW->id_rt,
            $setoranRW->periode,
        );
    }

    /**
     * Handle the SetoranRW "updated" event.
     * If periode, id_rt, jumlah_setor, or status_validasi changed,
     * recalculateChain the old period/RT as well.
     */
    public function updated(SetoranRW $setoranRW): void
    {
        if (
            $setoranRW->wasChanged("periode") ||
            $setoranRW->wasChanged("id_rt")
        ) {
            $oldRtId = $setoranRW->getOriginal("id_rt") ?? $setoranRW->id_rt;
            $oldPeriode =
                $setoranRW->getOriginal("periode") ?? $setoranRW->periode;

            if ($oldRtId) {
                KasBulananRtService::recalculateChain($oldRtId, $oldPeriode);
            }
        }

        if (!$setoranRW->id_rt) {
            return;
        }

        KasBulananRtService::recalculateChain(
            $setoranRW->id_rt,
            $setoranRW->periode,
        );
    }

    /**
     * Handle the SetoranRW "deleted" event.
     * RecalculateChain KasBulananRT for the period the deleted setoran belonged to.
     */
    public function deleted(SetoranRW $setoranRW): void
    {
        if (!$setoranRW->id_rt) {
            return;
        }

        KasBulananRtService::recalculateChain(
            $setoranRW->id_rt,
            $setoranRW->periode,
        );
    }
}
