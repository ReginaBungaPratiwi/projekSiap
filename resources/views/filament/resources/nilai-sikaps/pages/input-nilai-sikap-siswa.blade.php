<x-filament-panels::page>
    <div style="font-size: 14px;">

        {{-- Breadcrumb --}}
        <div style="margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b7280;">
                <a href="{{ \App\Filament\Resources\NilaiSikaps\NilaiSikapResource::getUrl() }}" style="color: #6b7280; text-decoration: none;">Nilai Akhlak & Sikap</a>
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span style="font-weight: 600; color: #1f2937;">Input Nilai Sikap Siswa</span>
            </div>
        </div>

        {{-- Header Info dengan Gradient --}}
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; margin-bottom: 24px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
            <div style="padding: 24px;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <div>
                        <h2 style="font-size: 20px; font-weight: 700; color: white; margin-bottom: 8px;">Input Nilai Sikap Siswa</h2>
                        <div style="display: flex; flex-wrap: wrap; gap: 16px; font-size: 13px;">
                            <span style="color: rgba(255,255,255,0.9);">Kelas: <strong style="color: white;">{{ $kelas->nama_kelas ?? '-' }}</strong></span>
                            <span style="color: rgba(255,255,255,0.7);">|</span>
                            <span style="color: rgba(255,255,255,0.9);">Semester: <strong style="color: white;">{{ ucfirst($semester->semester ?? '-') }}</strong></span>
                            <span style="color: rgba(255,255,255,0.7);">|</span>
                            <span style="color: rgba(255,255,255,0.9);">Tahun Ajaran: <strong style="color: white;">{{ $semester->tahunAjaran->tahun_ajaran ?? '-' }}</strong></span>
                        </div>
                    </div>
                    @if(isset($canEdit) && !$canEdit)
                        <span style="display: inline-flex; padding: 6px 14px; background: rgba(255,255,255,0.2); color: white; border-radius: 20px; font-size: 12px; font-weight: 500;">Mode: Hanya Lihat</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Table Daftar Santri --}}
        <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">NO</th>
                            <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">NIS</th>
                            <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">Nama Siswa</th>
                            <th style="padding: 14px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280;">Kelas</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 600; color: #6b7280;">Status</th>
                            <th style="padding: 14px 16px; text-align: center; font-size: 12px; font-weight: 600; color: #6b7280;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($santriData as $index => $data)
                            <tr style="border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fef9e3'" onmouseout="this.style.backgroundColor=''">
                                <td style="padding: 14px 16px; font-size: 13px; color: #6b7280;">{{ $index + 1 }}</td>
                                <td style="padding: 14px 16px; font-size: 13px; color: #6b7280; font-family: monospace;">{{ $data['nis'] }}</td>
                                <td style="padding: 14px 16px; font-size: 13px; font-weight: 500; color: #1f2937;">{{ $data['nama'] }}</td>
                                <td style="padding: 14px 16px; font-size: 13px; color: #6b7280;">{{ $data['kelas'] }}</td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    @if($data['has_nilai'])
                                        <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; background: #dcfce7; color: #16a34a; border-radius: 20px; font-size: 11px; font-weight: 500;">
                                            <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Sudah Input
                                        </span>
                                    @else
                                        <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; background: #fee2e2; color: #dc2626; border-radius: 20px; font-size: 11px; font-weight: 500;">
                                            <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Belum Input
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        {{-- Input / Edit Action --}}
                                        @php
                                            $actionUrl = \App\Filament\Resources\NilaiSikaps\NilaiSikapResource::getUrl(
                                                $data['has_nilai'] ? 'edit' : 'create',
                                                [$kelas->id, $data['santri_id'], $semester->id]
                                            );
                                        @endphp
                                        <a href="{{ $actionUrl }}" 
                                           style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; text-decoration: none; background: {{ $data['has_nilai'] ? '#fef3c7' : '#dbeafe' }}; color: {{ $data['has_nilai'] ? '#d97706' : '#2563eb' }}; border: 1px solid {{ $data['has_nilai'] ? '#fde68a' : '#bfdbfe' }};">
                                            <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            {{ $data['has_nilai'] ? 'Edit Nilai' : 'Input Nilai' }}
                                        </a>

                                        {{-- View Action --}}
                                        @if($data['has_nilai'])
                                            @php
                                                $viewUrl = \App\Filament\Resources\NilaiSikaps\NilaiSikapResource::getUrl('view', [$kelas->id, $data['santri_id'], $semester->id]);
                                            @endphp
                                            <a href="{{ $viewUrl }}" 
                                               style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; text-decoration: none; background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb;">
                                                <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                View
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 60px 16px; text-align: center;">
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                                        <svg style="width: 64px; height: 64px; color: #d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span style="font-size: 14px; color: #9ca3af;">Tidak ada santri di kelas ini untuk tahun ajaran yang dipilih.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Statistik --}}
            @if(count($santriData) > 0)
                <div style="padding: 12px 20px; border-top: 1px solid #e5e7eb; background: #f9fafb;">
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px;">
                        <span style="font-size: 12px; color: #6b7280;">Menampilkan {{ count($santriData) }} data</span>
                        <div style="display: flex; gap: 24px;">
                            <span style="font-size: 12px; color: #6b7280;">Total Santri: <strong style="color: #1f2937;">{{ count($santriData) }}</strong></span>
                            <span style="font-size: 12px; color: #6b7280;">Sudah Input: <strong style="color: #16a34a;">{{ collect($santriData)->where('has_nilai', true)->count() }}</strong></span>
                            <span style="font-size: 12px; color: #6b7280;">Belum Input: <strong style="color: #f59e0b;">{{ collect($santriData)->where('has_nilai', false)->count() }}</strong></span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Back Button --}}
        <div style="display: flex; justify-content: flex-end; margin-top: 24px;">
            <a href="{{ \App\Filament\Resources\NilaiSikaps\NilaiSikapResource::getUrl() }}" 
               style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #f3f4f6; color: #4b5563; border-radius: 10px; font-size: 13px; font-weight: 500; text-decoration: none; border: 1px solid #e5e7eb; transition: all 0.2s;"
               onmouseover="this.style.backgroundColor='#e5e7eb'"
               onmouseout="this.style.backgroundColor='#f3f4f6'">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Kelas
            </a>
        </div>
    </div>
</x-filament-panels::page>