<x-filament-panels::page>
    <style>
        /* ===== PRINT ===== */
        @media print {
            body * {
                visibility: hidden;
            }
            #rapor-content, #rapor-content * {
                visibility: visible;
                color: #000 !important;
            }
            #rapor-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px 40px;
                background: #fff !important;
            }
            #rapor-content .header-section {
                border-bottom-color: #000 !important;
            }
            #rapor-content .rapor-table th,
            #rapor-content .rapor-table td {
                border-color: #000 !important;
            }
            #rapor-content .rapor-table th {
                background-color: #f3f4f6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            #rapor-content .signature-name {
                border-bottom-color: #000 !important;
            }
            #rapor-content .rapor-muted {
                color: #6b7280 !important;
            }
            .no-print, .fi-topbar, .fi-sidebar, .fi-header, .fi-page-actions {
                display: none !important;
            }
        }

        /* ===== BASE (Light Mode) ===== */
        .rapor-container {
            color: #1a1a1a;
        }

        .rapor-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
            font-size: 14px;
        }

        .rapor-table th,
        .rapor-table td {
            border: 1px solid #d1d5db;
            padding: 6px 10px;
            text-align: left;
            vertical-align: middle;
        }

        .rapor-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center !important;
        }

        /* ===== HEADER ===== */
        .header-section {
            text-align: center;
            padding-bottom: 16px;
            margin-bottom: 24px;
            border-bottom: 3px double #1a1a1a;
        }

        .header-section .header-title {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0;
        }

        .header-section .header-school {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 4px 0;
        }

        .header-section .header-address {
            font-size: 11px;
            margin: 6px auto 0;
            max-width: 520px;
            line-height: 1.4;
        }

        .header-section .header-report-title {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 16px;
            text-transform: uppercase;
        }

        .header-section .header-semester {
            font-size: 13px;
            font-weight: 600;
            margin-top: 2px;
            text-transform: uppercase;
        }

        /* ===== INFO SECTION ===== */
        .info-section {
            margin-bottom: 16px;
            font-size: 14px;
        }

        .info-table {
            border-collapse: collapse;
            width: 100%;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
            border: none;
        }

        .info-table .info-label {
            width: 170px;
            font-weight: bold;
            white-space: nowrap;
        }

        .info-table .info-colon {
            width: 12px;
            text-align: center;
        }

        /* ===== SIGNATURE ===== */
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-box .signature-title {
            font-size: 14px;
            margin-bottom: 80px;
            line-height: 1.5;
        }

        .signature-box .signature-name {
            display: inline-block;
            min-width: 200px;
            border-bottom: 1px solid #1a1a1a;
            padding-bottom: 4px;
            font-weight: bold;
            font-size: 14px;
        }

        /* ===== MUTED TEXT ===== */
        .rapor-muted {
            color: #6b7280;
        }

        /* ===== DARK MODE ===== */
        .dark .rapor-container {
            color: #e5e7eb;
        }

        .dark .header-section {
            border-bottom-color: #9ca3af;
        }

        .dark .rapor-table th,
        .dark .rapor-table td {
            border-color: #4b5563;
        }

        .dark .rapor-table th {
            background-color: #374151;
        }

        .dark .signature-box .signature-name {
            border-bottom-color: #9ca3af;
        }

        .dark .rapor-muted {
            color: #9ca3af;
        }
    </style>

    <div class="rapor-container p-8 max-w-4xl mx-auto bg-white dark:bg-gray-900 rounded-xl shadow-sm" id="rapor-content">

        {{-- ===== HEADER ===== --}}
        <div class="header-section">
            <p class="header-title">DINAS PENDIDIKAN KOTA CIMAHI</p>
            <p class="header-school">PONPES AL MUSYAHADAH</p>
            <p class="header-address">
                Jl. Raya Cilember gang pondok pesantren al musyahadah No.27, RT.02/RW.06,<br>
                Cigugur Tengah, Kec. Cimahi Tengah, Kota Cimahi, Jawa Barat 40522
            </p>
            <p class="header-report-title">LAPORAN HASIL BELAJAR PESERTA DIDIK</p>
            <p class="header-semester">SEMESTER {{ strtoupper($this->semester->semester) }} TAHUN PELAJARAN {{ $this->semester->tahunAjaran->tahun_ajaran }}</p>
        </div>

        {{-- ===== STUDENT INFO ===== --}}
        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td class="info-label">Nama Peserta Didik</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">{{ $this->santri->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td class="info-label">Nomor Induk/NISN</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">{{ $this->santri->nis }}</td>
                </tr>
                <tr>
                    <td class="info-label">Nama Sekolah</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">PONPES AL MUSYAHADAH</td>
                </tr>
                <tr>
                    <td class="info-label">Alamat Sekolah</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">Jl. Raya Cilember gang pondok pesantren al musyahadah No.27, RT.02/RW.06, Cigugur Tengah, Kec. Cimahi Tengah, Kota Cimahi, Jawa Barat 40522</td>
                </tr>
            </table>
        </div>

        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td class="info-label">Kelas</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">{{ $this->record->nama_kelas }}</td>
                </tr>
                <tr>
                    <td class="info-label">Semester</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">{{ ucfirst($this->semester->semester) }}</td>
                </tr>
                <tr>
                    <td class="info-label">Tahun Pelajaran</td>
                    <td class="info-colon">:</td>
                    <td class="info-value">{{ $this->semester->tahunAjaran->tahun_ajaran }}</td>
                </tr>
            </table>
        </div>

        {{-- ===== GRADES TABLE ===== --}}
        <div style="margin-top: 24px;">
            <table class="rapor-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 40px;">NO</th>
                        <th rowspan="2">MATA PELAJARAN</th>
                        <th rowspan="2" style="width: 55px;">KKM</th>
                        <th colspan="2">NILAI</th>
                        <th rowspan="2" style="width: 200px;">DESKRIPSI KEMAJUAN BELAJAR</th>
                    </tr>
                    <tr>
                        <th style="width: 60px;">ANGKA</th>
                        <th style="width: 160px;">HURUF</th>
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
                            <td style="font-size: 12px;">{{ $nilai->kemajuan_belajar }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 16px; font-style: italic;">Belum ada nilai</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== AKHLAK DAN KEPRIBADIAN ===== --}}
        <div style="margin-top: 24px;">
            <table class="rapor-table">
                <thead>
                    <tr>
                        <th colspan="3">AKHLAK DAN KEPRIBADIAN</th>
                    </tr>
                    <tr>
                        <th style="width: 40px;">NO</th>
                        <th>ASPEK YANG DINILAI</th>
                        <th style="width: 100px;">NILAI</th>
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
                        <td colspan="3" style="font-size: 13px;">
                            <strong>Catatan:</strong> {{ $this->nilaiSikap->catatan }}
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <p class="rapor-muted" style="font-size: 10px; margin-top: 4px;">
                Keterangan: A = Sangat Baik, B = Baik, C = Cukup, D = Kurang
            </p>
        </div>

        {{-- ===== KETIDAKHADIRAN ===== --}}
        <div style="margin-top: 24px;">
            @php
                $ketidakhadiran = $this->santri->getKetidakhadiranSemester($this->record->id, $this->semester);
            @endphp
            <table class="rapor-table" style="width: 50%;">
                <thead>
                    <tr>
                        <th colspan="2">KETIDAKHADIRAN</th>
                    </tr>
                </thead>
                <tbody>
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
        </div>

        {{-- ===== TANDA TANGAN ===== --}}
        <div class="signature-section">
            <div class="signature-grid">
                <div class="signature-box">
                    <p class="signature-title">Mengetahui,<br>Orang Tua / Wali</p>
                    <span class="signature-name">_______________</span>
                </div>
                <div class="signature-box">
                    <p class="signature-title">Cimahi, {{ now()->translatedFormat('d F Y') }}<br>Wali Kelas</p>
                    <span class="signature-name">{{ $this->record->waliKelas?->nama ?? '_______________' }}</span>
                </div>
            </div>
        </div>

        {{-- ===== FOOTER ===== --}}
        <div class="rapor-muted" style="margin-top: 32px; text-align: center; font-size: 11px;">
            <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
        </div>
    </div>
</x-filament-panels::page>
