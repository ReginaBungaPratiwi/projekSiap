<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor - {{ $santri->nama_lengkap }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            padding: 20px;
        }

        .header-section {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
        }

        .header-title {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .header-school {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }

        .header-address {
            font-size: 10px;
            margin-top: 4px;
            line-height: 1.4;
        }

        .header-report-title {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 12px;
        }

        .header-semester {
            font-size: 11px;
            font-weight: 600;
            margin-top: 2px;
        }

        .info-section {
            margin-bottom: 12px;
        }

        .info-table {
            border-collapse: collapse;
            width: 100%;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
            border: none;
        }

        .info-label {
            width: 140px;
            font-weight: bold;
            white-space: nowrap;
        }

        .info-colon {
            width: 10px;
            text-align: center;
        }

        .rapor-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
        }

        .rapor-table th,
        .rapor-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            text-align: left;
        }

        .rapor-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center !important;
        }

        .summary-table {
            width: 50%;
            border-collapse: collapse;
            margin: 12px 0;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #000;
            padding: 5px 6px;
        }

        .summary-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
        }

        .signature-section {
            margin-top: 30px;
        }

        .signature-space {
            height: 60px;
        }

        .footer-section {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }

        @page {
            margin: 20mm 15mm;
        }
    </style>
</head>
<body>
    {{-- ===== HEADER ===== --}}
    <div class="header-section">
        <p class="header-title">DINAS PENDIDIKAN KOTA CIMAHI</p>
        <p class="header-school">PONPES AL MUSYAHADAH</p>
        <p class="header-address">
            Jl. Raya Cilember gang pondok pesantren al musyahadah No.27, RT.02/RW.06,<br>
            Cigugur Tengah, Kec. Cimahi Tengah, Kota Cimahi, Jawa Barat 40522
        </p>
        <p class="header-report-title">LAPORAN HASIL BELAJAR PESERTA DIDIK</p>
        <p class="header-semester">SEMESTER {{ strtoupper($semester->semester) }} TAHUN PELAJARAN {{ $semester->tahunAjaran->tahun_ajaran }}</p>
    </div>

    {{-- ===== STUDENT INFO ===== --}}
    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="info-label">Nama Peserta Didik</td>
                <td class="info-colon">:</td>
                <td>{{ $santri->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="info-label">Nomor Induk/NISN</td>
                <td class="info-colon">:</td>
                <td>{{ $santri->nis }}</td>
            </tr>
            <tr>
                <td class="info-label">Nama Sekolah</td>
                <td class="info-colon">:</td>
                <td>PONPES AL MUSYAHADAH</td>
            </tr>
            <tr>
                <td class="info-label">Alamat Sekolah</td>
                <td class="info-colon">:</td>
                <td>Jl. Raya Cilember gang pondok pesantren al musyahadah No.27, RT.02/RW.06, Cigugur Tengah, Kec. Cimahi Tengah, Kota Cimahi, Jawa Barat 40522</td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="info-label">Kelas</td>
                <td class="info-colon">:</td>
                <td>{{ $kelas->nama_kelas }}</td>
            </tr>
            <tr>
                <td class="info-label">Semester</td>
                <td class="info-colon">:</td>
                <td>{{ ucfirst($semester->semester) }}</td>
            </tr>
            <tr>
                <td class="info-label">Tahun Pelajaran</td>
                <td class="info-colon">:</td>
                <td>{{ $semester->tahunAjaran->tahun_ajaran }}</td>
            </tr>
        </table>
    </div>

    {{-- ===== GRADES TABLE ===== --}}
    <table class="rapor-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">NO</th>
                <th rowspan="2">MATA PELAJARAN</th>
                <th rowspan="2" style="width: 40px;">KKM</th>
                <th colspan="2">NILAI</th>
                <th rowspan="2" style="width: 130px;">DESKRIPSI KEMAJUAN BELAJAR</th>
            </tr>
            <tr>
                <th style="width: 45px;">ANGKA</th>
                <th style="width: 120px;">HURUF</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nilais as $index => $nilai)
                @php
                    $kkm = $kkms->get($nilai->mapel_id);
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ strtoupper($nilai->mapel->nama_mapel) }}</td>
                    <td class="text-center">{{ $kkm?->nilai_kkm ?? '-' }}</td>
                    <td class="text-center">{{ number_format($nilai->nilai_akhir, 0) }}</td>
                    <td class="text-center">{{ $nilai->nilai_terbilang }}</td>
                    <td style="font-size: 10px;">{{ $nilai->kemajuan_belajar }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 12px; font-style: italic;">Belum ada nilai</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ===== AKHLAK DAN KEPRIBADIAN ===== --}}
    <table class="rapor-table">
        <thead>
            <tr>
                <th colspan="3">AKHLAK DAN KEPRIBADIAN</th>
            </tr>
            <tr>
                <th style="width: 30px;">NO</th>
                <th>ASPEK YANG DINILAI</th>
                <th style="width: 80px;">NILAI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>Disiplin</td>
                <td class="text-center">{{ $nilaiSikap?->disiplin ?? '-' }}</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>Tanggung Jawab</td>
                <td class="text-center">{{ $nilaiSikap?->tanggung_jawab ?? '-' }}</td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td>Kejujuran</td>
                <td class="text-center">{{ $nilaiSikap?->kejujuran ?? '-' }}</td>
            </tr>
            <tr>
                <td class="text-center">4</td>
                <td>Sopan Santun / Adab</td>
                <td class="text-center">{{ $nilaiSikap?->sopan_santun ?? '-' }}</td>
            </tr>
            <tr>
                <td class="text-center">5</td>
                <td>Kepedulian / Kerja Sama</td>
                <td class="text-center">{{ $nilaiSikap?->kepedulian ?? '-' }}</td>
            </tr>
            @if($nilaiSikap?->catatan)
            <tr>
                <td colspan="3">
                    <strong>Catatan:</strong> {{ $nilaiSikap->catatan }}
                </td>
            </tr>
            @endif
        </tbody>
    </table>
    <p style="font-size: 9px; margin-top: 3px; color: #6b7280;">
        Keterangan: A = Sangat Baik, B = Baik, C = Cukup, D = Kurang
    </p>

    {{-- ===== KETIDAKHADIRAN ===== --}}
    <table class="summary-table">
        <thead>
            <tr>
                <th colspan="2">KETIDAKHADIRAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $ketidakhadiran = $santri->getKetidakhadiranSemester($kelas->id, $semester);
            @endphp
            <tr>
                <td style="width: 60%;">Sakit</td>
                <td class="text-center">{{ $ketidakhadiran['sakit'] }} hari</td>
            </tr>
            <tr>
                <td>Izin</td>
                <td class="text-center">{{ $ketidakhadiran['izin'] }} hari</td>
            </tr>
            <tr>
                <td>Tanpa Keterangan</td>
                <td class="text-center">{{ $ketidakhadiran['alpha'] }} hari</td>
            </tr>
        </tbody>
    </table>

    {{-- ===== TANDA TANGAN ===== --}}
    <div class="signature-section">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p>Mengetahui,<br>Orang Tua / Wali</p>
                    <div class="signature-space"></div>
                    <p style="border-bottom: 1px solid #000; display: inline-block; min-width: 160px; padding-bottom: 4px;">_______________</p>
                </td>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p>Cimahi, {{ now()->translatedFormat('d F Y') }}<br>Wali Kelas</p>
                    <div class="signature-space"></div>
                    <p style="border-bottom: 1px solid #000; display: inline-block; min-width: 160px; padding-bottom: 4px;"><strong>{{ $kelas->waliKelas?->nama ?? '_______________' }}</strong></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-section">
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>
</body>
</html>
