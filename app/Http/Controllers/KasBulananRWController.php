<?php

namespace App\Http\Controllers;

use App\Models\KasBulananRW;
use App\Services\KasBulananRwService;
use Dompdf\Dompdf;
use Dompdf\Options;

class KasBulananRWController extends Controller
{
    private function getReportData($recordId)
    {
        $record = KasBulananRW::findOrFail($recordId);
        $rw = auth()->user()?->rw;

        // Security check
        if (! $rw || $record->id_rw !== $rw->id) {
            abort(403, 'Unauthorized action.');
        }

        $rwId = $rw->id;

        $totals = KasBulananRwService::calculateTotals(
            $rwId,
            $record->periode,
            $record->saldo_awal,
        );

        $saldoAwal = $totals['saldo_awal'];
        $totalPendapatanKasHarian = $totals['total_pendapatan_kas_harian'];
        $totalPengeluaranKasHarian = $totals['total_pengeluaran_kas_harian'];
        $totalPengeluaranGajiPetugas = $totals['total_pengeluaran_gaji_petugas'];
        $totalKasbonPetugas = $totals['total_kasbon_petugas'];
        $totalPemasukanSetoranRT = $totals['total_pemasukan_setoran_rt'];
        $totalSemuaPemasukan = $totals['total_pendapatan'];
        $totalSemuaPengeluaran = $totals['total_pengeluaran'];
        $totalBersihPendapatan = $totals['total_pendapatan_bersih'];
        $saldoAkhirPeriode = $totals['saldo_akhir'];

        return compact(
            'record',
            'rw',
            'saldoAwal',
            'totalPendapatanKasHarian',
            'totalPengeluaranKasHarian',
            'totalPengeluaranGajiPetugas',
            'totalKasbonPetugas',
            'totalPemasukanSetoranRT',
            'totalSemuaPemasukan',
            'totalSemuaPengeluaran',
            'totalBersihPendapatan',
            'saldoAkhirPeriode'
        );
    }

    public function preview($recordId)
    {
        $data = $this->getReportData($recordId);

        return view('rw.kas-bulanan.preview', $data);
    }

    public function download($recordId)
    {
        $data = $this->getReportData($recordId);

        $html = view('rw.kas-bulanan.pdf', $data)->render();

        $options = new Options;
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "laporan bulanan RW {$data['rw']->nomor_rw} periode {$data['record']->periode}.pdf";

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    private function getAnnualReportData($tahun)
    {
        $rw = auth()->user()?->rw;

        // Security check
        if (! $rw) {
            abort(403, 'Unauthorized action.');
        }

        $rwId = $rw->id;

        $records = KasBulananRW::where('id_rw', $rwId)
            ->where('periode', 'like', "{$tahun}-%")
            ->orderBy('periode', 'asc')
            ->get();

        $totalPendapatan = $records->sum('total_pendapatan');
        $totalPengeluaran = $records->sum('total_pengeluaran');
        $pendapatanBersih = $totalPendapatan - $totalPengeluaran;

        return compact(
            'rw',
            'tahun',
            'records',
            'totalPendapatan',
            'totalPengeluaran',
            'pendapatanBersih'
        );
    }

    public function previewTahunan($tahun)
    {
        $data = $this->getAnnualReportData($tahun);

        return view('rw.kas-bulanan.preview-tahunan', $data);
    }

    public function downloadTahunan($tahun)
    {
        $data = $this->getAnnualReportData($tahun);

        $html = view('rw.kas-bulanan.pdf-tahunan', $data)->render();

        $options = new Options;
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "laporan tahunan RW {$data['rw']->nomor_rw} tahun {$data['tahun']}.pdf";

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
