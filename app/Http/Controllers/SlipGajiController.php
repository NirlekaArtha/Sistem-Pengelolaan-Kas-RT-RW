<?php

namespace App\Http\Controllers;

use App\Models\SlipGaji;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class SlipGajiController extends Controller
{
    private function getSingleSlipData($recordId)
    {
        $record = SlipGaji::findOrFail($recordId);
        $rw = auth()->user()?->rw;

        // Security check
        if (!$rw || $record->petugas->id_rw !== $rw->id) {
            abort(403, 'Unauthorized action.');
        }

        $petugas = $record->petugas;
        $kasbons = $petugas->kasbons()
            ->whereYear('tanggal', $record->tanggal->year)
            ->whereMonth('tanggal', $record->tanggal->month)
            ->get();

        return compact('record', 'rw', 'petugas', 'kasbons');
    }

    public function preview($recordId)
    {
        $data = $this->getSingleSlipData($recordId);
        return view('rw.slip-gaji.preview', $data);
    }

    public function download($recordId)
    {
        $data = $this->getSingleSlipData($recordId);
        $html = view('rw.slip-gaji.pdf', $data)->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "slip_gaji_{$data['petugas']->nama}_{$data['record']->tanggal->format('Y_m')}.pdf";

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function getAllSlipsData()
    {
        $rw = auth()->user()?->rw;

        if (!$rw) {
            abort(403, 'Unauthorized action.');
        }

        // Get all slip gajis for current RW in the current month
        $records = SlipGaji::whereHas('petugas', function ($q) use ($rw) {
                $q->where('id_rw', $rw->id);
            })
            ->whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->get();

        foreach ($records as $record) {
            $record->kasbons = $record->petugas->kasbons()
                ->whereYear('tanggal', $record->tanggal->year)
                ->whereMonth('tanggal', $record->tanggal->month)
                ->get();
        }

        return compact('rw', 'records');
    }

    public function previewAll()
    {
        $data = $this->getAllSlipsData();
        return view('rw.slip-gaji.preview-all', $data);
    }

    public function downloadAll()
    {
        $data = $this->getAllSlipsData();
        $html = view('rw.slip-gaji.pdf-all', $data)->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "laporan_semua_slip_gaji_RW_{$data['rw']->nomor_rw}_" . now()->format('Y_m') . ".pdf";

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
