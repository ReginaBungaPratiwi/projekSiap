<?php

namespace App\Filament\Resources\Kelas\Pages;

use App\Filament\Resources\Kelas\KelasResource;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\TahunAjaran;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;

class ViewKelas extends ViewRecord
{
    protected static string $resource = KelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('naikKelasPilihSantri')
                ->label('Naik Kelas (Pilih Santri)')
                ->icon('heroicon-o-user-group')
                ->color('primary')
                ->form([
                    Select::make('jenis_naik_kelas')
                        ->label('Jenis Naik Kelas')
                        ->options([
                            'reguler' => 'Naik Kelas Reguler',
                            'pemilihan' => 'Pilih Kelas Tujuan Manual',
                        ])
                        ->default('reguler')
                        ->required()
                        ->reactive(),

                    Select::make('kelasTujuan')
                        ->label('Kelas Tujuan')
                        ->options(function ($get) {
                            return $get('jenis_naik_kelas') === 'reguler'
                                ? $this->generateKelasTujuanOtomatis()
                                : Kelas::pluck('nama_kelas', 'id')->toArray();
                        })
                        ->required()
                        ->searchable()
                        ->preload(),

                    Section::make('Pilih Santri')
                        ->description('Centang santri yang akan dinaikkan kelasnya')
                        ->schema([
                            CheckboxList::make('santri_ids')
                                ->label('')
                                ->options(function () {
                                    return Santri::where('kelas_id', $this->record->id)
                                        ->orderBy('nama_lengkap')
                                        ->get()
                                        ->mapWithKeys(function ($santri) {
                                            $jk = $santri->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
                                            return [$santri->id => "{$santri->nis} - {$santri->nama_lengkap} ({$jk})"];
                                        })
                                        ->toArray();
                                })
                                ->searchable()
                                ->bulkToggleable()
                                ->columns(1)
                                ->gridDirection('row')
                                ->required(),
                        ]),
                ])
                ->action(function (array $data) {
                    $kelasTujuanId = $data['kelasTujuan'];
                    $santriIds = $data['santri_ids'];
                    $isLulus = $kelasTujuanId === 'lulus';

                    // 🔥 ambil tahun ajaran aktif (kolom: status)
                    $tahunAjaran = TahunAjaran::where('status', true)->first();

                    if (!$tahunAjaran) {
                        Notification::make()
                            ->title('Gagal!')
                            ->body('Tidak ada Tahun Ajaran aktif.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $tahunAkademik = $tahunAjaran->tahun_akademik;
                    $semester = $tahunAjaran->semester ?? 'ganjil';

                    $count = 0;
                    $skipCount = 0;

                    foreach ($santriIds as $santriId) {
                        $santri = Santri::find($santriId);
                        if (!$santri) continue;

                        if ($isLulus) {
                            // Proses LULUS: Update status santri menjadi lulus
                            $santri->update([
                                'status' => 'lulus'
                            ]);
                            $count++;
                        } else {
                            // Proses NAIK KELAS biasa
                            // ⚠ CEK: Tidak boleh naik kelas 2x dalam tahun ajaran yang sama
                            $sudahNaik = $santri->riwayatKelas()
                                ->where('tahun_akademik', $tahunAkademik)
                                ->where('semester', $semester)
                                ->exists();

                            if ($sudahNaik) {
                                Notification::make()
                                    ->title("Peringatan untuk {$santri->nama_lengkap}")
                                    ->body("Santri ini sudah mempunyai riwayat kelas pada Tahun Ajaran {$tahunAkademik} Semester {$semester}. Tidak boleh naik kelas lagi pada semester yang sama.")
                                    ->warning()
                                    ->send();
                                $skipCount++;
                                continue;
                            }

                            // Update kelas santri
                            $santri->update([
                                'kelas_id' => $kelasTujuanId
                            ]);

                            // 🔥 FIX: Gunakan firstOrCreate untuk menghindari duplicate entry
                            $santri->riwayatKelas()->firstOrCreate([
                                'kelas_id' => $kelasTujuanId,
                                'tahun_akademik' => $tahunAkademik,
                                'semester' => $semester,
                            ], [
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            $count++;
                        }
                    }

                    // Notifikasi berdasarkan jenis proses
                    if ($isLulus) {
                        if ($count > 0) {
                            Notification::make()
                                ->title('Proses Kelulusan Berhasil!')
                                ->body("{$count} santri telah diluluskan.")
                                ->success()
                                ->send();
                        }
                    } else {
                        $namaTujuan = Kelas::find($kelasTujuanId)?->nama_kelas ?? 'Tidak Diketahui';

                        if ($count > 0) {
                            Notification::make()
                                ->title('Naik Kelas Berhasil!')
                                ->body("{$count} santri berhasil dipindahkan ke kelas {$namaTujuan}. " .
                                       ($skipCount > 0 ? "{$skipCount} santri dilewati karena sudah memiliki riwayat." : ""))
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Tidak Ada Santri yang Diproses')
                                ->body("Semua santri yang dipilih sudah memiliki riwayat kelas di tahun ajaran ini.")
                                ->warning()
                                ->send();
                        }
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Proses Naik Kelas')
                ->modalSubmitActionLabel('Naikkan'),

            Actions\EditAction::make()->label('Edit'),

            Actions\Action::make('back')
                ->label('Kembali ke Daftar Kelas')
                ->url(KelasResource::getUrl('index'))
                ->color('gray'),
        ];
    }

    /**
     * Generate kelas tujuan otomatis (Reguler) - SEMUA KELAS di tingkat selanjutnya
     */
    private function generateKelasTujuanOtomatis(): array
    {
        $current = $this->record;
        $jenjang = $current->jenjang;

        // Cari tingkat sekarang dari nama kelas
        if (preg_match('/(\d+)/', $current->nama_kelas, $match)) {
            $tingkatSekarang = (int) $match[1];
            $tingkatBaru = $tingkatSekarang + 1;

            // Batas jenjang
            $batas = [
                'SD'  => 6,
                'SMP' => 9,
                'SMA' => 12,
                'SMK' => 12,
            ];

            // Jika sudah tingkat teratas, return kosong
            if (isset($batas[$jenjang]) && $tingkatSekarang >= $batas[$jenjang]) {
                return ['lulus' => '✅ LULUS'];
            }

            // 🔥 CARI SEMUA KELAS di tingkat baru (berdasarkan angka di nama_kelas)
            $allKelas = Kelas::where('jenjang', $jenjang)->get();
            
            $result = [];
            foreach ($allKelas as $kelas) {
                // Cek apakah nama kelas diawali dengan angka tingkat baru
                if (preg_match('/^' . $tingkatBaru . '\D/', $kelas->nama_kelas)) {
                    $result[$kelas->id] = $kelas->nama_kelas . " ($jenjang)";
                }
            }

            // Sorting berdasarkan nama kelas
            asort($result);

            return $result;
        }

        return [];
    }
}