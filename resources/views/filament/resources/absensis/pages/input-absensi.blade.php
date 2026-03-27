<x-filament-panels::page>

<style>
    /* Light mode default */
    .abs-card          { border:1px solid #e5e7eb; border-radius:12px; }
    .abs-stat-hadir    { border-radius:12px; padding:16px; text-align:center; background:#f0fdf4; border:1px solid #bbf7d0; }
    .abs-stat-hadir .abs-num  { color:#15803d; }
    .abs-stat-hadir .abs-lbl  { color:#16a34a; }
    .abs-stat-izin     { border-radius:12px; padding:16px; text-align:center; background:#fffbeb; border:1px solid #fde68a; }
    .abs-stat-izin .abs-num   { color:#b45309; }
    .abs-stat-izin .abs-lbl   { color:#d97706; }
    .abs-stat-sakit    { border-radius:12px; padding:16px; text-align:center; background:#eff6ff; border:1px solid #bfdbfe; }
    .abs-stat-sakit .abs-num  { color:#1d4ed8; }
    .abs-stat-sakit .abs-lbl  { color:#2563eb; }
    .abs-stat-alpha    { border-radius:12px; padding:16px; text-align:center; background:#fef2f2; border:1px solid #fecaca; }
    .abs-stat-alpha .abs-num  { color:#b91c1c; }
    .abs-stat-alpha .abs-lbl  { color:#dc2626; }

    .abs-num { font-size:24px; font-weight:700; }
    .abs-lbl { font-size:11px; margin-top:4px; font-weight:600; letter-spacing:.05em; text-transform:uppercase; }

    .abs-thead  { background:#f9fafb; border-bottom:1px solid #e5e7eb; }
    .abs-row    { border-bottom:1px solid #f3f4f6; }
    .abs-footer { background:#f9fafb; border-top:1px solid #e5e7eb; }
    .abs-col-muted { font-size:13px; color:#9ca3af; }
    .abs-col-text  { font-size:13px; color:#6b7280; }

    .abs-badge-hadir { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
    .abs-badge-izin  { background:#fffbeb; color:#b45309; border:1px solid #fde68a; }
    .abs-badge-sakit { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
    .abs-badge-alpha { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }
    .abs-badge-none  { background:#f9fafb; color:#6b7280; border:1px solid #e5e7eb; }

    .abs-radio-hadir { color:#15803d; }
    .abs-radio-izin  { color:#b45309; }
    .abs-radio-sakit { color:#1d4ed8; }
    .abs-radio-alpha { color:#b91c1c; }
    .abs-radio-muted { color:#6b7280; }

    .abs-ket-input {
        width:100%; border:none; border-bottom:1px solid #e5e7eb;
        background:transparent; font-size:13px; color:inherit;
        padding:4px 0; outline:none;
    }
    .abs-ket-input::placeholder { color:#d1d5db; font-style:italic; }

    /* Dark mode */
    .dark .abs-card         { border-color:#374151; }
    .dark .abs-stat-hadir   { background:#052e16; border-color:#166534; }
    .dark .abs-stat-hadir .abs-num { color:#86efac; }
    .dark .abs-stat-hadir .abs-lbl { color:#4ade80; }
    .dark .abs-stat-izin    { background:#451a03; border-color:#92400e; }
    .dark .abs-stat-izin .abs-num  { color:#fcd34d; }
    .dark .abs-stat-izin .abs-lbl  { color:#fbbf24; }
    .dark .abs-stat-sakit   { background:#172554; border-color:#1e40af; }
    .dark .abs-stat-sakit .abs-num { color:#93c5fd; }
    .dark .abs-stat-sakit .abs-lbl { color:#60a5fa; }
    .dark .abs-stat-alpha   { background:#450a0a; border-color:#991b1b; }
    .dark .abs-stat-alpha .abs-num { color:#fca5a5; }
    .dark .abs-stat-alpha .abs-lbl { color:#f87171; }

    .dark .abs-thead  { background:#1f2937; border-color:#374151; }
    .dark .abs-row    { border-color:#1f2937; }
    .dark .abs-footer { background:#1f2937; border-color:#374151; }
    .dark .abs-col-muted { color:#6b7280; }
    .dark .abs-col-text  { color:#9ca3af; }

    .dark .abs-radio-hadir { color:#86efac; }
    .dark .abs-radio-izin  { color:#fcd34d; }
    .dark .abs-radio-sakit { color:#93c5fd; }
    .dark .abs-radio-alpha { color:#fca5a5; }
    .dark .abs-radio-muted { color:#6b7280; }

    .dark .abs-ket-input { border-bottom-color:#374151; }
    .dark .abs-ket-input::placeholder { color:#4b5563; }
</style>

<div class="space-y-6">
    <!-- Breadcrumb -->
    <div class="text-sm text-gray-600 dark:text-gray-400">
        <span>Penilaian Santri</span>
        <span class="mx-2">›</span>
        <span>Absensi</span>
        <span class="mx-2">›</span>
        <span class="font-semibold text-gray-900 dark:text-white">Input Absensi: {{ $kelas->nama_kelas }}</span>
    </div>

    {{-- Info Kelas & Tanggal --}}
    <div class="abs-card" style="padding:20px; margin-bottom:24px;">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:16px;">
            <div>
                <div style="font-size:18px; font-weight:700;">{{ $kelas->nama_kelas }}</div>
                <div class="abs-col-muted" style="margin-top:2px;">Wali Kelas: {{ $kelas->waliKelas->nama ?? '-' }}</div>
            </div>
            
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="font-size:14px; font-weight:500;">{{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</div>
                <input type="date"
                       wire:model.live="tanggal"
                       style="border:1px solid #d1d5db; border-radius:8px; padding:6px 10px; font-size:13px; background:transparent; color:inherit;">
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:24px;">
        <div class="abs-stat-hadir">
            <div class="abs-num">{{ $statistik['hadir'] ?? 0 }}</div>
            <div class="abs-lbl">Hadir</div>
        </div>
        <div class="abs-stat-izin">
            <div class="abs-num">{{ $statistik['izin'] ?? 0 }}</div>
            <div class="abs-lbl">Izin</div>
        </div>
        <div class="abs-stat-sakit">
            <div class="abs-num">{{ $statistik['sakit'] ?? 0 }}</div>
            <div class="abs-lbl">Sakit</div>
        </div>
        <div class="abs-stat-alpha">
            <div class="abs-num">{{ $statistik['alpha'] ?? 0 }}</div>
            <div class="abs-lbl">Alpha</div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="abs-card" style="overflow:hidden; margin-bottom:24px;">

        <div style="padding:14px 16px; border-bottom:1px solid #e5e7eb;">
            <span style="font-size:14px; font-weight:600;">Daftar Absensi Santri</span>
        </div>

        @if(count($absensiData) > 0)

            {{-- Header --}}
            <div class="abs-thead" style="display:grid; grid-template-columns:50px 1fr 280px 1fr; padding:10px 16px;">
                <div class="abs-col-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">No</div>
                <div class="abs-col-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">Nama Santri</div>
                <div class="abs-col-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">Status</div>
                <div class="abs-col-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">Keterangan</div>
            </div>

            {{-- Rows --}}
            @foreach($absensiData as $index => $data)
                @php
                    $radioHadirClass = $data['status'] === 'hadir' ? 'abs-radio-hadir' : 'abs-radio-muted';
                    $radioIzinClass = $data['status'] === 'izin' ? 'abs-radio-izin' : 'abs-radio-muted';
                    $radioSakitClass = $data['status'] === 'sakit' ? 'abs-radio-sakit' : 'abs-radio-muted';
                    $radioAlphaClass = $data['status'] === 'alpha' ? 'abs-radio-alpha' : 'abs-radio-muted';
                @endphp
                <div class="abs-row" style="display:grid; grid-template-columns:50px 1fr 280px 1fr; padding:13px 16px; align-items:center;">
                    <div class="abs-col-muted">{{ $loop->iteration }}</div>
                    <div>
                        <div style="font-size:14px; font-weight:500;">{{ $data['nama'] }}</div>
                        <div class="abs-col-muted" style="margin-top:2px;">{{ $data['nis'] }}</div>
                    </div>
                    <div style="display:flex; gap:16px; align-items:center; flex-wrap:wrap;">
                        <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                            <input type="radio"
                                   wire:model.live="absensiData.{{ $index }}.status"
                                   value="hadir"
                                   style="accent-color:#16a34a; width:15px; height:15px; cursor:pointer;">
                            <span class="{{ $radioHadirClass }}" style="font-size:13px; font-weight:500;">Hadir</span>
                        </label>
                        <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                            <input type="radio"
                                   wire:model.live="absensiData.{{ $index }}.status"
                                   value="izin"
                                   style="accent-color:#d97706; width:15px; height:15px; cursor:pointer;">
                            <span class="{{ $radioIzinClass }}" style="font-size:13px; font-weight:500;">Izin</span>
                        </label>
                        <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                            <input type="radio"
                                   wire:model.live="absensiData.{{ $index }}.status"
                                   value="sakit"
                                   style="accent-color:#2563eb; width:15px; height:15px; cursor:pointer;">
                            <span class="{{ $radioSakitClass }}" style="font-size:13px; font-weight:500;">Sakit</span>
                        </label>
                        <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                            <input type="radio"
                                   wire:model.live="absensiData.{{ $index }}.status"
                                   value="alpha"
                                   style="accent-color:#dc2626; width:15px; height:15px; cursor:pointer;">
                            <span class="{{ $radioAlphaClass }}" style="font-size:13px; font-weight:500;">Alpha</span>
                        </label>
                    </div>
                    <div>
                        <input type="text"
                               wire:model="absensiData.{{ $index }}.keterangan"
                               placeholder="Keterangan..."
                               class="abs-ket-input"
                               value="{{ $data['keterangan'] }}">
                    </div>
                </div>
            @endforeach

            {{-- Footer --}}
            <div class="abs-footer" style="padding:12px 16px; display:flex; align-items:center; flex-wrap:wrap; gap:16px;">
                <span style="display:flex; align-items:center; gap:5px; font-size:13px;">
                    <span style="width:8px; height:8px; border-radius:50%; background:#16a34a; display:inline-block;"></span>
                    Hadir: {{ $statistik['hadir'] ?? 0 }}
                </span>
                <span style="display:flex; align-items:center; gap:5px; font-size:13px;">
                    <span style="width:8px; height:8px; border-radius:50%; background:#d97706; display:inline-block;"></span>
                    Izin: {{ $statistik['izin'] ?? 0 }}
                </span>
                <span style="display:flex; align-items:center; gap:5px; font-size:13px;">
                    <span style="width:8px; height:8px; border-radius:50%; background:#2563eb; display:inline-block;"></span>
                    Sakit: {{ $statistik['sakit'] ?? 0 }}
                </span>
                <span style="display:flex; align-items:center; gap:5px; font-size:13px;">
                    <span style="width:8px; height:8px; border-radius:50%; background:#dc2626; display:inline-block;"></span>
                    Alpha: {{ $statistik['alpha'] ?? 0 }}
                </span>
                <span style="font-size:13px; font-weight:600; margin-left:auto;">
                    Total Santri: {{ count($absensiData) }}
                </span>
            </div>

        @else
            <div class="abs-col-muted" style="padding:48px; text-align:center; font-size:14px;">
                Tidak ada data santri untuk kelas ini.
            </div>
        @endif

    </div>

    {{-- Tombol Simpan dan Kembali --}}
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <x-filament::button
            color="gray"
            tag="a"
            href="{{ \App\Filament\Resources\Absensis\AbsensiResource::getUrl('index') }}"
            icon="heroicon-o-arrow-left"
        >
            Kembali ke Daftar Kelas
        </x-filament::button>
        
        <x-filament::button 
            wire:click="save" 
            color="success" 
            icon="heroicon-o-check"
        >
            Simpan Absensi
        </x-filament::button>
    </div>

</div>

</x-filament-panels::page>