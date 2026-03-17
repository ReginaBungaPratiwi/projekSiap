<x-filament-panels::page>

<style>
    /* Light mode */
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

    <!-- Header Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Input Absensi: {{ $kelas->nama_kelas }}</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Wali Kelas: {{ $kelas->waliKelas->nama ?? '-' }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</span>
                    <input 
                        type="date" 
                        wire:model.live="tanggal" 
                        value="{{ $tanggal }}"
                        class="rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm w-48"
                    >
                </div>
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

        {{-- Header --}}
        <div class="abs-thead" style="display:grid; grid-template-columns:40px 1fr 260px 1fr; padding:10px 16px;">
            @foreach(['#','Nama Santri','Status','Keterangan'] as $h)
                <div class="abs-col-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">{{ $h }}</div>
            @endforeach
        </div>

        {{-- Rows --}}
        @foreach ($absensiData as $index => $data)
            <div class="abs-row" style="display:grid; grid-template-columns:40px 1fr 260px 1fr; padding:14px 16px; align-items:center;">

                {{-- No --}}
                <div class="abs-col-muted">{{ $loop->iteration }}</div>

                {{-- Nama --}}
                <div>
                    <div style="font-size:14px; font-weight:500;">{{ $data['nama'] }}</div>
                    @if (!empty($data['nis']) && $data['nis'] !== '-')
                        <div class="abs-col-muted" style="margin-top:2px;">{{ $data['nis'] }}</div>
                    @endif
                </div>

                {{-- Radio Status --}}
                <div style="display:flex; gap:16px; align-items:center; flex-wrap:wrap;">

                    <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                        <input type="radio"
                               wire:model.live="absensiData.{{ $index }}.status"
                               value="hadir"
                               style="accent-color:#16a34a; width:15px; height:15px; cursor:pointer;">
                        <span class="{{ $data['status'] === 'hadir' ? 'abs-radio-hadir' : 'abs-radio-muted' }}" style="font-size:13px; font-weight:500;">Hadir</span>
                    </label>

                    <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                        <input type="radio"
                               wire:model.live="absensiData.{{ $index }}.status"
                               value="izin"
                               style="accent-color:#d97706; width:15px; height:15px; cursor:pointer;">
                        <span class="{{ $data['status'] === 'izin' ? 'abs-radio-izin' : 'abs-radio-muted' }}" style="font-size:13px; font-weight:500;">Izin</span>
                    </label>

                    <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                        <input type="radio"
                               wire:model.live="absensiData.{{ $index }}.status"
                               value="sakit"
                               style="accent-color:#2563eb; width:15px; height:15px; cursor:pointer;">
                        <span class="{{ $data['status'] === 'sakit' ? 'abs-radio-sakit' : 'abs-radio-muted' }}" style="font-size:13px; font-weight:500;">Sakit</span>
                    </label>

                    <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                        <input type="radio"
                               wire:model.live="absensiData.{{ $index }}.status"
                               value="alpha"
                               style="accent-color:#dc2626; width:15px; height:15px; cursor:pointer;">
                        <span class="{{ $data['status'] === 'alpha' ? 'abs-radio-alpha' : 'abs-radio-muted' }}" style="font-size:13px; font-weight:500;">Alpha</span>
                    </label>

                </div>

                {{-- Keterangan --}}
                <div>
                    <input type="text"
                           wire:model="absensiData.{{ $index }}.keterangan"
                           placeholder="Keterangan..."
                           class="abs-ket-input"
                           value="{{ $data['keterangan'] }}">
                </div>

            </div>
        @endforeach

    </div>

    {{-- Footer --}}
    <div style="display:flex; align-items:center; justify-content:space-between;">
        <span class="abs-col-muted">
            {{ count($absensiData) }} dari {{ count($absensiData) }} santri tercatat
        </span>
        
        <x-filament::button 
            wire:click="save" 
            color="success" 
            icon="heroicon-o-check"
            class="w-auto"
        >
            Simpan Absensi
        </x-filament::button>
    </div>

</div>

</x-filament-panels::page>