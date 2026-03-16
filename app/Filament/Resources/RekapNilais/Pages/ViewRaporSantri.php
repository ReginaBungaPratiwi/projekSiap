<?php

namespace App\Filament\Resources\RekapNilais\Pages;

use App\Filament\Resources\RekapNilais\RekapNilaiResource;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\Nilai;
use App\Models\NilaiSikap;
use App\Models\Kkm;
use App\Models\Semester;
use Filament\Resources\Pages\Page;
use Filament\Actions;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewRaporSantri extends Page
{
    protected static string $resource = RekapNilaiResource::class;
    protected string $view = 'filament.pages.rapor-santri';

    public $record; // Kelas
    public $santri; // Santri
    public $semester; // Semester
    public $nilais; // All grades
    public $kkms; // All KKM values
    public $nilaiSikap; // Nilai sikap/akhlak

    public function mount($record, $santri, $semester = null): void
    {
        $this->record = Kelas::with(['waliKelas'])->findOrFail($record);
        $this->santri = Santri::findOrFail($santri);

        if ($semester) {
            $this->semester = Semester::with(['tahunAjaran'])->findOrFail($semester);
        } else {
            $this->semester = Semester::where('status', true)->with(['tahunAjaran'])->first();
        }

        // Get all nilais for this santri in this semester
        $this->nilais = Nilai::where('santri_id', $this->santri->id)
            ->where('kelas_id', $this->record->id)
            ->where('semester_id', $this->semester->id)
            ->where('tahun_ajaran_id', $this->semester->tahun_ajaran_id)
            ->with(['mapel', 'ustadz'])
            ->orderBy('mapel_id')
            ->get();

        // Get all KKMs for this class and semester
        // Ambil KKM berdasarkan mapel_id yang ada di nilai santri ini
        $mapelIds = $this->nilais->pluck('mapel_id')->unique()->toArray();

        $this->kkms = Kkm::where('kelas_id', $this->record->id)
            ->where('semester_id', $this->semester->id)
            ->where('tahun_ajaran_id', $this->semester->tahun_ajaran_id)
            ->whereIn('mapel_id', $mapelIds)
            ->get()
            ->keyBy('mapel_id');

        // Jika tidak ada KKM spesifik untuk kelas ini, coba ambil KKM global per mapel
        if ($this->kkms->isEmpty() && !empty($mapelIds)) {
            $this->kkms = Kkm::where('semester_id', $this->semester->id)
                ->where('tahun_ajaran_id', $this->semester->tahun_ajaran_id)
                ->whereIn('mapel_id', $mapelIds)
                ->get()
                ->keyBy('mapel_id');
        }

        // Get nilai sikap for this santri
        $this->nilaiSikap = NilaiSikap::where('santri_id', $this->santri->id)
            ->where('kelas_id', $this->record->id)
            ->where('semester_id', $this->semester->id)
            ->where('tahun_ajaran_id', $this->semester->tahun_ajaran_id)
            ->first();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadPdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => $this->downloadPdf()),

            Actions\Action::make('print')
                ->label('Print Rapor')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->action(fn () => $this->js('window.print()')),

            Actions\Action::make('back')
                ->label('Kembali')
                ->url(RekapNilaiResource::getUrl('view-kelas', [
                    'record' => $this->record->id,
                    'semester' => $this->semester->id
                ]))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    public function downloadPdf()
    {
        $pdf = Pdf::loadView('pdf.rapor-santri', [
            'santri' => $this->santri,
            'kelas' => $this->record,
            'semester' => $this->semester,
            'nilais' => $this->nilais,
            'kkms' => $this->kkms,
            'nilaiSikap' => $this->nilaiSikap,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $tahunAjaran = str_replace('/', '-', $this->semester->tahunAjaran->tahun_ajaran);
        $namaSantri = str_replace(['/', '\\', ' '], ['', '', '_'], $this->santri->nama_lengkap);
        $filename = 'Rapor_' . $namaSantri . '_' . $this->semester->semester . '_' . $tahunAjaran . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    public function getTitle(): string
    {
        return 'Rapor - ' . $this->santri->nama_lengkap;
    }
}
