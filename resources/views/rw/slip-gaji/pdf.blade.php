<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $petugas->nama }} - Periode {{ \Carbon\Carbon::parse($record->tanggal)->isoFormat('MMMM Y') }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px double #888888;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            color: #111111;
        }
        .header p {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #666666;
        }
        .info-table {
            width: 100%;
            margin-bottom: 25px;
            border-spacing: 0;
        }
        .info-table td {
            padding: 4px 0;
            border: none;
        }
        .info-label {
            font-weight: bold;
            color: #555555;
            width: 120px;
        }
        .info-colon {
            width: 15px;
        }
        /* Table Design */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .report-table th {
            background-color: #f5f5f5;
            color: #333333;
            font-weight: bold;
            text-align: left;
            padding: 10px 12px;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 2px solid #dddddd;
            border-top: 1px solid #dddddd;
        }
        .report-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eeeeee;
            color: #222222;
        }
        .text-right {
            text-align: right;
        }
        .text-danger {
            color: #dc2626;
        }
        .text-success {
            color: #16a34a;
        }
        .font-semibold {
            font-weight: bold;
        }
        .grand-total-row {
            background-color: #f0f7ff;
            border-top: 2px solid #2563eb;
            border-bottom: 2px solid #2563eb;
        }
        .grand-total-row td {
            font-weight: bold;
            color: #1d4ed8;
            font-size: 13px;
        }
        /* Signature block */
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }
        .signature-table {
            width: 100%;
            border: none;
        }
        .signature-table td {
            border: none;
            padding: 0;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-space {
            height: 60px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-title {
            font-size: 11px;
            color: #555555;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>SLIP GAJI PETUGAS</h2>
        <p>Sistem Pengelolaan Kas RT / RW Digital</p>
    </div>
    <table class="info-table">
        <tr>
            <td class="info-label">Wilayah</td>
            <td class="info-colon">:</td>
            <td>RW {{ $rw->nomor_rw }} {{ $rw->nama ? '('.$rw->nama.')' : '' }}</td>
            <td class="info-label">Nama Petugas</td>
            <td class="info-colon">:</td>
            <td>{{ $petugas->nama }}</td>
        </tr>
        <tr>
            <td class="info-label">Periode</td>
            <td class="info-colon">:</td>
            <td>{{ \Carbon\Carbon::parse($record->tanggal)->isoFormat('MMMM Y') }}</td>
            <td class="info-label">Tugas / Role</td>
            <td class="info-colon">:</td>
            <td>{{ ucfirst($petugas->tugas) }}</td>
        </tr>
    </table>
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 55%;">Komponen Gaji</th>
                <th style="width: 20%;" class="text-right">Tipe</th>
                <th style="width: 25%;" class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-semibold">Gaji Pokok</td>
                <td class="text-right text-success font-semibold">Penerimaan</td>
                <td class="text-right text-success font-semibold">Rp {{ number_format($petugas->gaji_pokok, 0, ',', '.') }}</td>
            </tr>
            
            @if($kasbons->count() > 0)
                <tr>
                    <td colspan="3" class="font-semibold" style="background-color: #fafafa; padding: 6px 12px; font-size: 11px; text-transform: uppercase; color: #555555;">Potongan Kasbon Bulan Ini</td>
                </tr>
                @foreach($kasbons as $kasbon)
                    <tr>
                        <td style="padding-left: 20px;">• Kasbon (Tanggal {{ \Carbon\Carbon::parse($kasbon->tanggal)->isoFormat('D MMMM Y') }})</td>
                        <td class="text-right text-danger">Potongan</td>
                        <td class="text-right text-danger">- Rp {{ number_format($kasbon->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" class="text-center" style="color: #888888; font-style: italic; padding: 12px;">Tidak ada potongan kasbon periode ini.</td>
                </tr>
            @endif
            <tr class="grand-total-row">
                <td>Total Gaji Bersih (Take Home Pay)</td>
                <td class="text-right">Total</td>
                <td class="text-right">Rp {{ number_format($record->total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    <!-- Signatures -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%;">
                    <div class="signature-box">
                        <p>Jakarta, {{ \Carbon\Carbon::parse($record->tanggal)->isoFormat('D MMMM Y') }}</p>
                        <p class="signature-title">Ketua RW {{ $rw->nomor_rw }}</p>
                        <div class="signature-space"></div>
                        <p class="signature-name">{{ $rw->nama ?? '..........................' }}</p>
                        <p class="signature-title">Rukun Warga {{ $rw->nomor_rw }}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>