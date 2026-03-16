<x-filament-panels::page>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #rapor-content, #rapor-content * {
                visibility: visible;
            }
            #rapor-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
            .fi-topbar, .fi-sidebar, .fi-header, .fi-page-actions {
                display: none !important;
            }
        }

        .rapor-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .rapor-table th,
        .rapor-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .rapor-table th {
            background-color: #f3f4f6;
            font-weight: bold;
        }

        .text-center {
            text-align: center !important;
        }

        .header-section {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            width: 180px;
            font-weight: bold;
        }

        @media print {
            .rapor-table th {
                background-color: #f3f4f6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>

    <div class="rapor-container bg-white p-8 max-w-5xl mx-auto" id="rapor-content">

        {{-- School Header --}}
        <div class="header-section">
            <h1 class="text-2xl font-bold">DINAS PENDIDIKAN KOTA CIMAHI</h1>
            <h2 class="text-xl font-bold mt-2">PONPES AL MUSYAHADAH</h2>
            <p class="text-sm mt-2">Jl. Raya Cilember gang pondok pesantren al musyahadah No.27, RT.02/RW.06, Cigugur Tengah, Kec. Cimahi Tengah, Kota Cimahi, Jawa Barat 40522</p>
            <h3 class="text-xl font-bold mt-4">LAPORAN HASIL BELAJAR PESERTA DIDIK</h3>
            <p class="text-sm mt-2">SEMESTER {{ strtoupper($this->semester->semester) }} TAHUN PELAJARAN {{ $this->semester->tahunAjaran->tahun_ajaran }}</p>
        </div>

        {{-- Student Information --}}
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Nama Peserta Didik</span>
                <span>: {{ $this->santri->nama_lengkap }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nomor Induk/NISN</span>
                <span>: {{ $this->santri->nis }}</span>
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
                <span>: {{ $this->record->nama_kelas }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Semester</span>
                <span>: {{ ucfirst($this->semester->semester) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tahun Pelajaran</span>
                <span>: {{ $this->semester->tahunAjaran->tahun_ajaran }}</span>
            </div>
        </div>

        {{-- Grades Table --}}
        <div class="grades-section mt-6">
            <table class="rapor-table">
                <thead>
                    <tr>
                        <th class="text-center" rowspan="2" style="width: 40px;">NO</th>
                        <th rowspan="2">MATA PELAJARAN</th>
                        <th class="text-center" rowspan="2" style="width: 60px;">KKM</th>
                        <th class="text-center" colspan="2">NILAI</th>
                        <th class="text-center" rowspan="2" style="width: 180px;">DESKRIPSI KEMAJUAN BELAJAR</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="width: 70px;">ANGKA</th>
                        <th class="text-center" style="width: 180px;">HURUF</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->nilais as $index => $nilai)
                        @php
                            $kkm = $this->kkms->get($nilai->mapel_id);
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
        </div>

        {{-- Akhlak dan Kepribadian --}}
        <div class="akhlak-section mt-6">
            <table class="rapor-table">
                <thead>
                    <tr>
                        <th colspan="3" class="text-center">AKHLAK DAN KEPRIBADIAN</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="width: 40px;">NO</th>
                        <th>ASPEK YANG DINILAI</th>
                        <th class="text-center" style="width: 100px;">NILAI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>Disiplin</td>
                        <td class="text-center">{{ $this->nilaiSikap?->disiplin ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>Tanggung Jawab</td>
                        <td class="text-center">{{ $this->nilaiSikap?->tanggung_jawab ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td>Kejujuran</td>
                        <td class="text-center">{{ $this->nilaiSikap?->kejujuran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td>Sopan Santun / Adab</td>
                        <td class="text-center">{{ $this->nilaiSikap?->sopan_santun ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td>Kepedulian / Kerja Sama</td>
                        <td class="text-center">{{ $this->nilaiSikap?->kepedulian ?? '-' }}</td>
                    </tr>
                    @if($this->nilaiSikap?->catatan)
                    <tr>
                        <td colspan="3">
                            <strong>Catatan:</strong> {{ $this->nilaiSikap->catatan }}
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <p style="font-size: 10px; margin-top: 5px; color: #6b7280;">
                Keterangan: A = Sangat Baik, B = Baik, C = Cukup, D = Kurang
            </p>
        </div>

        {{-- Attendance Summary --}}
        <div class="summary-section mt-6">
            <table class="rapor-table" style="width: 50%;">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center">KETIDAKHADIRAN</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ketidakhadiran = $this->santri->getKetidakhadiranSemester($this->record->id, $this->semester);
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
        </div>

        {{-- Signature Section --}}
        <div class="signature-section mt-8">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                <div class="text-center">
                    <p style="margin-bottom: 80px;">Mengetahui,<br>Orang Tua/Wali</p>
                    <p style="border-top: 1px solid #000; display: inline-block; padding-top: 5px; min-width: 200px;">
                        _______________
                    </p>
                </div>
                <div class="text-center">
                    <p style="margin-bottom: 80px;">Kembali,<br>Wali Kelas</p>
                    <p style="border-top: 1px solid #000; display: inline-block; padding-top: 5px; min-width: 200px;">
                        <strong>{{ $this->record->waliKelas?->nama ?? '_______________' }}</strong>
                    </p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer-section mt-8 text-center text-sm" style="color: #6b7280;">
            <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
        </div>
    </div>
</x-filament-panels::page>
