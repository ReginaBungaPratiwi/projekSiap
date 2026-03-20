<x-filament-panels::page>

    <style>
        /* Light mode default */
        .sikap-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .dark .sikap-card {
            background: #1f2937;
            border-color: #374151;
        }

        /* Header Card */
        .card-header {
            padding: 12px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .dark .card-header {
            background: #111827;
            border-bottom-color: #374151;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dark .card-title {
            color: #f3f4f6;
        }

        /* Info Grid untuk Biodata */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            padding: 20px;
        }

        @media (min-width: 768px) {
            .info-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
        }

        .dark .info-label {
            color: #9ca3af;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #111827;
            padding: 6px 10px;
            background: #f9fafb;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        .dark .info-value {
            color: #f3f4f6;
            background: #111827;
            border-color: #374151;
        }

        /* Badge untuk nilai */
        .nilai-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .nilai-sangat-baik {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .nilai-baik {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .nilai-cukup {
            background: #fef9c3;
            color: #854d0e;
            border: 1px solid #fde047;
        }

        .nilai-kurang {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .dark .nilai-sangat-baik {
            background: #14532d;
            color: #86efac;
            border-color: #15803d;
        }

        .dark .nilai-baik {
            background: #1e3a8a;
            color: #93c5fd;
            border-color: #1e40af;
        }

        .dark .nilai-cukup {
            background: #854d0e;
            color: #fef9c3;
            border-color: #ca8a04;
        }

        .dark .nilai-kurang {
            background: #991b1b;
            color: #fecaca;
            border-color: #b91c1c;
        }

        /* Tabel dengan grid system */
        .table-wrapper {
            overflow-x: auto;
        }

        .sikap-thead {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            display: grid;
            padding: 10px 20px;
        }

        .dark .sikap-thead {
            background: #111827;
            border-bottom-color: #374151;
        }

        .sikap-row {
            display: grid;
            padding: 12px 20px;
            border-bottom: 1px solid #f3f4f6;
            align-items: center;
        }

        .dark .sikap-row {
            border-bottom-color: #1f2937;
        }

        .sikap-row:hover {
            background: #fef9e3;
        }

        .dark .sikap-row:hover {
            background: #1e293b;
        }

        .sikap-col-muted {
            font-size: 12px;
            color: #6b7280;
        }

        .dark .sikap-col-muted {
            color: #9ca3af;
        }

        .sikap-col-text {
            font-size: 13px;
            color: #374151;
        }

        .dark .sikap-col-text {
            color: #e5e7eb;
        }

        /* Catatan box */
        .catatan-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 16px;
            margin: 20px;
            border-radius: 8px;
        }

        .dark .catatan-box {
            background: #1e1b04;
        }

        .catatan-content {
            color: #78350f;
            line-height: 1.5;
            font-size: 13px;
        }

        .dark .catatan-content {
            color: #fde68a;
        }

        /* Rekomendasi box */
        .rekomendasi-box {
            background: #fffbeb;
            padding: 20px;
            border-radius: 12px;
        }

        .dark .rekomendasi-box {
            background: #1e1b04;
        }

        /* Footer statistik */
        .sikap-footer {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .dark .sikap-footer {
            background: #111827;
            border-top-color: #374151;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
        }

        .dark .stat-item {
            color: #9ca3af;
        }

        .stat-bullet {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7280;
        }

        .dark .breadcrumb {
            color: #9ca3af;
        }

        .breadcrumb-active {
            font-weight: 600;
            color: #1f2937;
        }

        .dark .breadcrumb-active {
            color: #f3f4f6;
        }
    </style>

    <div class="space-y-5">

        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <span>Penilaian Santri</span>
            <span>›</span>
            <span>Rekap Nilai Sikap</span>
            <span>›</span>
            <span class="breadcrumb-active">Detail Nilai Sikap</span>
        </div>

        {{-- Informasi Santri --}}
        <div class="sikap-card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Informasi Santri
                </div>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nama Lengkap</span>
                    <div class="info-value">{{ $record->santri->nama_lengkap ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">NIS</span>
                    <div class="info-value font-mono">{{ $record->santri->nis ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Kelas</span>
                    <div class="info-value">{{ $record->kelas->nama_kelas ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Tahun Ajaran</span>
                    <div class="info-value">
                        @php
                            $tahunAjaran = null;
                            if (isset($record->tahunAjaran) && $record->tahunAjaran) {
                                $tahunAjaran = $record->tahunAjaran->nama ?? $record->tahunAjaran->tahun_ajaran ?? '-';
                            } elseif (isset($record->tahun_ajaran) && $record->tahun_ajaran) {
                                $tahunAjaran = $record->tahun_ajaran->nama ?? $record->tahun_ajaran->tahun_ajaran ?? '-';
                            } elseif ($record->tahun_ajaran_id) {
                                $tahunAjaran = 'ID: ' . $record->tahun_ajaran_id;
                            } else {
                                $tahunAjaran = '-';
                            }
                        @endphp
                        {{ $tahunAjaran }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Semester</span>
                    <div class="info-value">
                        @php
                            $semester = null;
                            if (isset($record->semester) && $record->semester) {
                                $semester = $record->semester->nama ?? $record->semester->semester ?? '-';
                            } elseif (isset($record->semesterData) && $record->semesterData) {
                                $semester = $record->semesterData->nama ?? $record->semesterData->semester ?? '-';
                            } elseif ($record->semester_id) {
                                $semester = 'ID: ' . $record->semester_id;
                            } else {
                                $semester = '-';
                            }
                        @endphp
                        {{ $semester }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Wali Kelas</span>
                    <div class="info-value">{{ $record->kelas->waliKelas->nama ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Tabel Detail Nilai Sikap --}}
        <div class="sikap-card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    Detail Penilaian Sikap
                </div>
            </div>

            @php
                $aspekList = [
                    ['label' => 'Disiplin', 'value' => $record->disiplin ?? null, 'desc' => 'Kedisiplinan dalam mengikuti aturan dan tata tertib'],
                    ['label' => 'Tanggung Jawab', 'value' => $record->tanggung_jawab ?? null, 'desc' => 'Kesadaran menyelesaikan tugas dan kewajiban'],
                    ['label' => 'Kejujuran', 'value' => $record->kejujuran ?? null, 'desc' => 'Kejujuran dalam perkataan dan perbuatan'],
                    ['label' => 'Sopan Santun', 'value' => $record->sopan_santun ?? null, 'desc' => 'Sikap hormat dan santun kepada guru & teman'],
                    ['label' => 'Kerja Sama', 'value' => $record->kepedulian ?? null, 'desc' => 'Kemampuan bekerja sama dan kepedulian sosial'],
                ];

                function getPredikat($nilai) {
                    if (is_null($nilai) || $nilai === '-') return null;
                    $huruf = strtoupper(trim($nilai));
                    if ($huruf == 'A') return ['text' => 'Sangat Baik', 'class' => 'nilai-sangat-baik'];
                    if ($huruf == 'B') return ['text' => 'Baik', 'class' => 'nilai-baik'];
                    if ($huruf == 'C') return ['text' => 'Cukup', 'class' => 'nilai-cukup'];
                    if ($huruf == 'D') return ['text' => 'Kurang', 'class' => 'nilai-kurang'];
                    return null;
                }

                $totalAspek = 0;
                $totalNilai = 0;
                $nilaiMap = ['A' => 4, 'B' => 3, 'C' => 2, 'D' => 1];
                
                foreach ($aspekList as $aspek) {
                    if ($aspek['value'] && isset($nilaiMap[strtoupper($aspek['value'])])) {
                        $totalAspek++;
                        $totalNilai += $nilaiMap[strtoupper($aspek['value'])];
                    }
                }
                
                $rataRata = $totalAspek > 0 ? round($totalNilai / $totalAspek, 1) : 0;
                $predikatRata = $rataRata >= 3.5 ? 'Sangat Baik' : ($rataRata >= 2.5 ? 'Baik' : ($rataRata >= 1.5 ? 'Cukup' : 'Kurang'));
            @endphp

            <div class="table-wrapper">
                <div class="sikap-thead" style="grid-template-columns: 60px 1fr 80px 120px 1fr;">
                    <div class="sikap-col-muted">No</div>
                    <div class="sikap-col-muted">Aspek Sikap</div>
                    <div class="sikap-col-muted">Nilai</div>
                    <div class="sikap-col-muted">Predikat</div>
                    <div class="sikap-col-muted">Keterangan</div>
                </div>

                @foreach($aspekList as $index => $aspek)
                    @php
                        $nilai = $aspek['value'];
                        $predikat = getPredikat($nilai);
                    @endphp
                    <div class="sikap-row" style="grid-template-columns: 60px 1fr 80px 120px 1fr;">
                        <div class="sikap-col-muted">{{ $index + 1 }}</div>
                        <div class="sikap-col-text" style="font-weight: 500;">{{ $aspek['label'] }}</div>
                        <div>
                            @if($nilai)
                                <span style="font-weight: 700; font-size: 16px;">{{ $nilai }}</span>
                            @else
                                <span class="sikap-col-muted">-</span>
                            @endif
                        </div>
                        <div>
                            @if($predikat)
                                <span class="nilai-badge {{ $predikat['class'] }}">{{ $predikat['text'] }}</span>
                            @else
                                <span class="sikap-col-muted">-</span>
                            @endif
                        </div>
                        <div class="sikap-col-text">{{ $aspek['desc'] }}</div>
                    </div>
                @endforeach
            </div>

            <div class="sikap-footer">
                <div class="stat-item">
                    <span class="stat-bullet" style="background: #10b981;"></span>
                    <span>A (Sangat Baik)</span>
                </div>
                <div class="stat-item">
                    <span class="stat-bullet" style="background: #3b82f6;"></span>
                    <span>B (Baik)</span>
                </div>
                <div class="stat-item">
                    <span class="stat-bullet" style="background: #f59e0b;"></span>
                    <span>C (Cukup)</span>
                </div>
                <div class="stat-item">
                    <span class="stat-bullet" style="background: #ef4444;"></span>
                    <span>D (Kurang)</span>
                </div>
                <div class="stat-item" style="margin-left: auto;">
                    <span>⭐ Rata-rata: {{ $rataRata }} ({{ $predikatRata }})</span>
                </div>
            </div>
        </div>

        {{-- Catatan Pembinaan --}}
        <div class="sikap-card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    Catatan Pembinaan
                </div>
            </div>
            @if($record->catatan && trim($record->catatan) !== '')
                <div class="catatan-box">
                    <div style="display: flex; gap: 12px;">
                        <span style="font-size: 20px;">📝</span>
                        <div style="flex: 1;">
                            <div class="catatan-content">{{ $record->catatan }}</div>
                            @if($record->updated_at)
                                <div style="font-size: 11px; color: #9ca3af; margin-top: 10px;">
                                    Diperbarui: {{ \Carbon\Carbon::parse($record->updated_at)->translatedFormat('d F Y') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div style="padding: 40px; text-align: center;">
                    <div style="font-size: 40px; margin-bottom: 8px;">📭</div>
                    <div class="sikap-col-muted">Belum ada catatan pembinaan</div>
                </div>
            @endif
        </div>

        {{-- Rekomendasi --}}
        @php
            $nilaiMap2 = [
                'Disiplin' => $record->disiplin,
                'Tanggung Jawab' => $record->tanggung_jawab,
                'Kejujuran' => $record->kejujuran,
                'Sopan Santun' => $record->sopan_santun,
                'Kerja Sama' => $record->kepedulian,
            ];
            
            $nilaiAngka = [];
            $hurufToAngka = ['A' => 4, 'B' => 3, 'C' => 2, 'D' => 1];
            
            foreach ($nilaiMap2 as $aspek => $huruf) {
                if ($huruf && isset($hurufToAngka[strtoupper($huruf)])) {
                    $nilaiAngka[$aspek] = $hurufToAngka[strtoupper($huruf)];
                }
            }
            
            $minAspek = !empty($nilaiAngka) ? array_keys($nilaiAngka, min($nilaiAngka))[0] : null;
            $rekomendasi = '';
            
            if ($minAspek) {
                if ($minAspek == 'Disiplin') $rekomendasi = 'Tingkatkan kedisiplinan dengan membuat jadwal harian dan konsisten melaksanakan aturan kelas.';
                elseif ($minAspek == 'Tanggung Jawab') $rekomendasi = 'Latih tanggung jawab dengan menyelesaikan tugas tepat waktu dan aktif dalam kegiatan kelompok.';
                elseif ($minAspek == 'Kejujuran') $rekomendasi = 'Tanamkan kejujuran dengan menjadi pribadi yang transparan dan berani mengakui kesalahan.';
                elseif ($minAspek == 'Sopan Santun') $rekomendasi = 'Asah adab dan sopan santun melalui pembiasaan salam, senyum, dan berkata lembut.';
                elseif ($minAspek == 'Kerja Sama') $rekomendasi = 'Kembangkan kerja sama dengan berpartisipasi aktif dalam diskusi dan membantu teman.';
            }
        @endphp

        @if($rekomendasi)
            <div class="sikap-card">
                <div class="rekomendasi-box">
                    <div style="display: flex; gap: 12px;">
                        <span style="font-size: 24px;">⭐</span>
                        <div>
                            <h4 style="font-weight: 600; margin-bottom: 6px; color: #1f2937;" class="dark:text-white">Rekomendasi Pengembangan Karakter</h4>
                            <p style="font-size: 13px; color: #78350f; line-height: 1.5;" class="dark:text-yellow-200">{{ $rekomendasi }}</p>
                            <p style="font-size: 11px; color: #9ca3af; margin-top: 8px;">Berdasarkan aspek dengan nilai terendah: <strong>{{ $minAspek }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tombol Kembali --}}
        <div style="display: flex; justify-content: flex-end;">
            <x-filament::button
                color="gray"
                tag="a"
                :href="\App\Filament\Resources\RekapNilaiSikaps\RekapNilaiSikapResource::getUrl()"
                icon="heroicon-o-arrow-left"
            >
                Kembali ke Daftar Rekap
            </x-filament::button>
        </div>
    </div>

</x-filament-panels::page>