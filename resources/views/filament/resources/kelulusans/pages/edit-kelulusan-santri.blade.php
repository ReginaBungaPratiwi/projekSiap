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

        /* Radio button */
        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 8px;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .radio-custom {
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .radio-custom.checked {
            border-color: #4f46e5;
        }

        .radio-custom.checked::after {
            content: "";
            width: 10px;
            height: 10px;
            background: #4f46e5;
            border-radius: 50%;
            display: block;
        }

        .dark .radio-custom {
            border-color: #4b5563;
        }

        .dark .radio-custom.checked {
            border-color: #818cf8;
        }

        .dark .radio-custom.checked::after {
            background: #818cf8;
        }

        /* Textarea */
        .textarea-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
            resize: vertical;
            background: white;
        }

        .dark .textarea-input {
            background: #1f2937;
            border-color: #374151;
            color: #f3f4f6;
        }

        .textarea-input:focus {
            outline: none;
            border-color: #4f46e5;
            ring: 2px solid rgba(79, 70, 229, 0.2);
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

        /* Tombol */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 20px;
            background: #4f46e5;
            color: white;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #f3f4f6;
            color: #4b5563;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .dark .btn-secondary {
            background: #1f2937;
            color: #9ca3af;
            border-color: #374151;
        }

        .dark .btn-secondary:hover {
            background: #374151;
        }
    </style>

    <div class="space-y-5">

        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <span>Kelulusan</span>
            <span>›</span>
            <span>Rekap Kelulusan</span>
            <span>›</span>
            <span class="breadcrumb-active">Edit Kelulusan - {{ $santri->nama_lengkap ?? '-' }}</span>
        </div>

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

        {{-- Form Menentukan Kelulusan --}}
        <div class="kelulusan-card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Menentukan Kelulusan
                </div>
            </div>
            <div style="padding: 20px;">
                <form wire:submit="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Status Radio --}}
                        <div>
                            <label class="info-label" style="display: block; margin-bottom: 12px;">Keputusan <span style="color: #ef4444;">*</span></label>
                            <div class="radio-group">
                                <label class="radio-item">
                                    <input type="radio" wire:model="status" value="lulus" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">LULUS</span>
                                </label>
                                <label class="radio-item">
                                    <input type="radio" wire:model="status" value="tidak_lulus" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">TIDAK LULUS</span>
                                </label>
                            </div>
                            @error('status')
                                <p class="text-xs text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Catatan Textarea --}}
                        <div>
                            <label class="info-label" style="display: block; margin-bottom: 8px;">Catatan <span style="color: #ef4444;">*</span></label>
                            <textarea
                                wire:model="catatan"
                                rows="4"
                                placeholder="Catatan Wali Kelas (WAJIB)"
                                class="textarea-input"
                            ></textarea>
                            @error('catatan')
                                <p class="text-xs text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ \App\Filament\Resources\Kelulusans\KelulusanResource::getUrl('view-santri', ['record' => $record->id, 'santri' => $santri->id, 'tahun_ajaran' => $tahunAjaran->id]) }}" 
                           class="btn-secondary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Keputusan Final
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-filament-panels::page>