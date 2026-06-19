<?php

namespace App\Observers;

use App\Models\Kasbon;
use App\Services\KasBulananRwService;

class KasbonObserver
{
    /**
     * Handle the Kasbon "created" event.
     *
     * 1. Recalculate SlipGaji total for the petugas (gaji_pokok - Σ kasbon).
     * 2. Recalculate KasBulananRW for the RW's period.
     *    (KasBulananRwService::recalculateSlipGaji uses saveQuietly so it won't
     *     re-fire SlipGajiObserver; we call recalculate() directly to ensure
     *     the updated SlipGaji total is reflected.)
     */
    public function created(Kasbon $kasbon): void
    {
        $tanggal = $kasbon->tanggal;
        $petugasId = $kasbon->id_petugas;

        KasBulananRwService::recalculateSlipGaji($petugasId, $tanggal);
    }

    /**
     * Handle the Kasbon "updated" event.
     * If tanggal or id_petugas changed, recalculate the OLD period too.
     */
    public function updated(Kasbon $kasbon): void
    {
        if (
            $kasbon->wasChanged('tanggal') ||
            $kasbon->wasChanged('id_petugas')
        ) {
            $oldPetugasId =
                $kasbon->getOriginal('id_petugas') ?? $kasbon->id_petugas;
            $oldTanggal = $kasbon->getOriginal('tanggal');

            KasBulananRwService::recalculateSlipGaji(
                $oldPetugasId,
                $oldTanggal,
            );
        }

        $tanggal = $kasbon->tanggal;
        $petugasId = $kasbon->id_petugas;

        KasBulananRwService::recalculateSlipGaji($petugasId, $tanggal);
    }

    /**
     * Handle the Kasbon "deleted" event.
     * Recalculate SlipGaji and KasBulananRW for the period.
     */
    public function deleted(Kasbon $kasbon): void
    {
        $tanggal = $kasbon->tanggal;
        $petugasId = $kasbon->id_petugas;

        KasBulananRwService::recalculateSlipGaji($petugasId, $tanggal);
    }
}
