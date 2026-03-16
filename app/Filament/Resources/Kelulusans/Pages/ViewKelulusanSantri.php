<?php

namespace App\Filament\Resources\Kelulusans\Pages;

use App\Filament\Resources\Kelulusans\KelulusanResource;
use App\Models\Kelas;
use App\Models\Kelulusan;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ViewKelulusanSantri extends Page
{
    protected static string $resource = KelulusanResource::class;
    protected string $view = 'filament.resources.kelulusans.pages.view-kelulusan-santri';

    public Kelas $record;
    public Santri $santri;
    public TahunAjaran $tahunAjaran;
    public ?Kelulusan $kelulusan = null;
    public Collection $nilaiList;

    public function mount(int|Kelas $record, int|Santri $santri, int|TahunAjaran $tahun_ajaran): void
    {
        $this->record = $record instanceof Kelas
            ? $record
            : Kelas::findOrFail($record);

        $this->santri = $santri instanceof Santri
            ? $santri->load('kelas')
            : Santri::with('kelas')->findOrFail($santri);

        $this->tahunAjaran = $tahun_ajaran instanceof TahunAjaran
            ? $tahun_ajaran
            : TahunAjaran::findOrFail($tahun_ajaran);

        // Cek otorisasi: ustadz hanya bisa akses kelas yang dia walikan
        /** @var User|null $user */
        $user = Auth::user();
        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
            if ((int) $this->record->wali_kelas_id !== (int) $user->ustadz_id) {
                abort(403, 'Anda tidak memiliki akses ke kelas ini.');
            }
        }

        // Get kelulusan record if exists
        $this->kelulusan = Kelulusan::where('santri_id', $this->santri->id)
            ->where('kelas_id', $this->record->id)
            ->where('tahun_ajaran_id', $this->tahunAjaran->id)
            ->first();

        // Get all nilai for this santri in this class and tahun ajaran
        // We need to get nilai from both semesters
        $semesterIds = Semester::where('tahun_ajaran_id', $this->tahunAjaran->id)
            ->pluck('id');

        $this->nilaiList = Nilai::with('mapel')
            ->where('santri_id', $this->santri->id)
            ->where('kelas_id', $this->record->id)
            ->whereIn('semester_id', $semesterIds)
            ->get()
            ->groupBy('mapel_id')
            ->map(function ($nilaiPerMapel) {
                $firstNilai = $nilaiPerMapel->first();
                $avgNilai = $nilaiPerMapel->avg('nilai_akhir');

                return (object) [
                    'mapel' => $firstNilai->mapel,
                    'rata_rata_raport' => round($avgNilai, 0),
                    'nilai_akhir' => round($avgNilai, 0),
                ];
            })
            ->values();
    }

    public function getTitle(): string
    {
        return 'kelulusan SANTRI';
    }

    public function getBreadcrumbs(): array
    {
        return [
            KelulusanResource::getUrl() => 'List Kelulusan Santri',
            KelulusanResource::getUrl('list-santri', [
                'record' => $this->record->id,
                'tahun_ajaran' => $this->tahunAjaran->id,
            ]) => 'List Santri',
            '#' => 'View',
        ];
    }

    public function getEditUrl(): string
    {
        return KelulusanResource::getUrl('edit-santri', [
            'record' => $this->record->id,
            'santri' => $this->santri->id,
            'tahun_ajaran' => $this->tahunAjaran->id,
        ]);
    }

    /**
     * Check if current user is admin
     */
    public function isAdmin(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && ($user->hasRole('super_admin') || $user->hasRole('admin'));
    }
}
