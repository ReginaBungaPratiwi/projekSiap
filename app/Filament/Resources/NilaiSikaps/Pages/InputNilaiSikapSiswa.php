<?php

namespace App\Filament\Resources\NilaiSikaps\Pages;

use App\Filament\Resources\NilaiSikaps\NilaiSikapResource;
use App\Models\Kelas;
use App\Models\NilaiSikap;
use App\Models\Santri;
use App\Models\SantriKelas;
use App\Models\Semester;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class InputNilaiSikapSiswa extends Page
{
    protected static string $resource = NilaiSikapResource::class;
    protected string $view = 'filament.resources.nilai-sikaps.pages.input-nilai-sikap-siswa';

    public Kelas $kelas;
    public Semester $semester;
    public array $santriData = [];
    public bool $canEdit = false;

    public function mount(int|Kelas $record, int|Semester $semester): void
    {
        $this->kelas = $record instanceof Kelas ? $record : Kelas::findOrFail($record);
        $this->semester = $semester instanceof Semester
            ? $semester->load('tahunAjaran')
            : Semester::with('tahunAjaran')->findOrFail($semester);

        // Check if user can edit (only ustadz wali kelas)
        $this->canEdit = $this->checkCanEdit();

        $this->loadSantriData();
    }

    protected function checkCanEdit(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Admin cannot edit, only view
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return false;
        }

        // Ustadz can only edit if they are wali kelas of this class
        if ($user->hasRole('ustadz') && $user->ustadz_id) {
            return $this->kelas->wali_kelas_id === $user->ustadz_id;
        }

        return false;
    }

    protected function loadSantriData(): void
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

        $this->santriData = [];

        foreach ($santris as $santri) {
            // Get existing nilai sikap if any
            $nilaiSikap = NilaiSikap::where('santri_id', $santri->id)
                ->where('kelas_id', $this->kelas->id)
                ->where('semester_id', $this->semester->id)
                ->where('tahun_ajaran_id', $this->semester->tahun_ajaran_id)
                ->first();

            $this->santriData[] = [
                'santri_id' => $santri->id,
                'nis' => $santri->nis,
                'nama' => $santri->nama_lengkap,
                'kelas' => $this->kelas->nama_kelas,
                'has_nilai' => $nilaiSikap !== null,
            ];
        }
    }

    public function getTitle(): string
    {
        return 'Input Nilai Sikap Siswa';
    }

    public function getBreadcrumbs(): array
    {
        return [
            NilaiSikapResource::getUrl() => 'Nilai Akhlak & Sikap',
            '#' => 'Masuk Untuk Input Nilai',
        ];
    }

    public function deleteNilai(int $santriId): void
    {
        // Check permission
        if (!Gate::allows('Delete:NilaiSikap')) {
            Notification::make()
                ->title('Akses Ditolak')
                ->body('Anda tidak memiliki izin untuk menghapus nilai sikap.')
                ->danger()
                ->send();
            return;
        }

        // Check if user can edit this class
        if (!$this->canEdit) {
            Notification::make()
                ->title('Akses Ditolak')
                ->body('Anda tidak memiliki izin untuk menghapus nilai sikap di kelas ini.')
                ->danger()
                ->send();
            return;
        }

        // Find and delete nilai sikap
        $nilaiSikap = NilaiSikap::where('santri_id', $santriId)
            ->where('kelas_id', $this->kelas->id)
            ->where('semester_id', $this->semester->id)
            ->where('tahun_ajaran_id', $this->semester->tahun_ajaran_id)
            ->first();

        if ($nilaiSikap) {
            $nilaiSikap->delete();

            Notification::make()
                ->title('Berhasil')
                ->body('Nilai sikap berhasil dihapus.')
                ->success()
                ->send();

            // Reload data
            $this->loadSantriData();
        } else {
            Notification::make()
                ->title('Gagal')
                ->body('Nilai sikap tidak ditemukan.')
                ->danger()
                ->send();
        }
    }
}
