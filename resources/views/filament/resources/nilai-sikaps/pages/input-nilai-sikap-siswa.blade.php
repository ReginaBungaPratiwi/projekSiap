<x-filament-panels::page>

    <style>
        /* Light mode default */
        .sikap-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .dark .sikap-card {
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

        .sikap-thead {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            display: grid;
            padding: 10px 20px;
        }

        .dark .sikap-thead {
            background: #111827;
            border-bottom-color: #374151;
        }

        .sikap-row {
            display: grid;
            padding: 12px 20px;
            border-bottom: 1px solid #f3f4f6;
            align-items: center;
        }

        .dark .sikap-row {
            border-bottom-color: #1f2937;
        }

        .sikap-row:hover {
            background: #fef9e3;
        }

        .dark .sikap-row:hover {
            background: #1e293b;
        }

        .sikap-col-muted {
            font-size: 12px;
            color: #6b7280;
        }

        .dark .sikap-col-muted {
            color: #9ca3af;
        }

        .sikap-col-text {
            font-size: 13px;
            color: #374151;
        }

        .dark .sikap-col-text {
            color: #e5e7eb;
        }

        /* Badge untuk status */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            width: fit-content;
        }

        .status-sudah {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .status-belum {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .dark .status-sudah {
            background: #14532d;
            color: #86efac;
            border-color: #15803d;
        }

        .dark .status-belum {
            background: #7f1a1a;
            color: #fecaca;
            border-color: #b91c1c;
        }

        /* Tombol Aksi */
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-input {
            background: #dbeafe;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .btn-input:hover {
            background: #bfdbfe;
        }

        .btn-edit {
            background: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        .btn-edit:hover {
            background: #fde68a;
        }

        .btn-view {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }

        .btn-view:hover {
            background: #e5e7eb;
        }

        .dark .btn-input {
            background: #1e3a8a;
            color: #93c5fd;
            border-color: #1e40af;
        }

        .dark .btn-edit {
            background: #451a03;
            color: #fcd34d;
            border-color: #92400e;
        }

        .dark .btn-view {
            background: #1f2937;
            color: #9ca3af;
            border-color: #374151;
        }

        /* Action group untuk tombol */
        .action-group {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
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

        /* Search input */
        .search-wrapper {
            position: relative;
        }

        .search-input {
            padding: 6px 12px 6px 32px;
            font-size: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            width: 220px;
            background: white;
        }

        .dark .search-input {
            background: #1f2937;
            border-color: #374151;
            color: #f3f4f6;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            color: #9ca3af;
        }

        /* Footer statistik */
        .sikap-footer {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .dark .sikap-footer {
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

        /* Header gradient */
        .gradient-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            overflow: hidden;
        }

        /* Styling untuk cell NIS dan Nama */
        .nis-cell {
            font-family: monospace;
            padding-right: 16px;
        }
        
        .nama-cell {
            font-weight: 500;
            padding-right: 16px;
        }
    </style>

    <div class="space-y-5">

        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <span>Penilaian Santri</span>
            <span>›</span>
            <span>Nilai Akhlak & Sikap</span>
            <span>›</span>
            <span class="breadcrumb-active">Input Nilai Sikap Siswa</span>
        </div>

        {{-- Header Info dengan Gradient --}}
        <div class="gradient-header">
            <div style="padding: 20px 24px;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div>
                        <h2 style="font-size: 18px; font-weight: 700; color: white; margin-bottom: 8px;">Input Nilai Sikap Siswa</h2>
                        <div style="display: flex; flex-wrap: wrap; gap: 16px; font-size: 13px;">
                            <span style="color: rgba(255,255,255,0.9);">Kelas: <strong style="color: white;">{{ $kelas->nama_kelas ?? '-' }}</strong></span>
                            <span style="color: rgba(255,255,255,0.7);">|</span>
                            <span style="color: rgba(255,255,255,0.9);">Semester: <strong style="color: white;">{{ ucfirst($semester->semester ?? '-') }}</strong></span>
                            <span style="color: rgba(255,255,255,0.7);">|</span>
                            <span style="color: rgba(255,255,255,0.9);">Tahun Ajaran: <strong style="color: white;">{{ $semester->tahunAjaran->tahun_ajaran ?? '-' }}</strong></span>
                        </div>
                    </div>
                    @if(isset($canEdit) && !$canEdit)
                        <span style="display: inline-flex; padding: 4px 12px; background: rgba(255,255,255,0.2); color: white; border-radius: 20px; font-size: 11px;">Mode: Hanya Lihat</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tabel Daftar Santri --}}
        <div class="sikap-card">
            <div class="card-header">
                <div class="card-title" style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Daftar Siswa
                    </div>
                    <div class="search-wrapper">
                        <input type="text" 
                               wire:model.live="search" 
                               placeholder="Cari nama atau NIS..." 
                               class="search-input">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            @php
                $totalSantri = count($santriData ?? []);
                $sudahInput = collect($santriData ?? [])->where('has_nilai', true)->count();
            @endphp

            <div class="table-wrapper">
                <div class="sikap-thead" style="grid-template-columns: 50px 120px 1fr 100px 1fr;">
                    <div class="sikap-col-muted">No</div>
                    <div class="sikap-col-muted">NIS</div>
                    <div class="sikap-col-muted">Nama Santri</div>
                    <div class="sikap-col-muted" style="text-align: center;">Status</div>
                    <div class="sikap-col-muted" style="text-align: center;">Aksi</div>
                </div>

                @forelse($santriData ?? [] as $index => $data)
                    <div class="sikap-row" style="grid-template-columns: 50px 120px 1fr 100px 1fr;">
                        <div class="sikap-col-muted">{{ $index + 1 }}</div>
                        <div class="sikap-col-text nis-cell">{{ $data['nis'] }}</div>
                        <div class="sikap-col-text nama-cell">{{ $data['nama'] }}</div>
                        <div style="display: flex; justify-content: center;">
                            @if($data['has_nilai'])
                                <span class="status-badge status-sudah">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Sudah Input
                                </span>
                            @else
                                <span class="status-badge status-belum">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Belum Input
                                </span>
                            @endif
                        </div>
                        <div>
                            <div class="action-group">
                                {{-- Input / Edit Action --}}
                                @php
                                    $actionUrl = \App\Filament\Resources\NilaiSikaps\NilaiSikapResource::getUrl(
                                        $data['has_nilai'] ? 'edit' : 'create',
                                        [$kelas->id, $data['santri_id'], $semester->id]
                                    );
                                @endphp
                                <a href="{{ $actionUrl }}" class="btn-action {{ $data['has_nilai'] ? 'btn-edit' : 'btn-input' }}">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    {{ $data['has_nilai'] ? 'Edit' : 'Input' }}
                                </a>

                                {{-- View Action --}}
                                @if($data['has_nilai'])
                                    @php
                                        $viewUrl = \App\Filament\Resources\NilaiSikaps\NilaiSikapResource::getUrl('view', [$kelas->id, $data['santri_id'], $semester->id]);
                                    @endphp
                                    <a href="{{ $viewUrl }}" class="btn-action btn-view">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="sikap-row" style="justify-content: center; text-align: center; display: flex;">
                        <div class="sikap-col-muted" style="text-align: center; width: 100%; padding: 40px;">
                            Tidak ada data santri
                        </div>
                    </div>
                @endforelse
            </div>

            @if(count($santriData ?? []) > 0)
            <div class="sikap-footer">
                <div class="stat-item">
                    <span class="stat-bullet" style="background: #10b981;"></span>
                    <span>Total Santri: {{ $totalSantri }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-bullet" style="background: #3b82f6;"></span>
                    <span>Sudah Input: {{ $sudahInput }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-bullet" style="background: #f59e0b;"></span>
                    <span>Belum Input: {{ $totalSantri - $sudahInput }}</span>
                </div>
                <div class="stat-item" style="margin-left: auto;">
                    <span>📊 Progress: {{ $totalSantri > 0 ? round(($sudahInput / $totalSantri) * 100) : 0 }}%</span>
                </div>
            </div>
            @endif
        </div>

        {{-- Tombol Kembali --}}
        <div style="display: flex; justify-content: flex-end;">
            <a href="{{ \App\Filament\Resources\NilaiSikaps\NilaiSikapResource::getUrl() }}" 
               class="btn-action btn-view" style="padding: 8px 20px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Kelas
            </a>
        </div>
    </div>

</x-filament-panels::page> 