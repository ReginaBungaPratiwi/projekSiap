<?php

namespace App\Filament\Resources\RekapNilaiSikaps\Pages;

use App\Filament\Resources\RekapNilaiSikaps\RekapNilaiSikapResource;
use App\Models\Kelas;
use App\Models\NilaiSikap;
use App\Models\Semester;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RekapNilaiSikapSiswa extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = RekapNilaiSikapResource::class;
    protected string $view = 'filament.resources.rekap-nilai-sikaps.pages.rekap-nilai-sikap-siswa';

    public Kelas $record;
    public Semester $semester;

    public function mount(int|Kelas $record, int|Semester $semester): void
    {
        $this->record = $record instanceof Kelas
            ? $record->load(['waliKelas'])
            : Kelas::with(['waliKelas'])->findOrFail($record);

        $this->semester = $semester instanceof Semester
            ? $semester->load(['tahunAjaran'])
            : Semester::with(['tahunAjaran'])->findOrFail($semester);
    }

    public function getTitle(): string
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->hasRole('ustadz')) {
            return 'Lihat Rekap Nilai Siswa';
        }

        return 'Rekap Nilai Sikap Siswa';
    }

    public function getSubheading(): ?string
    {
        return 'Kelas: ' . $this->record->nama_kelas . ' | Semester: ' . ucfirst($this->semester->semester) . ' - ' . ($this->semester->tahunAjaran->tahun_ajaran ?? '');
    }

    public function getBreadcrumbs(): array
    {
        /** @var User|null $user */
        $user = Auth::user();

        $breadcrumbLabel = ($user && $user->hasRole('ustadz')) ? 'Laporan Nilai Sikap' : 'Laporan Nilai Akhlak Dan Sikap';

        return [
            RekapNilaiSikapResource::getUrl() => $breadcrumbLabel,
            '#' => $this->getTitle(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                NilaiSikap::query()
                    ->where('kelas_id', $this->record->id)
                    ->where('semester_id', $this->semester->id)
                    ->with(['santri'])
            )
            ->columns([
                TextColumn::make('no')
                    ->label('NO')
                    ->rowIndex()
                    ->sortable(false),

                TextColumn::make('santri.nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(false),

                TextColumn::make('santri.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(false),

                TextColumn::make('disiplin')
                    ->label('Disiplin')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('tanggung_jawab')
                    ->label('Tanggung Jawab')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('kejujuran')
                    ->label('Kejujuran')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('sopan_santun')
                    ->label('Sopan Santun / Adab')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('kepedulian')
                    ->label('Kepedulian / Kerja Sama')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->actions([
                Actions\Action::make('lihat_detail')
                    ->label('Lihat detail')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn(NilaiSikap $record) => RekapNilaiSikapResource::getUrl('detail', [
                        'record' => $record->id,
                    ])),
            ])
            ->defaultSort('id', 'asc');
    }
}
