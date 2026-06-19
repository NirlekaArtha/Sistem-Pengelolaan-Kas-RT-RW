<?php

namespace App\Observers;

use App\Models\SetoranRW;
use App\Services\KasBulananRwService;

class SetoranRwObserver
{
    /**
     * Handle the SetoranRW "created" event.
     * RecalculateChain KasBulananRW for the setoran's period.
     */
    public function created(SetoranRW $setoranRW): void
    {
        KasBulananRwService::recalculateChain(
            $setoranRW->id_rw,
            $setoranRW->periode,
        );
    }

    /**
     * Handle the SetoranRW "updated" event.
     * If periode, id_rw, jumlah_setor, or status_validasi changed,
     * recalculateChain the old period/RW as well.
     */
    public function updated(SetoranRW $setoranRW): void
    {
        if (
            $setoranRW->wasChanged('periode') ||
            $setoranRW->wasChanged('id_rw')
        ) {
            $oldRwId = $setoranRW->getOriginal('id_rw') ?? $setoranRW->id_rw;
            $oldPeriode =
                $setoranRW->getOriginal('periode') ?? $setoranRW->periode;
            KasBulananRwService::recalculateChain($oldRwId, $oldPeriode);
        }

        KasBulananRwService::recalculateChain(
            $setoranRW->id_rw,
            $setoranRW->periode,
        );
    }

    /**
     * Handle the SetoranRW "deleted" event.
     * RecalculateChain KasBulananRW for the period the deleted setoran belonged to.
     */
    public function deleted(SetoranRW $setoranRW): void
    {
        KasBulananRwService::recalculateChain(
            $setoranRW->id_rw,
            $setoranRW->periode,
        );
    }
}
