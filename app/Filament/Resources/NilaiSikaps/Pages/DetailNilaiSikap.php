<?php

namespace App\Filament\Resources\NilaiSikaps\Pages;

use App\Filament\Resources\NilaiSikaps\NilaiSikapResource;
use App\Models\Kelas;
use App\Models\NilaiSikap;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class DetailNilaiSikap extends Page
{
    protected static string $resource = NilaiSikapResource::class;
    protected string $view = 'filament.resources.nilai-sikaps.pages.detail-nilai-sikap';

    public Kelas $kelas;
    public Santri $santri;
    public Semester $semester;
    public ?NilaiSikap $nilaiSikap = null;

    public string $disiplin = 'A';
    public string $tanggung_jawab = 'A';
    public string $kejujuran = 'A';
    public string $sopan_santun = 'A';
    public string $kepedulian = 'A';
    public string $catatan = '';

    public bool $canEdit = false;
    public string $mode = 'view'; // view, edit, create

    public function mount(int|Kelas $record, int|Santri $santri, int|Semester $semester): void
    {
        $this->kelas = $record instanceof Kelas ? $record : Kelas::findOrFail($record);
        $this->santri = $santri instanceof Santri ? $santri : Santri::findOrFail($santri);
        $this->semester = $semester instanceof Semester
            ? $semester->load('tahunAjaran')
            : Semester::with('tahunAjaran')->findOrFail($semester);

        // Check if user can edit - only ustadz wali kelas can edit, admin can only view
        $this->canEdit = $this->checkCanEdit();

        // Determine mode from URL
        $path = request()->path();
        if (str_ends_with($path, '/edit')) {
            // Jika tidak punya akses edit, redirect ke view
            if (!$this->canEdit) {
                $this->redirect(NilaiSikapResource::getUrl('view', [
                    'record' => $this->kelas->id,
                    'santri' => $this->santri->id,
                    'semester' => $this->semester->id,
                ]));
                return;
            }
            $this->mode = 'edit';
        } elseif (str_ends_with($path, '/create')) {
            // Jika tidak punya akses create, redirect ke view
            if (!$this->canEdit) {
                $this->redirect(NilaiSikapResource::getUrl('view', [
                    'record' => $this->kelas->id,
                    'santri' => $this->santri->id,
                    'semester' => $this->semester->id,
                ]));
                return;
            }
            $this->mode = 'create';
        } else {
            $this->mode = 'view';
        }

        // Load existing nilai sikap
        $this->nilaiSikap = NilaiSikap::where('santri_id', $this->santri->id)
            ->where('kelas_id', $this->kelas->id)
            ->where('semester_id', $this->semester->id)
            ->where('tahun_ajaran_id', $this->semester->tahun_ajaran_id)
            ->first();

        if ($this->nilaiSikap) {
            $this->disiplin = $this->nilaiSikap->disiplin ?? 'A';
            $this->tanggung_jawab = $this->nilaiSikap->tanggung_jawab ?? 'A';
            $this->kejujuran = $this->nilaiSikap->kejujuran ?? 'A';
            $this->sopan_santun = $this->nilaiSikap->sopan_santun ?? 'A';
            $this->kepedulian = $this->nilaiSikap->kepedulian ?? 'A';
            $this->catatan = $this->nilaiSikap->catatan ?? '';
        }
    }

    public function save(): void
    {
        if (!$this->canEdit) {
            Notification::make()
                ->title('Anda tidak memiliki izin untuk mengubah data')
                ->body('Hanya ustadz wali kelas yang dapat menginput nilai sikap.')
                ->danger()
                ->send();
            return;
        }

        /** @var User|null $user */
        $user = Auth::user();

        $this->nilaiSikap = NilaiSikap::updateOrCreate(
            [
                'santri_id' => $this->santri->id,
                'kelas_id' => $this->kelas->id,
                'semester_id' => $this->semester->id,
                'tahun_ajaran_id' => $this->semester->tahun_ajaran_id,
            ],
            [
                'ustadz_id' => $user?->ustadz_id,
                'disiplin' => $this->disiplin,
                'tanggung_jawab' => $this->tanggung_jawab,
                'kejujuran' => $this->kejujuran,
                'sopan_santun' => $this->sopan_santun,
                'kepedulian' => $this->kepedulian,
                'catatan' => $this->catatan,
            ]
        );

        Notification::make()
            ->title('Nilai Sikap berhasil disimpan!')
            ->success()
            ->send();

        // Redirect to view mode after save
        $this->redirect(NilaiSikapResource::getUrl('view', [
            'record' => $this->kelas->id,
            'santri' => $this->santri->id,
            'semester' => $this->semester->id,
        ]));
    }

    public function cancel(): void
    {
        // Redirect back to input nilai page
        $this->redirect(NilaiSikapResource::getUrl('input-nilai', [
            'record' => $this->kelas->id,
            'semester' => $this->semester->id,
        ]));
    }

    public function switchToEdit(): void
    {
        $this->redirect(NilaiSikapResource::getUrl('edit', [
            'record' => $this->kelas->id,
            'santri' => $this->santri->id,
            'semester' => $this->semester->id,
        ]));
    }

    /**
     * Check if current user can edit nilai sikap
     * Only ustadz who is wali kelas of this class can edit
     * Admin and other roles can only view
     */
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

    public function getTitle(): string
    {
        return match($this->mode) {
            'create' => 'Form Input Nilai Sikap',
            'edit' => 'Form Input Nilai Sikap',
            default => 'Form Input Nilai Sikap',
        };
    }

    public function getBreadcrumbs(): array
    {
        $modeLabel = match($this->mode) {
            'create' => 'Input Nilai',
            'edit' => 'Edit',
            default => 'View',
        };

        return [
            NilaiSikapResource::getUrl() => 'Nilai Akhlak & Sikap',
            NilaiSikapResource::getUrl('input-nilai', ['record' => $this->kelas->id, 'semester' => $this->semester->id]) => 'Input Nilai Sikap Siswa',
            '#' => $modeLabel,
        ];
    }
}
