<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Header Info --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4">
            <h2 class="text-lg font-semibold text-gray-950 dark:text-white mb-2">List Siswa</h2>
            <div class="flex flex-wrap items-center gap-4 text-sm">
                <span class="text-gray-500 dark:text-gray-400">Kelas: <strong class="text-gray-900 dark:text-white">{{ $kelas->nama_kelas }}</strong></span>
                <span class="text-gray-300 dark:text-gray-600">|</span>
                <span class="text-gray-500 dark:text-gray-400">Semester: <strong class="text-gray-900 dark:text-white">{{ ucfirst($semester->semester) }}</strong></span>
                <span class="text-gray-300 dark:text-gray-600">|</span>
                <span class="text-gray-500 dark:text-gray-400">Tahun Ajaran: <strong class="text-gray-900 dark:text-white">{{ $semester->tahunAjaran->tahun_ajaran }}</strong></span>
            </div>
        </div>

        {{-- Table --}}
        <div class="fi-ta rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-ta-content overflow-x-auto">
                <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th class="fi-ta-header-cell px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">NO</th>
                            <th class="fi-ta-header-cell px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">NIS</th>
                            <th class="fi-ta-header-cell px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">Nama Siswa</th>
                            <th class="fi-ta-header-cell px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">Kelas</th>
                            <th class="fi-ta-header-cell px-3 py-3.5 text-center text-sm font-semibold text-gray-950 dark:text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                        @forelse($santriData as $index => $data)
                            <tr class="fi-ta-row transition duration-75 hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="fi-ta-cell px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                <td class="fi-ta-cell px-3 py-4 text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $data['nis'] }}</td>
                                <td class="fi-ta-cell px-3 py-4 text-sm text-gray-950 dark:text-white">{{ $data['nama'] }}</td>
                                <td class="fi-ta-cell px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $data['kelas'] }}</td>
                                <td class="fi-ta-cell px-3 py-4">
                                    <div class="flex flex-wrap items-center justify-center gap-1">
                                        {{-- Edit Action --}}
                                        <a href="{{ \App\Filament\Resources\NilaiSikaps\NilaiSikapResource::getUrl('edit', ['record' => $kelas->id, 'santri' => $data['santri_id'], 'semester' => $semester->id]) }}"
                                           class="fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-1.5 py-0.5 bg-warning-50 text-warning-600 ring-warning-600/10 dark:bg-warning-400/10 dark:text-warning-400 dark:ring-warning-400/30">
                                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                                <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                            </svg>
                                            Edit
                                        </a>

                                        {{-- View Action --}}
                                        <a href="{{ \App\Filament\Resources\NilaiSikaps\NilaiSikapResource::getUrl('view', ['record' => $kelas->id, 'santri' => $data['santri_id'], 'semester' => $semester->id]) }}"
                                           class="fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-1.5 py-0.5 bg-gray-50 text-gray-600 ring-gray-600/10 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/30">
                                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                            view
                                        </a>

                                        {{-- Input Nilai Action --}}
                                        <a href="{{ \App\Filament\Resources\NilaiSikaps\NilaiSikapResource::getUrl('create', ['record' => $kelas->id, 'santri' => $data['santri_id'], 'semester' => $semester->id]) }}"
                                           class="fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-1.5 py-0.5 bg-primary-50 text-primary-600 ring-primary-600/10 dark:bg-primary-400/10 dark:text-primary-400 dark:ring-primary-400/30">
                                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" />
                                            </svg>
                                            Input Nilai
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada santri di kelas ini untuk semester yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="fi-ta-footer border-t border-gray-200 px-4 py-3 dark:border-white/10">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        Menampilkan 1-{{ count($santriData) }} dari {{ count($santriData) }} data
                    </span>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Perpages</span>
                        <select class="fi-input rounded-lg border-gray-300 text-sm shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white">
                            <option>15</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Back Button --}}
        <div class="flex justify-end">
            <x-filament::button color="gray" tag="a" :href="$this->getResource()::getUrl('index')">
                Kembali
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
