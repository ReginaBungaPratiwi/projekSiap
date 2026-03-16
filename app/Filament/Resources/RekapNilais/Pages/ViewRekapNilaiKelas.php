<?php

namespace App\Filament\Resources\RekapNilais\Pages;

use App\Filament\Resources\RekapNilais\RekapNilaiResource;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\Santri;
use App\Models\SantriKelas;
use Filament\Resources\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions;

class ViewRekapNilaiKelas extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = RekapNilaiResource::class;
    protected string $view = 'filament.resources.rekap-nilais.pages.view-rekap-nilai-kelas';

    public $record; // Kelas
    public $semester; // Semester ID

    public function mount($record, $semester): void
    {
        $this->record = Kelas::with(['waliKelas'])->findOrFail($record);
        $this->semester = Semester::with(['tahunAjaran'])->findOrFail($semester);
    }

    public function table(Table $table): Table
    {
        // Get all santri in this class for this tahun ajaran
        // Tidak perlu filter semester karena santri di kelas yang sama untuk kedua semester dalam satu tahun ajaran
        $santriIds = SantriKelas::where('kelas_id', $this->record->id)
            ->where('tahun_akademik', $this->semester->tahunAjaran->tahun_ajaran)
            ->pluck('santri_id')
            ->unique();

        return $table
            ->query(
                Santri::whereIn('id', $santriIds)
            )
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),

                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_lengkap')
                    ->label('Nama Santri')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->formatStateUsing(fn ($state) => strtoupper(substr($state, 0, 1)))
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($state) => $state === 'laki-laki' ? 'info' : 'danger'),
            ])
            ->actions([
                Actions\Action::make('view_rapor')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(fn ($record) => RekapNilaiResource::getUrl('rapor', [
                        'record' => $this->record->id,
                        'santri' => $record->id,
                        'semester' => $this->semester->id
                    ])),
            ])
            ->defaultSort('nis', 'asc');
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('back')
                ->label('Kembali')
                ->url(RekapNilaiResource::getUrl('index'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    public function getTitle(): string
    {
        return 'Rekap Nilai - ' . $this->record->nama_kelas;
    }
}
