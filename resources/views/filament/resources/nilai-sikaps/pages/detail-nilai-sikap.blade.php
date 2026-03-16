<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Edit Button for View Mode --}}
        @if($mode === 'view' && $canEdit)
            <div class="flex justify-end">
                <x-filament::button wire:click="switchToEdit" color="warning">
                    <x-heroicon-m-pencil-square class="w-4 h-4 mr-1" />
                    Edit
                </x-filament::button>
            </div>
        @endif

        {{-- Informasi Santri --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-header px-6 py-4 border-b border-gray-200 dark:border-white/10">
                <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white">Informasi Santri</h3>
            </div>
            <div class="fi-section-content p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Nama Siswa</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                            {{ $santri->nama_lengkap }}
                        </div>
                    </div>
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">NIS</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 font-mono dark:bg-gray-800 dark:text-white">
                            {{ $santri->nis }}
                        </div>
                    </div>
                    <div>
                        <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Kelas</label>
                        <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                            {{ $kelas->nama_kelas }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Input Nilai Sikap --}}
        <form wire:submit="save">
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-header px-6 py-4 border-b border-gray-200 dark:border-white/10">
                    <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white">Form Input Nilai Sikap Siswa</h3>
                </div>
                <div class="fi-section-content p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Disiplin --}}
                        <div>
                            <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Disiplin</label>
                            @if($mode === 'view')
                                <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                                    {{ $disiplin }}
                                </div>
                            @else
                                <select wire:model="disiplin"
                                    class="fi-select-input mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            @endif
                        </div>

                        {{-- Tanggung Jawab --}}
                        <div>
                            <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Tanggung Jawab</label>
                            @if($mode === 'view')
                                <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                                    {{ $tanggung_jawab }}
                                </div>
                            @else
                                <select wire:model="tanggung_jawab"
                                    class="fi-select-input mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            @endif
                        </div>

                        {{-- Kejujuran --}}
                        <div>
                            <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Kejujuran</label>
                            @if($mode === 'view')
                                <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                                    {{ $kejujuran }}
                                </div>
                            @else
                                <select wire:model="kejujuran"
                                    class="fi-select-input mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            @endif
                        </div>

                        {{-- Sopan Santun / Adab --}}
                        <div>
                            <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Sopan Santun / Adab</label>
                            @if($mode === 'view')
                                <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                                    {{ $sopan_santun }}
                                </div>
                            @else
                                <select wire:model="sopan_santun"
                                    class="fi-select-input mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            @endif
                        </div>

                        {{-- Kerja Sama --}}
                        <div>
                            <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Kerja Sama</label>
                            @if($mode === 'view')
                                <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 dark:bg-gray-800 dark:text-white">
                                    {{ $kepedulian }}
                                </div>
                            @else
                                <select wire:model="kepedulian"
                                    class="fi-select-input mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            @endif
                        </div>

                        {{-- Catatan Pembinaan --}}
                        <div>
                            <label class="fi-fo-field-wrp-label text-sm font-medium text-gray-950 dark:text-white">Catatan Pembinaan</label>
                            @if($mode === 'view')
                                <div class="fi-input mt-1 block w-full rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-950 min-h-[80px] dark:bg-gray-800 dark:text-white">
                                    {{ $catatan ?: '-' }}
                                </div>
                            @else
                                <textarea wire:model="catatan" rows="3"
                                    class="fi-textarea-input mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    placeholder="Masukan Catatan"></textarea>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 mt-6">
                <x-filament::button color="gray" tag="a" :href="\App\Filament\Resources\NilaiSikaps\NilaiSikapResource::getUrl('input-nilai', ['record' => $kelas->id, 'semester' => $semester->id])">
                    Kembali
                </x-filament::button>

                @if($mode === 'create' || $mode === 'edit')
                    <x-filament::button type="submit" color="success" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">Simpan</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </x-filament::button>
                @endif
            </div>
        </form>
    </div>
</x-filament-panels::page>
