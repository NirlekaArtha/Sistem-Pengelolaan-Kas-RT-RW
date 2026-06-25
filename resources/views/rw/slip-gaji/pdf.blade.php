<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $petugas->nama }} - Periode {{ \Carbon\Carbon::parse($record->tanggal)->isoFormat('MMMM Y') }}</title>

    <style>
        @page {
            margin: 1.35cm 1.35cm 1.25cm 1.35cm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            color: #111111;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.35;
        }

        .document {
            width: 100%;
            position: relative;
            padding-bottom: 58px;
        }

        .kop {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            line-height: 1.35;
            margin: 0 0 22px 0;
            text-transform: uppercase;
        }

        .top-line {
            border-top: 2px solid #000000;
            margin: 0 0 12px 0;
        }

        .title-section {
            text-align: center;
            margin-bottom: 34px;
        }

        .title-section h1 {
            margin: 0 0 5px 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .title-section p {
            margin: 0;
            font-size: 12.5px;
            line-height: 1.35;
        }

        .employee-section {
            margin-bottom: 22px;
        }

        .employee-section h2 {
            margin: 0 0 12px 0;
            font-size: 16px;
            font-weight: bold;
        }

        .employee-table {
            border-collapse: collapse;
            width: auto;
            table-layout: auto;
        }

        .employee-table td {
            border: none;
            padding: 3px 0;
            font-size: 13px;
            line-height: 1.35;
            vertical-align: top;
        }

        .employee-label {
            width: 70px;
            padding-right: 4px;
        }

        .employee-colon {
            width: 12px;
            text-align: center;
            padding-right: 10px;
        }

        .employee-value {
            width: 260px;
            word-wrap: break-word;
        }

        .separator-line {
            border-top: 2px solid #000000;
            margin: 24px 0 10px 0;
        }

        .salary-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 28px;
        }

        .salary-table th,
        .salary-table td {
            border: 1px solid #222222;
            padding: 8px 8px;
            font-size: 12.5px;
            line-height: 1.35;
            vertical-align: middle;
        }

        .salary-table th {
            height: 44px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
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
            height: 40px;
        }

        .potongan-title {
            margin-bottom: 4px;
        }

        .potongan-list {
            padding-left: 34px;
        }

        .potongan-item {
            margin: 2px 0;
            word-wrap: break-word;
        }

        .amount-lines {
            text-align: center;
            line-height: 1.5;
            white-space: nowrap;
        }

        .total-row td {
            border-top: 1.5px solid #000000;
            height: 40px;
            font-weight: normal;
        }

        .signature-area {
            width: 100%;
            margin-top: 24px;
        }

        .date-box {
            width: 38%;
            margin-left: auto;
            text-align: left;
            font-size: 12px;
            line-height: 1.55;
        }

        .signature-table {
            width: 62%;
            margin-left: auto;
            margin-top: 38px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .signature-table td {
            border: none;
            padding: 0;
            width: 50%;
            text-align: center;
            font-size: 12px;
            line-height: 1.35;
            vertical-align: top;
        }

        .signature-space {
            height: 84px;
        }

        .signature-name {
            word-wrap: break-word;
        }

        .confidential-note {
            position: fixed;
            left: 0cm;
            right: 0cm;
            bottom: 0.55cm;
            font-size: 11.5px;
            line-height: 1.35;
            color: #111111;
        }
    </style>
</head>

<body>
    @php
        $petugas = $petugas ?? $record->petugas;
        $potonganKasbons = $kasbons ?? ($record->kasbons ?? collect());

        $tanggalBayar = \Carbon\Carbon::parse($record->tanggal);
        $periodeAwal = $tanggalBayar->copy()->subMonthNoOverflow()->day(26);
        $periodeAkhir = $tanggalBayar->copy()->day(25);

        $ketuaRw04 = config('rw.ketua_rw_04', 'Iin Hartanto');
        $ketuaRw05 = config('rw.ketua_rw_05', 'M. Rochmat Hidayat');

        $jabatanPetugas = '-';
        $tugasPetugas = $petugas->tugas ?? null;

        if ($tugasPetugas) {
            if (is_object($tugasPetugas) && method_exists($tugasPetugas, 'getLabel')) {
                $jabatanPetugas = $tugasPetugas->getLabel();
            } else {
                $jabatanPetugas = (string) $tugasPetugas;
            }
        }

        $nilaiTugasPetugas = is_object($tugasPetugas) && isset($tugasPetugas->value)
            ? $tugasPetugas->value
            : $tugasPetugas;

        $isSatpam = ($tugasPetugas instanceof \App\Enums\PetugasTugas && $tugasPetugas === \App\Enums\PetugasTugas::SATPAM)
            || strtolower((string) ($nilaiTugasPetugas ?? '')) === 'satpam';

        $tunjanganTransportasi = $isSatpam ? 50000 : 0;

        $totalDasarGaji = $record->total
            ?? (($petugas->gaji_pokok ?? 0) - $potonganKasbons->sum('jumlah'));

        $totalGajiBersih = $totalDasarGaji + $tunjanganTransportasi;
    @endphp

    <div class="document">
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

                    @if($isSatpam)
                        <tr class="normal-row">
                            <td>Tunjangan Transportasi</td>
                            <td class="col-amount">
                                {{ number_format($tunjanganTransportasi, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif

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
</body>
</html>
