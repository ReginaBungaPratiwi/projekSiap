<x-filament-panels::page>

<style>
    /* Light mode default */
    .abs-card          { border:1px solid #e5e7eb; border-radius:12px; }
    .abs-stat-total    { border:1px solid #e5e7eb; border-radius:12px; padding:16px; text-align:center; }
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
    .abs-stat-belum    { border-radius:12px; padding:16px; text-align:center; background:#faf5ff; border:1px solid #e9d5ff; }
    .abs-stat-belum .abs-num  { color:#7c3aed; }
    .abs-stat-belum .abs-lbl  { color:#8b5cf6; }

    .abs-num { font-size:24px; font-weight:700; }
    .abs-lbl { font-size:11px; margin-top:4px; font-weight:600; letter-spacing:.05em; text-transform:uppercase; }

    .abs-thead { background:#f9fafb; border-bottom:1px solid #e5e7eb; }
    .abs-row   { border-bottom:1px solid #f3f4f6; }
    .abs-footer{ background:#f9fafb; border-top:1px solid #e5e7eb; }
    .abs-col-muted { font-size:13px; color:#9ca3af; }
    .abs-col-text  { font-size:13px; color:#6b7280; }

    .abs-badge-hadir { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
    .abs-badge-izin  { background:#fffbeb; color:#b45309; border:1px solid #fde68a; }
    .abs-badge-sakit { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
    .abs-badge-alpha { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }
    .abs-badge-none  { background:#f9fafb; color:#6b7280; border:1px solid #e5e7eb; }

    /* Dark mode */
    .dark .abs-card         { border-color:#374151; }
    .dark .abs-stat-total   { border-color:#374151; }
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
    .dark .abs-stat-belum   { background:#2e1065; border-color:#6d28d9; }
    .dark .abs-stat-belum .abs-num { color:#c4b5fd; }
    .dark .abs-stat-belum .abs-lbl { color:#a78bfa; }

    .dark .abs-thead  { background:#1f2937; border-color:#374151; }
    .dark .abs-row    { border-color:#1f2937; }
    .dark .abs-footer { background:#1f2937; border-color:#374151; }
    .dark .abs-col-muted { color:#6b7280; }
    .dark .abs-col-text  { color:#9ca3af; }

    .dark .abs-badge-hadir { background:#052e16; color:#86efac; border-color:#166534; }
    .dark .abs-badge-izin  { background:#451a03; color:#fcd34d; border-color:#92400e; }
    .dark .abs-badge-sakit { background:#172554; color:#93c5fd; border-color:#1e40af; }
    .dark .abs-badge-alpha { background:#450a0a; color:#fca5a5; border-color:#991b1b; }
    .dark .abs-badge-none  { background:#1f2937; color:#9ca3af; border-color:#374151; }
</style>

<div class="space-y-6">
    <!-- Breadcrumb -->
    <div class="text-sm text-gray-600 dark:text-gray-400">
        <span>Penilaian Santri</span>
        <span class="mx-2">›</span>
        <span>Absensi</span>
        <span class="mx-2">›</span>
        <span class="font-semibold text-gray-900 dark:text-white">Lihat Absensi: {{ $kelas->nama_kelas }}</span>
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

    @php $stat = $this->getStatistik(); @endphp

    {{-- Statistik --}}
    <div style="display:grid; grid-template-columns:repeat(6,1fr); gap:12px; margin-bottom:24px;">
        <div class="abs-stat-total">
            <div class="abs-num">{{ $stat['total'] }}</div>
            <div class="abs-lbl abs-col-muted">Total</div>
        </div>
        <div class="abs-stat-hadir">
            <div class="abs-num">{{ $stat['hadir'] }}</div>
            <div class="abs-lbl">Hadir</div>
        </div>
        <div class="abs-stat-izin">
            <div class="abs-num">{{ $stat['izin'] }}</div>
            <div class="abs-lbl">Izin</div>
        </div>
        <div class="abs-stat-sakit">
            <div class="abs-num">{{ $stat['sakit'] }}</div>
            <div class="abs-lbl">Sakit</div>
        </div>
        <div class="abs-stat-alpha">
            <div class="abs-num">{{ $stat['alpha'] }}</div>
            <div class="abs-lbl">Alpha</div>
        </div>
        <div class="abs-stat-belum">
            <div class="abs-num">{{ $stat['belum'] }}</div>
            <div class="abs-lbl">Belum</div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="abs-card" style="overflow:hidden; margin-bottom:24px;">

        <div style="padding:14px 16px; border-bottom:1px solid #e5e7eb;">
            <span style="font-size:14px; font-weight:600;">Daftar Absensi Santri</span>
        </div>

        @if(count($absensiData) > 0)

            {{-- Header --}}
            <div class="abs-thead" style="display:grid; grid-template-columns:40px 120px 1fr 100px 1fr 70px 1fr; padding:10px 16px;">
                @foreach(['No','NIS','Nama Santri','Status','Keterangan','Waktu','Diinput Oleh'] as $h)
                    <div class="abs-col-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">{{ $h }}</div>
                @endforeach
            </div>

            {{-- Rows --}}
            @foreach($absensiData as $index => $data)
                @php
                    $badgeClass = match($data['status'] ?? '') {
                        'hadir' => 'abs-badge-hadir',
                        'izin'  => 'abs-badge-izin',
                        'sakit' => 'abs-badge-sakit',
                        'alpha' => 'abs-badge-alpha',
                        default => 'abs-badge-none',
                    };
                    $icon = match($data['status'] ?? '') {
                        'hadir' => '✓',
                        'izin'  => '📝',
                        'sakit' => '⚕',
                        'alpha' => '✗',
                        default => '⏳',
                    };
                @endphp
                <div class="abs-row" style="display:grid; grid-template-columns:40px 120px 1fr 100px 1fr 70px 1fr; padding:13px 16px; align-items:center;">
                    <div class="abs-col-muted">{{ $loop->iteration }}</div>
                    <div class="abs-col-muted">{{ $data['nis'] }}</div>
                    <div style="font-size:14px; font-weight:500;">{{ $data['nama'] }}</div>
                    <div>
                        <span class="{{ $badgeClass }}" style="display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600;">
                            {{ $icon }} {{ $data['status_label'] }}
                        </span>
                    </div>
                    <div class="abs-col-text">{{ $data['keterangan'] ?? '-' }}</div>
                    <div class="abs-col-muted">{{ $data['waktu'] }}</div>
                    <div class="abs-col-text">{{ $data['diinput'] }}</div>
                </div>
            @endforeach

            {{-- Footer --}}
            <div class="abs-footer" style="padding:12px 16px; display:flex; align-items:center; flex-wrap:wrap; gap:16px;">
                <span style="display:flex; align-items:center; gap:5px; font-size:13px;">
                    <span style="width:8px; height:8px; border-radius:50%; background:#16a34a; display:inline-block;"></span>
                    Hadir: {{ $stat['hadir'] }}
                </span>
                <span style="display:flex; align-items:center; gap:5px; font-size:13px;">
                    <span style="width:8px; height:8px; border-radius:50%; background:#d97706; display:inline-block;"></span>
                    Izin: {{ $stat['izin'] }}
                </span>
                <span style="display:flex; align-items:center; gap:5px; font-size:13px;">
                    <span style="width:8px; height:8px; border-radius:50%; background:#2563eb; display:inline-block;"></span>
                    Sakit: {{ $stat['sakit'] }}
                </span>
                <span style="display:flex; align-items:center; gap:5px; font-size:13px;">
                    <span style="width:8px; height:8px; border-radius:50%; background:#dc2626; display:inline-block;"></span>
                    Alpha: {{ $stat['alpha'] }}
                </span>
                <span style="display:flex; align-items:center; gap:5px; font-size:13px;">
                    <span style="width:8px; height:8px; border-radius:50%; background:#8b5cf6; display:inline-block;"></span>
                    Belum: {{ $stat['belum'] }}
                </span>
                <span style="font-size:13px; font-weight:600; margin-left:auto;">
                    Kehadiran: {{ round((($stat['total'] - $stat['belum']) / max($stat['total'], 1)) * 100) }}%
                </span>
            </div>

        @else
            <div class="abs-col-muted" style="padding:48px; text-align:center; font-size:14px;">
                Tidak ada data absensi untuk tanggal ini.
            </div>
        @endif

    </div>

    {{-- Tombol Kembali --}}
    <div style="display:flex; justify-content:flex-end;">
        <x-filament::button
            color="gray"
            tag="a"
            href="{{ \App\Filament\Resources\Absensis\AbsensiResource::getUrl('index') }}"
            icon="heroicon-o-arrow-left"
        >
            Kembali ke Daftar Kelas
        </x-filament::button>
    </div>

</div>

</x-filament-panels::page>