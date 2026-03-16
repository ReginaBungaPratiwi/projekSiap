<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header with Edit Button (Admin Only) --}}
        @if($this->isAdmin())
        <div class="flex justify-end">
            <x-filament::button tag="a" :href="$this->getEditUrl()" icon="heroicon-o-pencil-square">
                Edit
            </x-filament::button>
        </div>
        @endif

        {{-- Identitas Santri --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-header px-6 py-4 border-b border-gray-200 dark:border-white/10">
                <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white">Identitas Santri</h3>
            </div>
            <div class="fi-section-content p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Nama Siswa</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                            {{ $santri->nama_lengkap ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">NIS</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 font-mono dark:bg-gray-800 dark:text-white">
                            {{ $santri->nis ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Kelas</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                            {{ $record->nama_kelas ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Tahun Ajaran</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                            {{ $tahunAjaran->tahun_ajaran ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nilai Akhir Table --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-header px-6 py-4 border-b border-gray-200 dark:border-white/10">
                <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white">Nilai Akhir</h3>
            </div>
            <div class="fi-section-content p-6">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-left text-sm font-semibold text-gray-950 dark:text-white w-16">NO</th>
                                <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-left text-sm font-semibold text-gray-950 dark:text-white">MATA PELAJARAN</th>
                                <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-center text-sm font-semibold text-gray-950 dark:text-white w-48">NILAI RATA - RATA RAPORT</th>
                                <th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-center text-sm font-semibold text-gray-950 dark:text-white w-32">NILAI AKHIR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($nilaiList as $index => $nilai)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm text-gray-950 dark:text-white">{{ $index + 1 }}</td>
                                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm text-gray-950 dark:text-white">{{ $nilai->mapel->nama_mapel ?? '-' }}</td>
                                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm text-gray-950 dark:text-white text-center">{{ $nilai->rata_rata_raport }}</td>
                                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm text-gray-950 dark:text-white text-center">{{ $nilai->nilai_akhir }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="border border-gray-200 dark:border-gray-700 px-4 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                                        Belum ada data nilai
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Menentukan Kelulusan (Read Only) --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-header px-6 py-4 border-b border-gray-200 dark:border-white/10">
                <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white">Menentukan Kelulusan</h3>
            </div>
            <div class="fi-section-content p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Status Keputusan --}}
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Keputusan</label>
                        <div class="mt-3 space-y-3">
                            <label class="flex items-center gap-3">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full border-2 {{ $kelulusan?->status === 'lulus' ? 'border-primary-600 bg-primary-600' : 'border-gray-300 dark:border-gray-600' }}">
                                    @if($kelulusan?->status === 'lulus')
                                        <span class="h-2 w-2 rounded-full bg-white"></span>
                                    @endif
                                </span>
                                <span class="text-sm font-medium text-gray-950 dark:text-white">LULUS</span>
                            </label>
                            <label class="flex items-center gap-3">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full border-2 {{ $kelulusan?->status === 'tidak_lulus' ? 'border-primary-600 bg-primary-600' : 'border-gray-300 dark:border-gray-600' }}">
                                    @if($kelulusan?->status === 'tidak_lulus')
                                        <span class="h-2 w-2 rounded-full bg-white"></span>
                                    @endif
                                </span>
                                <span class="text-sm font-medium text-gray-950 dark:text-white">TIDAK LULUS</span>
                            </label>
                        </div>
                        @if(!$kelulusan || $kelulusan->status === 'belum_ditentukan')
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 italic">Belum ditentukan</p>
                        @endif
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Catatan</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white min-h-[100px]">
                            {{ $kelulusan?->catatan ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-end gap-3">
            <x-filament::button
                color="gray"
                tag="a"
                :href="\App\Filament\Resources\Kelulusans\KelulusanResource::getUrl('list-santri', ['record' => $record->id, 'tahun_ajaran' => $tahunAjaran->id])"
            >
                Kembali
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
