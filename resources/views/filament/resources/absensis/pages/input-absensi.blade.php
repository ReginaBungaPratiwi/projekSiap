<x-filament::page>

    <div class="mb-6">
        <label class="block text-sm font-medium mb-2">
            Tanggal
        </label>
        <input type="date"
               wire:model="tanggal"
               class="filament-input w-full rounded-lg border-gray-300">
    </div>

    <div class="mt-6 mb-4">
        <div class="grid grid-cols-12 gap-4 font-bold text-sm mb-2">
            <div class="col-span-4">Nama Santri</div>
            <div class="col-span-4">Status</div>
            <div class="col-span-4">Keterangan</div>
        </div>
    </div>

    <div class="space-y-3">
        @foreach ($absensiData as $index => $data)
            <div class="grid grid-cols-12 gap-4 items-end border-b pb-2">

                <div class="col-span-4">
                    {{ $data['nama'] }}
                </div>

                <div class="col-span-4">
                    <select
                        wire:model="absensiData.{{ $index }}.status"
                        class="filament-input w-full rounded-lg border-gray-300">

                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alpha">Alpha</option>
                    </select>
                </div>

                <div class="col-span-4">
                    <input type="text"
                        wire:model="absensiData.{{ $index }}.keterangan"
                        placeholder="Keterangan"
                        class="filament-input w-full rounded-lg border-gray-300">
                </div>

            </div>
        @endforeach
    </div>

    <div class="mt-6">
        <x-filament::button wire:click="save" color="success">
            Simpan Absensi
        </x-filament::button>
    </div>

</x-filament::page>