<?php

namespace App\Http\Controllers;

use App\Models\KasBulananRT;
use App\Services\KasBulananRtService;
use Dompdf\Dompdf;
use Dompdf\Options;

class KasBulananRTController extends Controller
{
    private function getReportData($recordId)
    {
        $record = KasBulananRT::findOrFail($recordId);
        $rt = auth()->user()?->rt;

        // Security check
        if (! $rt || $record->id_rt !== $rt->id) {
            abort(403, 'Unauthorized action.');
        }

        $rtId = $rt->id;

        $totals = KasBulananRtService::calculateTotals(
            $rtId,
            $record->periode,
            $record->saldo_awal,
        );

        $saldoAwal = $totals['saldo_awal'];
        $totalPendapatanKasHarian = $totals['total_pendapatan_kas_harian'];
        $totalPengeluaranKasHarian = $totals['total_pengeluaran_kas_harian'];
        $totalPendapatanIuranWarga = $totals['total_pendapatan_iuran_warga'];
        $totalPengeluaranSetoranRW = $totals['total_pengeluaran_setoran_rw'];
        $totalSemuaPemasukan = $totals['total_pendapatan'];
        $totalSemuaPengeluaran = $totals['total_pengeluaran'];
        $totalBersihPendapatan = $totals['total_pendapatan_bersih'];
        $saldoAkhirPeriode = $totals['saldo_akhir'];

        return compact(
            'record',
            'rt',
            'saldoAwal',
            'totalPendapatanKasHarian',
            'totalPengeluaranKasHarian',
            'totalPendapatanIuranWarga',
            'totalPengeluaranSetoranRW',
            'totalSemuaPemasukan',
            'totalSemuaPengeluaran',
            'totalBersihPendapatan',
            'saldoAkhirPeriode'
        );
    }

    public function preview($recordId)
    {
        $data = $this->getReportData($recordId);

        return view('rt.kas-bulanan.preview', $data);
    }

    public function download($recordId)
    {
        $data = $this->getReportData($recordId);

        $html = view('rt.kas-bulanan.pdf', $data)->render();

        $options = new Options;
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "laporan bulanan RT {$data['rt']->nomor_rt} periode {$data['record']->periode}.pdf";

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    private function getAnnualReportData($tahun)
    {
        $rt = auth()->user()?->rt;

        // Security check
        if (! $rt) {
            abort(403, 'Unauthorized action.');
        }

        $rtId = $rt->id;

        $records = KasBulananRT::where('id_rt', $rtId)
            ->where('periode', 'like', "{$tahun}-%")
            ->orderBy('periode', 'asc')
            ->get();

        $totalPendapatan = $records->sum('total_pendapatan');
        $totalPengeluaran = $records->sum('total_pengeluaran');
        $pendapatanBersih = $totalPendapatan - $totalPengeluaran;

        return compact(
            'rt',
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

        return view('rt.kas-bulanan.preview-tahunan', $data);
    }

    public function downloadTahunan($tahun)
    {
        $data = $this->getAnnualReportData($tahun);

        $html = view('rt.kas-bulanan.pdf-tahunan', $data)->render();

        $options = new Options;
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "laporan tahunan RT {$data['rt']->nomor_rt} tahun {$data['tahun']}.pdf";

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
