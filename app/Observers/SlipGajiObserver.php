<?php

namespace App\Observers;

use App\Models\Petugas;
use App\Models\SlipGaji;
use App\Services\KasBulananRwService;

class SlipGajiObserver
{
    /**
     * Handle the SlipGaji "created" event.
     * Recalculate KasBulananRW for the petugas's RW and the slip's period.
     */
    public function created(SlipGaji $slipGaji): void
    {
        $rwId = $slipGaji->petugas?->id_rw;

        if (! $rwId) {
            return;
        }

        $periode = KasBulananRwService::getPayrollPeriodForDate(
            $slipGaji->tanggal,
        );
        KasBulananRwService::recalculateChain($rwId, $periode);
    }

    /**
     * Handle the SlipGaji "updated" event.
     * If tanggal or id_petugas changed, also recalculate the OLD period/RW.
     *
     * Note: This observer is NOT triggered when saveQuietly() is used inside
     * KasBulananRwService::recalculateSlipGaji(), preventing cascade loops.
     */
    public function updated(SlipGaji $slipGaji): void
    {
        if (
            $slipGaji->wasChanged('tanggal') ||
            $slipGaji->wasChanged('id_petugas')
        ) {
            $oldPetugasId =
                $slipGaji->getOriginal('id_petugas') ?? $slipGaji->id_petugas;
            $oldPeriode = KasBulananRwService::getPayrollPeriodForDate(
                $slipGaji->getOriginal('tanggal'),
            );
            $oldRwId = Petugas::find($oldPetugasId)?->id_rw;

            if ($oldRwId) {
                KasBulananRwService::recalculateChain($oldRwId, $oldPeriode);
            }
        }

        $rwId = $slipGaji->petugas?->id_rw;

        if (! $rwId) {
            return;
        }

        $periode = KasBulananRwService::getPayrollPeriodForDate(
            $slipGaji->tanggal,
        );
        KasBulananRwService::recalculateChain($rwId, $periode);
    }

    /**
     * Handle the SlipGaji "deleted" event.
     * Recalculate KasBulananRW for the period the deleted slip belonged to.
     */
    public function deleted(SlipGaji $slipGaji): void
    {
        $rwId = $slipGaji->petugas?->id_rw;

        if (! $rwId) {
            return;
        }

        $periode = KasBulananRwService::getPayrollPeriodForDate(
            $slipGaji->tanggal,
        );
        KasBulananRwService::recalculateChain($rwId, $periode);
    }
}
