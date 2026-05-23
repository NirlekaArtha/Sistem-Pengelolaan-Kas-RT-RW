<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Laporan Kas Bulanan RT {{ $rt->nomor_rt }} - Periode {{ $record->periode }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --border-color: #e2e8f0;
            --success: #16a34a;
            --danger: #dc2626;
            --info: #0284c7;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Top Action Bar */
        .action-bar {
            width: 100%;
            max-width: 800px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            padding: 16px 24px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            box-sizing: border-box;
        }

        .action-bar h1 {
            font-size: 1.1rem;
            margin: 0;
            color: var(--text-primary);
            font-weight: 600;
        }

        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--primary);
            color: #fff;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            border: none;
            cursor: pointer;
        }

        .btn-export:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
        }

        /* Paper Sheet Layout */
        .paper {
            width: 100%;
            max-width: 800px;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
            padding: 50px;
            box-sizing: border-box;
            position: relative;
        }

        .header {
            text-align: center;
            border-bottom: 3px double var(--border-color);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-primary);
        }

        .header p {
            margin: 6px 0 0 0;
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Table Design */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background-color: #f1f5f9;
            color: var(--text-secondary);
            font-weight: 600;
            text-align: left;
            padding: 14px 18px;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border-color);
        }

        td {
            padding: 14px 18px;
            font-size: 0.95rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        tr:hover td {
            background-color: #f8fafc;
        }

        .text-right {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .font-semibold {
            font-weight: 600;
        }

        .highlight-row {
            background-color: #faf5ff;
        }

        .highlight-row td {
            font-weight: 600;
        }

        .grand-total-row {
            background-color: #eff6ff;
            border-top: 2px solid #3b82f6;
            border-bottom: 2px solid #3b82f6;
        }

        .grand-total-row td {
            font-weight: 700;
            color: var(--primary);
        }

        .text-success {
            color: var(--success) !important;
        }

        .text-danger {
            color: var(--danger) !important;
        }

        .text-info {
            color: var(--info) !important;
        }

        /* Signature block */
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            text-align: center;
            width: 250px;
        }

        .signature-space {
            height: 80px;
        }

        .signature-name {
            font-weight: 700;
            text-decoration: underline;
        }

        .signature-title {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }
            .action-bar {
                display: none;
            }
            .paper {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Action Bar -->
    <div class="action-bar">
        <h1>Preview Laporan Kas Bulanan</h1>
        <a href="{{ route('rt.kas-bulanan.download', $record) }}" class="btn-export">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Export ke PDF
        </a>
    </div>

    <!-- Paper Sheet -->
    <div class="paper">
        <div class="header">
            <h2>Laporan Kas Bulanan Rukun Tetangga</h2>
            <p>Sistem Pengelolaan Kas RT / RW Digital</p>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Wilayah</span>
                <span class="info-value">RT {{ $rt->nomor_rt }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Nama Ketua RT</span>
                <span class="info-value">{{ $rt->nama ?? '-' }}</span>
            </div>
            <div class="info-item" style="grid-column: span 2;">
                <span class="info-label">Periode Laporan</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($record->periode . '-01')->isoFormat('MMMM Y') }}</span>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Komponen Laporan Keuangan</th>
                    <th class="text-right">Jumlah / Nominal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-semibold">Saldo Awal Periode</td>
                    <td class="text-right font-semibold">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">• Total Pendapatan Kas Harian (RT)</td>
                    <td class="text-right text-success">+ Rp {{ number_format($totalPendapatanKasHarian, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">• Total Pendapatan Iuran Warga</td>
                    <td class="text-right text-success">+ Rp {{ number_format($totalPendapatanIuranWarga, 0, ',', '.') }}</td>
                </tr>
                <tr class="highlight-row">
                    <td style="padding-left: 20px;">Total Semua Pemasukan</td>
                    <td class="text-right text-success">Rp {{ number_format($totalSemuaPemasukan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">• Total Pengeluaran Kas Harian (RT)</td>
                    <td class="text-right text-danger">- Rp {{ number_format($totalPengeluaranKasHarian, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding-left: 30px;">• Total Pengeluaran Setoran ke RW</td>
                    <td class="text-right text-danger">- Rp {{ number_format($totalPengeluaranSetoranRW, 0, ',', '.') }}</td>
                </tr>
                <tr class="highlight-row">
                    <td style="padding-left: 20px;">Total Semua Pengeluaran</td>
                    <td class="text-right text-danger">Rp {{ number_format($totalSemuaPengeluaran, 0, ',', '.') }}</td>
                </tr>
                <tr class="highlight-row" style="background-color: #fafdfb;">
                    <td class="font-semibold text-info">Total Bersih Pendapatan (Net Income)</td>
                    <td class="text-right font-semibold text-info">Rp {{ number_format($totalBersihPendapatan, 0, ',', '.') }}</td>
                </tr>
                <tr class="grand-total-row">
                    <td>Saldo Akhir Periode</td>
                    <td class="text-right">Rp {{ number_format($saldoAkhirPeriode, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Jakarta, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
                <p class="signature-title">Ketua RT {{ $rt->nomor_rt }}</p>
                <div class="signature-space"></div>
                <p class="signature-name">{{ $rt->nama ?? '..........................' }}</p>
                <p class="signature-title">NIP. ..........................</p>
            </div>
        </div>
    </div>

</body>
</html>
