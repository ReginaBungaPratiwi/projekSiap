<?php

namespace App\Filament\Resources\Ustadzs\Widgets;

use App\Models\JadwalPelajaran;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class JadwalMengajarWidget extends BaseWidget
{
    public ?int $ustadzId = null;

    protected function getTableQuery(): Builder
    {
        return JadwalPelajaran::query()
            ->where('ustadz_id', $this->ustadzId)
            ->with(['jamPelajaran', 'mapel', 'kelas', 'semester', 'tahunAjaran', 'ustadz']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->heading('Jadwal Mengajar')
            ->columns([
                TextColumn::make('jamPelajaran.nama_jam')
                    ->label('Jam Ke')
                    ->searchable()
                    ->sortable(query: fn (Builder $query, string $direction) => $query
                        ->leftJoin('jam_pelajarans', 'jadwal_pelajarans.jam_pelajaran_id', '=', 'jam_pelajarans.id')
                        ->orderBy('jam_pelajarans.nama_jam', $direction)
                        ->select('jadwal_pelajarans.*')
                    ),
                TextColumn::make('waktu')
                    ->label('Waktu')
                    ->getStateUsing(function ($record) {
                        if (!$record->jamPelajaran) {
                            return '-';
                        }
                        $mulai = $record->jamPelajaran->jam_mulai ? date('H:i', strtotime($record->jamPelajaran->jam_mulai)) : '-';
                        $selesai = $record->jamPelajaran->jam_selesai ? date('H:i', strtotime($record->jamPelajaran->jam_selesai)) : '-';
                        return "{$mulai} - {$selesai}";
                    }),
                TextColumn::make('hari')
                    ->label('Hari')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mapel.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('semester.semester')
                    ->label('Semester')
                    ->formatStateUsing(fn ($state) => $state ? ucfirst($state) : '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tahunAjaran.tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->getStateUsing(fn ($record) => $record->tahunAjaran ? $record->tahunAjaran->tahun_awal . '/' . $record->tahunAjaran->tahun_akhir : '-')
                    ->searchable(query: fn (Builder $query, string $search) => $query
                        ->whereHas('tahunAjaran', fn ($q) => $q
                            ->where('tahun_awal', 'like', "%{$search}%")
                            ->orWhere('tahun_akhir', 'like', "%{$search}%")
                        )
                    )
                    ->sortable(query: fn (Builder $query, string $direction) => $query
                        ->leftJoin('tahun_ajarans', 'jadwal_pelajarans.tahun_ajaran_id', '=', 'tahun_ajarans.id')
                        ->orderBy('tahun_ajarans.tahun_awal', $direction)
                        ->select('jadwal_pelajarans.*')
                    ),
            ])
            ->defaultSort('hari', 'asc');
    }

    public static function canView(): bool
    {
        return true;
    }
}
