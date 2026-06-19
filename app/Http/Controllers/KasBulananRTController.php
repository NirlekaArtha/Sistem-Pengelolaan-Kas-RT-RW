<?php

namespace App\Http\Controllers;

use App\Enums\KasTipe;
use App\Enums\SetoranStatusValidasi;
use App\Models\IuranWarga;
use App\Models\KasBulananRT;
use App\Models\KasRT;
use App\Models\SetoranRW;
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

        // 1. Saldo awal
        $saldoAwal = $record->saldo_awal;

        // 2. Total pendapatan kas harian RT (tipe masuk)
        $totalPendapatanKasHarian = KasRT::where('id_rt', $rtId)
            ->where('tipe', KasTipe::MASUK->value)
            ->where('tanggal', 'like', "{$record->periode}-%")
            ->sum('jumlah');

        // 3. Total pengeluaran kas harian RT (tipe keluar)
        $totalPengeluaranKasHarian = KasRT::where('id_rt', $rtId)
            ->where('tipe', KasTipe::KELUAR->value)
            ->where('tanggal', 'like', "{$record->periode}-%")
            ->sum('jumlah');

        // 4. Total pendapatan iuran warga
        $totalPendapatanIuranWarga = IuranWarga::join(
            'jenis_iuran_wargas',
            'iuran_wargas.id_jenis_iuran',
            '=',
            'jenis_iuran_wargas.id',
        )
            ->where('jenis_iuran_wargas.id_rt', $rtId)
            ->where('iuran_wargas.tanggal_bayar', 'like', "{$record->periode}-%")
            ->sum('jenis_iuran_wargas.jumlah');

        // 5. Total pengeluaran setoran ke RW (validated)
        $totalPengeluaranSetoranRW = SetoranRW::where('id_rt', $rtId)
            ->where('periode', $record->periode)
            ->where('status_validasi', SetoranStatusValidasi::VALID->value)
            ->sum('jumlah_setor');

        // 6. Total semua pemasukan
        $totalSemuaPemasukan = $totalPendapatanKasHarian + $totalPendapatanIuranWarga;

        // 7. Total semua pengeluaran
        $totalSemuaPengeluaran = $totalPengeluaranKasHarian + $totalPengeluaranSetoranRW;

        // 8. Total bersih pendapatan
        $totalBersihPendapatan = $totalSemuaPemasukan - $totalSemuaPengeluaran;

        // 9. Saldo akhir periode
        $saldoAkhirPeriode = $saldoAwal + $totalBersihPendapatan;

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
