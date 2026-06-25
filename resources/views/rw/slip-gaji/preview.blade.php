<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Slip Gaji - {{ $petugas->nama }} - Periode {{ \Carbon\Carbon::parse($record->tanggal)->isoFormat('MMMM Y') }}</title>

    <style>
        :root {
            --bg-color: #f4f4f4;
            --paper-bg: #ffffff;
            --text-color: #111111;
            --border-color: #222222;
            --muted-color: #333333;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 28px 16px;
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .action-bar {
            width: 100%;
            max-width: 794px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1px solid #dddddd;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
        }

        .action-bar h1 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
        }

        .btn-export {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: var(--primary);
            color: #ffffff;
            padding: 9px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: 0.2s ease;
            white-space: nowrap;
        }

        .btn-export:hover {
            background-color: var(--primary-hover);
        }

        .paper {
            width: 794px;
            min-height: 1123px;
            background: var(--paper-bg);
            box-shadow: 0 12px 34px rgba(0, 0, 0, 0.12);
            padding: 34px 42px 30px 42px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .content {
            flex: 1 0 auto;
        }

        .kop {
            text-align: center;
            font-weight: 700;
            font-size: 16px;
            line-height: 1.45;
            margin: 0 0 26px 0;
            text-transform: uppercase;
        }

        .top-line {
            border-top: 2px solid #000000;
            margin: 0 0 14px 0;
        }

        .title-section {
            text-align: center;
            margin-bottom: 42px;
        }

        .title-section h1 {
            margin: 0 0 6px 0;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .title-section p {
            margin: 0;
            font-size: 13px;
            line-height: 1.35;
        }

        .employee-section {
            margin-bottom: 26px;
        }

        .employee-section h2 {
            margin: 0 0 14px 0;
            font-size: 14px;
            font-weight: 700;
        }

        .employee-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .employee-table td {
            border: none;
            padding: 4px 0;
            font-size: 13px;
            line-height: 1.35;
            vertical-align: top;
        }

        .employee-label {
            width: 80px;
        }

        .employee-colon {
            width: 18px;
            text-align: center;
        }

        .employee-value {
            word-break: normal;
            overflow-wrap: anywhere;
        }

        .separator-line {
            border-top: 2px solid #000000;
            margin: 28px 0 12px 0;
        }

        .salary-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 32px;
        }

        .salary-table th,
        .salary-table td {
            border: 1px solid var(--border-color);
            padding: 10px 10px;
            font-size: 13px;
            line-height: 1.35;
            vertical-align: middle;
        }

        .salary-table th {
            height: 56px;
            text-align: center;
            font-weight: 700;
            font-size: 13px;
        }

        .salary-table .col-desc {
            width: 72%;
        }

        .salary-table .col-amount {
            width: 28%;
            text-align: center;
            white-space: nowrap;
        }

        .salary-table .normal-row td {
            height: 48px;
        }

        .potongan-title {
            margin-bottom: 6px;
        }

        .potongan-list {
            padding-left: 46px;
        }

        .potongan-item {
            margin: 2px 0;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .amount-lines {
            text-align: center;
            line-height: 1.55;
            white-space: nowrap;
        }

        .total-row td {
            border-top: 1.5px solid #000000;
            height: 46px;
            font-weight: 400;
        }

        .signature-area {
            margin-top: 28px;
            width: 100%;
        }

        .date-box {
            width: 36%;
            margin-left: auto;
            text-align: left;
            font-size: 13px;
            line-height: 1.6;
        }

        .signature-table {
            width: 62%;
            margin-left: auto;
            margin-top: 42px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .signature-table td {
            border: none;
            padding: 0;
            text-align: center;
            font-size: 13px;
            line-height: 1.35;
            width: 50%;
        }

        .signature-space td,
        .signature-space {
            height: 92px;
        }

        .signature-name {
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .confidential-note {
            flex-shrink: 0;
            margin-top: auto;
            padding-top: 28px;
            font-size: 12.5px;
            line-height: 1.4;
            color: #111111;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
                gap: 0;
            }

            .action-bar {
                display: none;
            }

            .paper {
                width: auto;
                min-height: 100vh;
                box-shadow: none;
                padding: 1.4cm 1.35cm 1.1cm 1.35cm;
                overflow: visible;
            }
        }

        @media screen and (max-width: 840px) {
            .paper,
            .action-bar {
                width: 100%;
            }

            .paper {
                min-height: auto;
                padding: 28px 24px;
            }
        }
    </style>
</head>

<body>
    <div class="action-bar">
        <h1>Preview Slip Gaji</h1>

        <a href="{{ route('rw.slip-gaji.download', $record) }}" class="btn-export">
            Export ke PDF
        </a>
    </div>

    @php
        $petugas = $petugas ?? $record->petugas;
        $potonganKasbons = $kasbons ?? ($record->kasbons ?? collect());

        $tanggalBayar = \Carbon\Carbon::parse($record->tanggal);
        $periodeAwal = $tanggalBayar->copy()->subMonthNoOverflow()->day(26);
        $periodeAkhir = $tanggalBayar->copy()->day(25);

        $tunjanganTransportasi = $petugas->tunjangan_transportasi
            ?? $record->tunjangan_transportasi
            ?? 50000;

        $totalGajiBersih = $record->total
            ?? (($petugas->gaji_pokok ?? 0) + $tunjanganTransportasi - $potonganKasbons->sum('jumlah'));

        $ketuaRw04 = config('rw.ketua_rw_04', 'Iin Hartanto');
        $ketuaRw05 = config('rw.ketua_rw_05', 'M. Rochmat Hidayat');

        $jabatanPetugas = '-';

        if (isset($petugas->tugas)) {
            if (is_object($petugas->tugas) && method_exists($petugas->tugas, 'getLabel')) {
                $jabatanPetugas = $petugas->tugas->getLabel();
            } else {
                $jabatanPetugas = (string) $petugas->tugas;
            }
        }
    @endphp

    <div class="paper">
        <div class="content">
            <div class="kop">
                <div>PERUMAHAN CITRA PRIMA SERPONG 1</div>
                <div>MUNCUL, SETU, TANGERANG SELATAN</div>
            </div>

            <div class="top-line"></div>

            <div class="title-section">
                <h1>SLIP GAJI KARYAWAN</h1>
                <p>
                    Periode Pembayaran :
                    {{ $periodeAwal->isoFormat('D MMMM Y') }}
                    –
                    {{ $periodeAkhir->isoFormat('D MMMM Y') }}
                </p>
            </div>

            <div class="employee-section">
                <h2>Data Karyawan</h2>

                <table class="employee-table">
                    <tr>
                        <td class="employee-label">Nama</td>
                        <td class="employee-colon">:</td>
                        <td class="employee-value">{{ $petugas->nama }}</td>
                    </tr>
                    <tr>
                        <td class="employee-label">Jabatan</td>
                        <td class="employee-colon">:</td>
                        <td class="employee-value">{{ $jabatanPetugas }}</td>
                    </tr>
                </table>
            </div>

            <div class="separator-line"></div>

            <table class="salary-table">
                <thead>
                    <tr>
                        <th class="col-desc">RINCIAN GAJI</th>
                        <th class="col-amount">JUMLAH (Rp)</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="normal-row">
                        <td>Gaji Pokok</td>
                        <td class="col-amount">
                            {{ number_format($petugas->gaji_pokok ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    <tr class="normal-row">
                        <td>Tunjangan Transportasi</td>
                        <td class="col-amount">
                            {{ number_format($tunjanganTransportasi, 0, ',', '.') }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="potongan-title">Potongan</div>

                            <div class="potongan-list">
                                @if($potonganKasbons->count() > 0)
                                    @foreach($potonganKasbons as $kasbon)
                                        <div class="potongan-item">
                                            - &nbsp; {{ \Carbon\Carbon::parse($kasbon->tanggal)->isoFormat('dddd, D MMMM Y') }} : Kasbon
                                        </div>
                                    @endforeach
                                @else
                                    <div class="potongan-item">
                                        - &nbsp; Tidak ada potongan kasbon
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td class="amount-lines">
                            @if($potonganKasbons->count() > 0)
                                @foreach($potonganKasbons as $kasbon)
                                    <div>
                                        -{{ number_format($kasbon->jumlah, 0, ',', '.') }}
                                    </div>
                                @endforeach
                            @else
                                <div>0</div>
                            @endif
                        </td>
                    </tr>

                    <tr class="total-row">
                        <td>Total Gaji Bersih</td>
                        <td class="col-amount">
                            {{ number_format($totalGajiBersih, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="signature-area">
                <div class="date-box">
                    <div>Muncul, {{ $periodeAkhir->isoFormat('D MMMM Y') }}</div>
                    <br>
                    <div>Mengetahui,</div>
                </div>

                <table class="signature-table">
                    <tr>
                        <td>Ketua RW 04</td>
                        <td>Ketua RW 05</td>
                    </tr>
                    <tr>
                        <td class="signature-space"></td>
                        <td class="signature-space"></td>
                    </tr>
                    <tr>
                        <td class="signature-name">{{ $ketuaRw04 }}</td>
                        <td class="signature-name">{{ $ketuaRw05 }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="confidential-note">
            *Slip gaji ini adalah dokumen rahasia yang hanya untuk karyawan yang bersangkutan,
            dilarang menyebarkan dan mendistribusikan tanpa ijin pengurus CPS 1
        </div>
    </div>
</body>
</html>
