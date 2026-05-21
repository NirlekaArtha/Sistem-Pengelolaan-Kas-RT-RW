<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kas Tahunan RW {{ $rw->nomor_rw }} - Tahun {{ $tahun }}</title>
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
            margin-bottom: 20px;
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

        .font-semibold {
            font-weight: bold;
        }

        .highlight-row {
            background-color: #fafafa;
        }

        .highlight-row td {
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

        .text-success {
            color: #16a34a;
        }

        .text-danger {
            color: #dc2626;
        }

        .text-info {
            color: #0284c7;
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
        <h2>Laporan Kas Tahunan Rukun Warga</h2>
        <p>Sistem Pengelolaan Kas RT / RW Digital</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Wilayah</td>
            <td class="info-colon">:</td>
            <td>RW {{ $rw->nomor_rw }}</td>
            <td class="info-label">Ketua RW</td>
            <td class="info-colon">:</td>
            <td>{{ $rw->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Tahun Laporan</td>
            <td class="info-colon">:</td>
            <td colspan="4">{{ $tahun }}</td>
        </tr>
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th>Periode</th>
                <th class="text-right">Jumlah Masuk</th>
                <th class="text-right">Jumlah Keluar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($record->periode . '-01')->isoFormat('MMMM Y') }}</td>
                    <td class="text-right text-success">+ Rp {{ number_format($record->total_pendapatan, 0, ',', '.') }}</td>
                    <td class="text-right text-danger">- Rp {{ number_format($record->total_pengeluaran, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #555555;">Tidak ada data kas untuk tahun ini.</td>
                </tr>
            @endforelse

            @if($records->isNotEmpty())
                <tr class="highlight-row" style="border-top: 2px solid #dddddd;">
                    <td class="font-semibold">Total</td>
                    <td class="text-right font-semibold text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    <td class="text-right font-semibold text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                </tr>
                <tr class="grand-total-row">
                    <td class="font-semibold" colspan="2">Pendapatan Bersih (Net Income)</td>
                    <td class="text-right font-semibold">
                        Rp {{ number_format($pendapatanBersih, 0, ',', '.') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Signatures -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%;">
                    <div class="signature-box">
                        <p>Jakarta, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
                        <p class="signature-title">Ketua RW {{ $rw->nomor_rw }}</p>
                        <div class="signature-space"></div>
                        <p class="signature-name">{{ $rw->nama ?? '..........................' }}</p>
                        <p class="signature-title">NIP. ..........................</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
