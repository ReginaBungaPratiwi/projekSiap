<x-filament-panels::page>

    <style>
        /* Light mode default */
        .rekap-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .dark .rekap-card {
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

        /* Tabel wrapper */
        .table-wrapper {
            overflow-x: auto;
            padding: 0;
        }

        /* Footer statistik */
        .rekap-footer {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .dark .rekap-footer {
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
    </style>

    <div class="space-y-5">

        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <span>Rekap Nilai</span>
            <span>›</span>
            <span class="breadcrumb-active">Rekap Nilai - {{ $this->record->nama_kelas }}</span>
        </div>

        {{-- Informasi Kelas --}}
        <div class="rekap-card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Informasi Kelas
                </div>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Kelas</span>
                    <div class="info-value">{{ $this->record->nama_kelas }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Semester</span>
                    <div class="info-value">{{ ucfirst($this->semester->semester) }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Tahun Ajaran</span>
                    <div class="info-value">{{ $this->semester->tahunAjaran->tahun_ajaran }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Wali Kelas</span>
                    <div class="info-value">{{ $this->record->waliKelas?->nama ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Tabel Rekap Nilai Siswa --}}
        <div class="rekap-card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Daftar Nilai Siswa
                </div>
            </div>

            <div class="table-wrapper">
                {{ $this->table }}
            </div>
        </div>

        {{-- Tombol Kembali
        <div style="display: flex; justify-content: flex-end;">
            <x-filament::button
                color="gray"
                tag="a"
                :href="\App\Filament\Resources\RekapNilais\RekapNilaiResource::getUrl('index')"
                icon="heroicon-o-arrow-left"
            >
                Kembali ke Daftar Kelas
            </x-filament::button>
        </div>
    </div> --}}

</x-filament-panels::page>