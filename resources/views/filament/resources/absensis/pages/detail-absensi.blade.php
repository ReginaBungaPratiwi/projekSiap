<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header dengan informasi kelas dan filter tanggal -->
        <x-filament::section>
            <x-slot name="heading">
                {{ $kelas->nama_kelas }}
            </x-slot>
            
            <x-slot name="description">
                Wali Kelas: {{ $kelas->waliKelas->nama ?? '-' }}
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div class="flex items-center gap-4">
                    <div class="bg-primary-100 dark:bg-primary-900 rounded-lg p-3">
                        <x-filament::icon
                            name="heroicon-o-calendar"
                            class="w-6 h-6 text-primary-600 dark:text-primary-400"
                        />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal</p>
                        <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <div class="w-full md:w-64">
                        <input 
                            type="date" 
                            wire:model.live="tanggal" 
                            wire:change="updatedTanggal($event.target.value)"
                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            value="{{ $tanggal }}"
                        >
                    </div>
                </div>
            </div>
        </x-filament::section>

        @php $stat = $this->getStatistik(); @endphp

        <!-- Statistik Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Santri</p>
                <p class="text-2xl font-bold">{{ $stat['total'] }}</p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border-l-4 border-green-500 border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Hadir</p>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stat['hadir'] }}</p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border-l-4 border-red-500 border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Sakit</p>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stat['sakit'] }}</p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border-l-4 border-yellow-500 border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Izin</p>
                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stat['izin'] }}</p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border-l-4 border-gray-500 border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Alpha</p>
                <p class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $stat['alpha'] }}</p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border-l-4 border-purple-500 border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum Absen</p>
                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stat['belum'] }}</p>
            </div>
        </div>

        <!-- Tabel Absensi (READ ONLY) -->
        <x-filament::section>
            <x-slot name="heading">
                Daftar Absensi Santri
            </x-slot>

            @if(count($absensiData) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Santri</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Keterangan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diinput Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($absensiData as $index => $data)
                                @php
                                    $statusColors = [
                                        'hadir' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                        'sakit' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                        'izin' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                        'alpha' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    ];
                                    
                                    $statusIcons = [
                                        'hadir' => '✓',
                                        'sakit' => '⚕',
                                        'izin' => '📝',
                                        'alpha' => '✗',
                                    ];
                                    
                                    $color = $data['status'] ? ($statusColors[$data['status']] ?? 'bg-gray-100') : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400';
                                    $icon = $data['status'] ? ($statusIcons[$data['status']] ?? '') : '⏳';
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $data['nis'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $data['nama'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                            {{ $icon }} {{ $data['status_label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $data['keterangan'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $data['waktu'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $data['diinput'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Footer dengan ringkasan -->
                <div class="mt-4 px-6 py-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div class="flex flex-wrap gap-4 text-sm">
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-green-500"></span>
                            Hadir: {{ $stat['hadir'] }}
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span>
                            Sakit: {{ $stat['sakit'] }}
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                            Izin: {{ $stat['izin'] }}
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-gray-500"></span>
                            Alpha: {{ $stat['alpha'] }}
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                            Belum: {{ $stat['belum'] }}
                        </span>
                        <span class="font-semibold ml-auto">
                            Kehadiran: {{ round((($stat['total'] - $stat['belum']) / max($stat['total'], 1)) * 100) }}%
                        </span>
                    </div>
                </div>
            @else
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                    Tidak ada data santri di kelas ini
                </div>
            @endif
        </x-filament::section>

        <!-- Tombol Kembali -->
        <div class="flex justify-end">
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