<x-filament-panels::page>

    <style>
        /* Light mode default */
        .kelulusan-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .dark .kelulusan-card {
            background: #1f2937;
            border-color: #374151;
        }

        /* Header Card */
        .card-header {
            padding: 12px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .dark .card-header {
            background: #111827;
            border-bottom-color: #374151;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dark .card-title {
            color: #f3f4f6;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            padding: 20px;
        }

        @media (min-width: 768px) {
            .info-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
        }

        .dark .info-label {
            color: #9ca3af;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #111827;
            padding: 6px 10px;
            background: #f9fafb;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        .dark .info-value {
            color: #f3f4f6;
            background: #111827;
            border-color: #374151;
        }

        /* Tabel dengan grid system */
        .table-wrapper {
            overflow-x: auto;
        }

        .kelulusan-thead {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            display: grid;
            padding: 10px 20px;
        }

        .dark .kelulusan-thead {
            background: #111827;
            border-bottom-color: #374151;
        }

        .kelulusan-row {
            display: grid;
            padding: 12px 20px;
            border-bottom: 1px solid #f3f4f6;
            align-items: center;
        }

        .dark .kelulusan-row {
            border-bottom-color: #1f2937;
        }

        .kelulusan-row:hover {
            background: #fef9e3;
        }

        .dark .kelulusan-row:hover {
            background: #1e293b;
        }

        .kelulusan-col-muted {
            font-size: 12px;
            color: #6b7280;
        }

        .dark .kelulusan-col-muted {
            color: #9ca3af;
        }

        .kelulusan-col-text {
            font-size: 13px;
            color: #374151;
        }

        .dark .kelulusan-col-text {
            color: #e5e7eb;
        }

        /* Badge untuk status kelulusan */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-lulus {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .status-tidak-lulus {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .status-belum {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .dark .status-lulus {
            background: #14532d;
            color: #86efac;
            border-color: #15803d;
        }

        .dark .status-tidak-lulus {
            background: #7f1a1a;
            color: #fecaca;
            border-color: #b91c1c;
        }

        .dark .status-belum {
            background: #451a03;
            color: #fcd34d;
            border-color: #92400e;
        }

        /* Catatan box */
        .catatan-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 16px;
            margin: 20px;
            border-radius: 8px;
        }

        .dark .catatan-box {
            background: #1e1b04;
        }

        .catatan-content {
            color: #78350f;
            line-height: 1.5;
            font-size: 13px;
        }

        .dark .catatan-content {
            color: #fde68a;
        }

        /* Footer statistik */
        .kelulusan-footer {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .dark .kelulusan-footer {
            background: #111827;
            border-top-color: #374151;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
        }

        .dark .stat-item {
            color: #9ca3af;
        }

        .stat-bullet {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7280;
        }

        .dark .breadcrumb {
            color: #9ca3af;
        }

        .breadcrumb-active {
            font-weight: 600;
            color: #1f2937;
        }

        .dark .breadcrumb-active {
            color: #f3f4f6;
        }

        /* Tombol Edit */
        .edit-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            background: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
            transition: all 0.2s;
        }

        .edit-btn:hover {
            background: #fde68a;
        }

        .dark .edit-btn {
            background: #451a03;
            color: #fcd34d;
            border-color: #92400e;
        }

        .dark .edit-btn:hover {
            background: #92400e;
        }
    </style>

    <div class="space-y-5">

        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <span>Kelulusan</span>
            <span>›</span>
            <span>Rekap Kelulusan</span>
            <span>›</span>
            <span class="breadcrumb-active">Detail Kelulusan - {{ $santri->nama_lengkap ?? '-' }}</span>
        </div>

        {{-- Edit Button for Admin --}}
        @if($this->isAdmin())
            <div style="display: flex; justify-content: flex-end;">
                <a href="{{ $this->getEditUrl() }}" class="edit-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Edit
                </a>
            </div>
        @endif

        {{-- Informasi Santri --}}
        <div class="kelulusan-card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Identitas Santri
                </div>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nama Lengkap</span>
                    <div class="info-value">{{ $santri->nama_lengkap ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">NIS</span>
                    <div class="info-value font-mono">{{ $santri->nis ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Kelas</span>
                    <div class="info-value">{{ $record->nama_kelas ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Tahun Ajaran</span>
                    <div class="info-value">{{ $tahunAjaran->tahun_ajaran ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Tabel Nilai Akhir --}}
        <div class="kelulusan-card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Nilai Akhir
                </div>
            </div>

            @php
                $totalNilai = 0;
                $jumlahMapel = count($nilaiList ?? []);
                $rataRata = 0;
                
                foreach($nilaiList ?? [] as $nilai) {
                    $totalNilai += $nilai->nilai_akhir ?? 0;
                }
                $rataRata = $jumlahMapel > 0 ? round($totalNilai / $jumlahMapel, 2) : 0;
            @endphp

            <div class="table-wrapper">
                <div class="kelulusan-thead" style="grid-template-columns: 60px 1fr 120px 120px;">
                    <div class="kelulusan-col-muted">No</div>
                    <div class="kelulusan-col-muted">Mata Pelajaran</div>
                    <div class="kelulusan-col-muted" style="text-align: center;">Rata-rata Raport</div>
                    <div class="kelulusan-col-muted" style="text-align: center;">Nilai Akhir</div>
                </div>

                @forelse($nilaiList ?? [] as $index => $nilai)
                    <div class="kelulusan-row" style="grid-template-columns: 60px 1fr 120px 120px;">
                        <div class="kelulusan-col-muted">{{ $index + 1 }}</div>
                        <div class="kelulusan-col-text" style="font-weight: 500;">{{ $nilai->mapel->nama_mapel ?? '-' }}</div>
                        <div style="text-align: center;">
                            <span style="font-weight: 500;">{{ $nilai->rata_rata_raport ?? '-' }}</span>
                        </div>
                        <div style="text-align: center;">
                            <span style="font-weight: 700; font-size: 15px; color: #4f46e5;">{{ $nilai->nilai_akhir ?? '-' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="kelulusan-row" style="justify-content: center; text-align: center; display: flex;">
                        <div class="kelulusan-col-muted" style="text-align: center; width: 100%; padding: 40px;">
                            Belum ada data nilai
                        </div>
                    </div>
                @endforelse
            </div>

            @if($jumlahMapel > 0)
            <div class="kelulusan-footer">
                <div class="stat-item" style="margin-left: auto;">
                    <span>📊 Rata-rata Nilai Akhir: <strong style="font-size: 14px; color: #4f46e5;">{{ $rataRata }}</strong></span>
                </div>
            </div>
            @endif
        </div>

        {{-- Menentukan Kelulusan --}}
        <div class="kelulusan-card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Menentukan Kelulusan
                </div>
            </div>
            <div class="info-grid" style="grid-template-columns: repeat(2, 1fr);">
                <div class="info-item">
                    <span class="info-label">Keputusan</span>
                    <div>
                        @if($kelulusan?->status === 'lulus')
                            <span class="status-badge status-lulus">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                LULUS
                            </span>
                        @elseif($kelulusan?->status === 'tidak_lulus')
                            <span class="status-badge status-tidak-lulus">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                TIDAK LULUS
                            </span>
                        @else
                            <span class="status-badge status-belum">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum Ditentukan
                            </span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Catatan</span>
                    <div class="catatan-box" style="margin: 0;">
                        <div class="catatan-content">
                            {{ $kelulusan?->catatan ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Kembali --}}
        <div style="display: flex; justify-content: flex-end;">
            <a href="{{ \App\Filament\Resources\Kelulusans\KelulusanResource::getUrl('list-santri', ['record' => $record->id, 'tahun_ajaran' => $tahunAjaran->id]) }}" 
               class="edit-btn" style="background: #f3f4f6; color: #4b5563; border-color: #e5e7eb;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Santri
            </a>
        </div>
    </div>

</x-filament-panels::page>