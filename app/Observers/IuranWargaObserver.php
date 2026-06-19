<?php

namespace App\Observers;

use App\Models\IuranWarga;
use App\Services\KasBulananRtService;
use Carbon\Carbon;

class IuranWargaObserver
{
    /**
     * Handle the IuranWarga "created" event.
     * Recalculate KasBulananRT for the periode of the new iuran entry.
     */
    public function created(IuranWarga $iuranWarga): void
    {
        if (! $iuranWarga->tanggal_bayar || ! $iuranWarga->id_rt) {
            return;
        }

        $periode = $iuranWarga->tanggal_bayar->format('Y-m');
        KasBulananRtService::recalculateChain($iuranWarga->id_rt, $periode);
    }

    /**
     * Handle the IuranWarga "updated" event.
     * If tanggal_bayar or id_rt changed, also recalculate the OLD period/RT.
     */
    public function updated(IuranWarga $iuranWarga): void
    {
        if (
            $iuranWarga->wasChanged('tanggal_bayar') ||
            $iuranWarga->wasChanged('id_rt')
        ) {
            $oldRtId = $iuranWarga->getOriginal('id_rt') ?? $iuranWarga->id_rt;
            $oldTanggal = $iuranWarga->getOriginal('tanggal_bayar');

            if ($oldTanggal && $oldRtId) {
                $oldPeriode = Carbon::parse($oldTanggal)->format('Y-m');
                KasBulananRtService::recalculateChain($oldRtId, $oldPeriode);
            }
        }

        if (! $iuranWarga->tanggal_bayar || ! $iuranWarga->id_rt) {
            return;
        }

        $periode = $iuranWarga->tanggal_bayar->format('Y-m');
        KasBulananRtService::recalculateChain($iuranWarga->id_rt, $periode);
    }

    /**
     * Handle the IuranWarga "deleted" event.
     * Recalculate KasBulananRT for the period the deleted iuran belonged to.
     */
    public function deleted(IuranWarga $iuranWarga): void
    {
        if (! $iuranWarga->tanggal_bayar || ! $iuranWarga->id_rt) {
            return;
        }

        $periode = $iuranWarga->tanggal_bayar->format('Y-m');
        KasBulananRtService::recalculateChain($iuranWarga->id_rt, $periode);
    }
}
