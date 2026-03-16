<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Informasi Santri --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-header px-6 py-4 border-b border-gray-200 dark:border-white/10">
                <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white">Informasi santri</h3>
            </div>
            <div class="fi-section-content p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Nama Siswa</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                            {{ $record->santri->nama_lengkap ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">NIS</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 font-mono dark:bg-gray-800 dark:text-white">
                            {{ $record->santri->nis ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Kelas</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                            {{ $record->kelas->nama_kelas ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Input Nilai Sikap (View Only) --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-header px-6 py-4 border-b border-gray-200 dark:border-white/10">
                <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white">Form Input Nilai Sikap siswa</h3>
            </div>
            <div class="fi-section-content p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Disiplin --}}
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Disiplin</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                            {{ $record->disiplin ?? '-' }}
                        </div>
                    </div>

                    {{-- Tanggung Jawab --}}
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Tanggung Jawab</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                            {{ $record->tanggung_jawab ?? '-' }}
                        </div>
                    </div>

                    {{-- Kejujuran --}}
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Kejujuran</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                            {{ $record->kejujuran ?? '-' }}
                        </div>
                    </div>

                    {{-- Sopan Santun / Adab --}}
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Sopan Santun / Adab</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                            {{ $record->sopan_santun ?? '-' }}
                        </div>
                    </div>

                    {{-- Kerja Sama --}}
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Kerja Sama</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                            {{ $record->kepedulian ?? '-' }}
                        </div>
                    </div>

                    {{-- Catatan Pembinaan --}}
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Catatan Pembinaan :</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 min-h-[80px] dark:bg-gray-800 dark:text-white">
                            {{ $record->catatan ?: '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-end gap-3">
            <x-filament::button color="gray" tag="a" :href="\App\Filament\Resources\RekapNilaiSikaps\RekapNilaiSikapResource::getUrl()">
                Kembali
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
