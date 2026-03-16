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
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }

        .header-section h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header-section h2 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header-section h3 {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }

        .header-section p {
            font-size: 10px;
            margin-top: 5px;
        }

        .info-section {
            margin-bottom: 15px;
        }

        .info-row {
            margin-bottom: 5px;
        }

        .info-label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }

        .rapor-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .rapor-table th,
        .rapor-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        .rapor-table th {
            background-color: #f3f4f6;
            font-weight: bold;
        }

        .text-center {
            text-align: center !important;
        }

        .passed {
            color: #059669;
            font-weight: bold;
        }

        .failed {
            color: #dc2626;
            font-weight: bold;
        }

        .summary-table {
            width: 50%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #000;
            padding: 6px 8px;
        }

        .summary-table th {
            background-color: #f3f4f6;
            font-weight: bold;
        }

        .signature-section {
            margin-top: 30px;
        }

        .signature-container {
            width: 100%;
        }

        .signature-box {
            display: inline-block;
            width: 48%;
            text-align: center;
            vertical-align: top;
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
    <div class="header-section">
        <h1>DINAS PENDIDIKAN KOTA CIMAHI</h1>
        <h2>PONPES AL MUSYAHADAH</h2>
        <p>Jl. Raya Cilember gang pondok pesantren al musyahadah No.27, RT.02/RW.06, Cigugur Tengah, Kec. Cimahi Tengah, Kota Cimahi, Jawa Barat 40522</p>
        <h3>LAPORAN HASIL BELAJAR PESERTA DIDIK</h3>
        <p>SEMESTER {{ strtoupper($semester->semester) }} TAHUN PELAJARAN {{ $semester->tahunAjaran->tahun_ajaran }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Nama Peserta Didik</span>
            <span>: {{ $santri->nama_lengkap }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nomor Induk/NISN</span>
            <span>: {{ $santri->nis }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nama Sekolah</span>
            <span>: PONPES AL MUSYAHADAH</span>
        </div>
        <div class="info-row">
            <span class="info-label">Alamat Sekolah</span>
            <span>: Jl. Raya Cilember gang pondok pesantren al musyahadah No.27, RT.02/RW.06, Cigugur Tengah, Kec. Cimahi Tengah, Kota Cimahi, Jawa Barat 40522</span>
        </div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Kelas</span>
            <span>: {{ $kelas->nama_kelas }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Semester</span>
            <span>: {{ ucfirst($semester->semester) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tahun Pelajaran</span>
            <span>: {{ $semester->tahunAjaran->tahun_ajaran }}</span>
        </div>
    </div>

    <table class="rapor-table">
        <thead>
            <tr>
                <th class="text-center" rowspan="2" style="width: 30px;">NO</th>
                <th rowspan="2">MATA PELAJARAN</th>
                <th class="text-center" rowspan="2" style="width: 50px;">KKM</th>
                <th class="text-center" colspan="2">NILAI</th>
                <th class="text-center" rowspan="2" style="width: 130px;">DESKRIPSI KEMAJUAN BELAJAR</th>
            </tr>
            <tr>
                <th class="text-center" style="width: 50px;">ANGKA</th>
                <th class="text-center" style="width: 130px;">HURUF</th>
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
                    <td class="text-center">{{ $nilai->kemajuan_belajar }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada nilai</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Akhlak dan Kepribadian --}}
    <table class="rapor-table">
        <thead>
            <tr>
                <th colspan="3" class="text-center">AKHLAK DAN KEPRIBADIAN</th>
            </tr>
            <tr>
                <th class="text-center" style="width: 30px;">NO</th>
                <th>ASPEK YANG DINILAI</th>
                <th class="text-center" style="width: 80px;">NILAI</th>
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

    <table class="summary-table">
        <thead>
            <tr>
                <th colspan="2" class="text-center">KETIDAKHADIRAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $ketidakhadiran = $santri->getKetidakhadiranSemester($kelas->id, $semester);
            @endphp
            <tr>
                <td style="width: 60%;">SAKIT</td>
                <td class="text-center">{{ $ketidakhadiran['sakit'] }}</td>
            </tr>
            <tr>
                <td>IZIN</td>
                <td class="text-center">{{ $ketidakhadiran['izin'] }}</td>
            </tr>
            <tr>
                <td>TANPA KETERANGAN</td>
                <td class="text-center">{{ $ketidakhadiran['alpha'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p>Mengetahui,<br>Orang Tua/Wali</p>
                    <div class="signature-space"></div>
                    <p>_______________</p>
                </td>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p>Kembali,<br>Wali Kelas</p>
                    <div class="signature-space"></div>
                    <p><strong>{{ $kelas->waliKelas?->nama ?? '_______________' }}</strong></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-section">
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>
</body>
</html>
