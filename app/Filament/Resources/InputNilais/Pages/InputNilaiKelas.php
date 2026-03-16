<?php

namespace App\Filament\Resources\InputNilais\Pages;

use App\Filament\Resources\InputNilais\InputNilaiResource;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Semester;
use App\Models\Santri;
use App\Models\SantriKelas;
use App\Models\Nilai;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Attributes\Url;

class InputNilaiKelas extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = InputNilaiResource::class;
    protected string $view = 'filament.resources.input-nilais.pages.input-nilai-kelas';

    public static function canAccess(array $parameters = []): bool
    {
        return true;
    }

    public Kelas $kelas;
    public Mapel $mapel;
    public Semester $semester;

    public array $nilaiData = [];

    public function mount(): void
    {
        $kelas = request()->query('kelas');
        $mapel = request()->query('mapel');
        $semester = request()->query('semester');

        $this->kelas = Kelas::findOrFail($kelas);
        $this->mapel = Mapel::findOrFail($mapel);
        $this->semester = Semester::with('tahunAjaran')->findOrFail($semester);

        $this->loadNilaiData();
    }

    public function loadNilaiData(): void
    {
        // Get santri in this kelas for this tahun ajaran
        // Tidak perlu filter semester karena santri di kelas yang sama untuk kedua semester dalam satu tahun ajaran
        $santriIds = SantriKelas::where('kelas_id', $this->kelas->id)
            ->where('tahun_akademik', $this->semester->tahunAjaran->tahun_ajaran)
            ->pluck('santri_id')
            ->unique();

        $santris = Santri::whereIn('id', $santriIds)
            ->orderBy('nama_lengkap')
            ->get();

        $this->nilaiData = [];

        foreach ($santris as $santri) {
            // Get existing nilai if any
            $nilai = Nilai::where('santri_id', $santri->id)
                ->where('mapel_id', $this->mapel->id)
                ->where('kelas_id', $this->kelas->id)
                ->where('semester_id', $this->semester->id)
                ->where('tahun_ajaran_id', $this->semester->tahun_ajaran_id)
                ->first();

            $this->nilaiData[$santri->id] = [
                'santri_id' => $santri->id,
                'nis' => $santri->nis,
                'nama' => $santri->nama_lengkap,
                'nilai_harian' => $nilai?->nilai_harian ?? '',
                'nilai_uts' => $nilai?->nilai_uts ?? '',
                'nilai_uas' => $nilai?->nilai_uas ?? '',
                'nilai_praktik' => $nilai?->nilai_praktik ?? '',
                'nilai_akhir' => $nilai?->nilai_akhir ?? 0,
                'nilai_huruf' => $nilai?->nilai_huruf ?? '-',
            ];
        }
    }

    public function save(): void
    {
        foreach ($this->nilaiData as $santriId => $data) {
            // Skip if all values are empty
            if (empty($data['nilai_harian']) && empty($data['nilai_uts']) && empty($data['nilai_uas']) && empty($data['nilai_praktik'])) {
                continue;
            }

            Nilai::updateOrCreate(
                [
                    'santri_id' => $santriId,
                    'mapel_id' => $this->mapel->id,
                    'kelas_id' => $this->kelas->id,
                    'semester_id' => $this->semester->id,
                    'tahun_ajaran_id' => $this->semester->tahun_ajaran_id,
                ],
                [
                    'nilai_harian' => !empty($data['nilai_harian']) ? $data['nilai_harian'] : null,
                    'nilai_uts' => !empty($data['nilai_uts']) ? $data['nilai_uts'] : null,
                    'nilai_uas' => !empty($data['nilai_uas']) ? $data['nilai_uas'] : null,
                    'nilai_praktik' => !empty($data['nilai_praktik']) ? $data['nilai_praktik'] : null,
                ]
            );
        }

        // Reload data to show calculated values
        $this->loadNilaiData();

        Notification::make()
            ->title('Nilai berhasil disimpan!')
            ->success()
            ->send();
    }

    public function getTitle(): string
    {
        return 'Form Input Nilai Siswa ' . ucfirst($this->semester->semester);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
