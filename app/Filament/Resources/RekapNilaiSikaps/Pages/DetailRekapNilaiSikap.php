<?php

namespace App\Filament\Resources\RekapNilaiSikaps\Pages;

use App\Filament\Resources\RekapNilaiSikaps\RekapNilaiSikapResource;
use App\Models\NilaiSikap;
use Filament\Resources\Pages\Page;

class DetailRekapNilaiSikap extends Page
{
    protected static string $resource = RekapNilaiSikapResource::class;
    protected string $view = 'filament.resources.rekap-nilai-sikaps.pages.detail-rekap-nilai-sikap';

    public NilaiSikap $record;

    public function mount(int|NilaiSikap $record): void
    {
        $this->record = $record instanceof NilaiSikap
            ? $record->load(['santri', 'kelas', 'semester.tahunAjaran', 'ustadz'])
            : NilaiSikap::with(['santri', 'kelas', 'semester.tahunAjaran', 'ustadz'])->findOrFail($record);
    }

    public function getTitle(): string
    {
        return 'Detail Rekap Nilai Siswa';
    }

    public function getBreadcrumbs(): array
    {
        return [
            RekapNilaiSikapResource::getUrl() => 'Laporan Nilai Sikap',
            '#' => 'Lihat detail',
        ];
    }
}
