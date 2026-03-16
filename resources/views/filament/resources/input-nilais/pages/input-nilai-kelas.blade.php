<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Info Bar --}}
        <div class="flex flex-wrap items-center gap-4 text-sm">
            <span class="text-gray-500 dark:text-gray-400">Kelas: <strong class="text-gray-900 dark:text-white">{{ $kelas->nama_kelas }}</strong></span>
            <span class="text-gray-300 dark:text-gray-600">|</span>
            <span class="text-gray-500 dark:text-gray-400">Mapel: <strong class="text-gray-900 dark:text-white">{{ $mapel->nama_mapel }}</strong></span>
            <span class="text-gray-300 dark:text-gray-600">|</span>
            <span class="text-gray-500 dark:text-gray-400">Semester: <strong class="text-gray-900 dark:text-white">{{ ucfirst($semester->semester) }}</strong></span>
            <span class="text-gray-300 dark:text-gray-600">|</span>
            <span class="text-gray-500 dark:text-gray-400">Tahun: <strong class="text-gray-900 dark:text-white">{{ $semester->tahunAjaran->tahun_ajaran }}</strong></span>
        </div>

        {{-- Table --}}
        <div class="fi-ta rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <form wire:submit="save">
                <div class="fi-ta-content overflow-x-auto">
                    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                        <thead class="bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th class="fi-ta-header-cell px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">No</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">NIS</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">Nama Santri</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 text-center text-sm font-semibold text-gray-950 dark:text-white">Harian</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 text-center text-sm font-semibold text-gray-950 dark:text-white">UTS</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 text-center text-sm font-semibold text-gray-950 dark:text-white">UAS</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 text-center text-sm font-semibold text-gray-950 dark:text-white">Praktik</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 text-center text-sm font-semibold text-gray-950 dark:text-white">Rata-Rata</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($nilaiData as $santriId => $data)
                                <tr class="fi-ta-row transition duration-75 hover:bg-gray-50 dark:hover:bg-white/5">
                                    <td class="fi-ta-cell px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                                    <td class="fi-ta-cell px-3 py-4 text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $data['nis'] }}</td>
                                    <td class="fi-ta-cell px-3 py-4 text-sm text-gray-950 dark:text-white">{{ $data['nama'] }}</td>
                                    <td class="fi-ta-cell px-3 py-4 text-center">
                                        <input type="number" wire:model.defer="nilaiData.{{ $santriId }}.nilai_harian"
                                            class="fi-input w-16 rounded-lg border-gray-300 text-center text-sm shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white focus:border-primary-500 focus:ring-primary-500"
                                            min="0" max="100" step="0.01">
                                    </td>
                                    <td class="fi-ta-cell px-3 py-4 text-center">
                                        <input type="number" wire:model.defer="nilaiData.{{ $santriId }}.nilai_uts"
                                            class="fi-input w-16 rounded-lg border-gray-300 text-center text-sm shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white focus:border-primary-500 focus:ring-primary-500"
                                            min="0" max="100" step="0.01">
                                    </td>
                                    <td class="fi-ta-cell px-3 py-4 text-center">
                                        <input type="number" wire:model.defer="nilaiData.{{ $santriId }}.nilai_uas"
                                            class="fi-input w-16 rounded-lg border-gray-300 text-center text-sm shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white focus:border-primary-500 focus:ring-primary-500"
                                            min="0" max="100" step="0.01">
                                    </td>
                                    <td class="fi-ta-cell px-3 py-4 text-center">
                                        <input type="number" wire:model.defer="nilaiData.{{ $santriId }}.nilai_praktik"
                                            class="fi-input w-16 rounded-lg border-gray-300 text-center text-sm shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white focus:border-primary-500 focus:ring-primary-500"
                                            min="0" max="100" step="0.01">
                                    </td>
                                    <td class="fi-ta-cell px-3 py-4 text-center text-sm font-semibold {{ $data['nilai_akhir'] >= 70 ? 'text-success-600' : ($data['nilai_akhir'] > 0 ? 'text-danger-600' : 'text-gray-400') }}">
                                        {{ $data['nilai_akhir'] > 0 ? number_format($data['nilai_akhir'], 2) : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Tidak ada santri di kelas ini untuk semester yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="fi-ta-footer border-t border-gray-200 px-4 py-3 dark:border-white/10">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            @if(count($nilaiData) > 0)
                                {{ count($nilaiData) }} santri
                            @endif
                        </span>
                        <div class="flex gap-2">
                            <x-filament::button color="gray" tag="a" :href="$this->getResource()::getUrl('index')">
                                Kembali
                            </x-filament::button>
                            @if(count($nilaiData) > 0)
                                <x-filament::button type="submit" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="save">Simpan</span>
                                    <span wire:loading wire:target="save">Menyimpan...</span>
                                </x-filament::button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-filament-panels::page>
