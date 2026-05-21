<?php

namespace App\Http\Controllers;

use App\Models\KasBulananRW;
use App\Models\KasRW;
use App\Models\SlipGaji;
use App\Models\SetoranRW;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class KasBulananRWController extends Controller
{
    private function getReportData($recordId)
    {
        $record = KasBulananRW::findOrFail($recordId);
        $rw = auth()->user()?->rw;

        // Security check
        if (!$rw || $record->id_rw !== $rw->id) {
            abort(403, 'Unauthorized action.');
        }

        $rwId = $rw->id;

        // 1. Saldo awal
        $saldoAwal = $record->saldo_awal;

        // 2. Total pendapatan dari kas harian
        $totalPendapatanKasHarian = KasRW::where('id_rw', $rwId)
            ->where('tipe', 'masuk')
            ->where('tanggal', 'like', "{$record->periode}-%")
            ->sum('jumlah');

        // 3. Total pengeluaran kas harian
        $totalPengeluaranKasHarian = KasRW::where('id_rw', $rwId)
            ->where('tipe', 'keluar')
            ->where('tanggal', 'like', "{$record->periode}-%")
            ->sum('jumlah');

        // 4. Total pengeluaran gaji petugas
        $totalPengeluaranGajiPetugas = SlipGaji::whereHas('petugas', function ($q) use ($rwId) {
                $q->where('id_rw', $rwId);
            })
            ->where('tanggal', 'like', "{$record->periode}-%")
            ->sum('total');

        // 5. Total pemasukan setoran dari RT
        $totalPemasukanSetoranRT = SetoranRW::where('id_rw', $rwId)
            ->where('periode', $record->periode)
            ->where('status_validasi', 'valid')
            ->sum('jumlah_setor');

        // 6. Total semua pemasukan
        $totalSemuaPemasukan = $totalPendapatanKasHarian + $totalPemasukanSetoranRT;

        // 7. Total semua pengeluaran
        $totalSemuaPengeluaran = $totalPengeluaranKasHarian + $totalPengeluaranGajiPetugas;

        // 8. Total bersih pendapatan
        $totalBersihPendapatan = $totalSemuaPemasukan - $totalSemuaPengeluaran;

        // 9. Saldo akhir periode
        $saldoAkhirPeriode = $saldoAwal + $totalBersihPendapatan;

        return compact(
            'record',
            'rw',
            'saldoAwal',
            'totalPendapatanKasHarian',
            'totalPengeluaranKasHarian',
            'totalPengeluaranGajiPetugas',
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

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "laporan bulanan RW {$data['rw']->nomor_rw} periode {$data['record']->periode}.pdf";

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function getAnnualReportData($tahun)
    {
        $rw = auth()->user()?->rw;

        // Security check
        if (!$rw) {
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

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "laporan tahunan RW {$data['rw']->nomor_rw} tahun {$data['tahun']}.pdf";

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
