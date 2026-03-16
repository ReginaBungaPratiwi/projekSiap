<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header Information --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">
                Rekap Nilai Semester {{ ucfirst($this->semester->semester) }}
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Kelas:</span>
                    <p class="text-gray-900 dark:text-white">{{ $this->record->nama_kelas }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Semester:</span>
                    <p class="text-gray-900 dark:text-white">{{ ucfirst($this->semester->semester) }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Tahun Ajaran:</span>
                    <p class="text-gray-900 dark:text-white">{{ $this->semester->tahunAjaran->tahun_ajaran }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Wali Kelas:</span>
                    <p class="text-gray-900 dark:text-white">{{ $this->record->waliKelas?->nama ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Table --}}
        {{ $this->table }}
    </div>
</x-filament-panels::page>
