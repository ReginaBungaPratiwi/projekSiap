<?php

namespace App\Filament\Resources\RekapNilais\Tables;

use App\Models\TahunAjaran;
use App\Models\Semester;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class RekapNilaisTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('no')
                ->label('No')
                ->rowIndex()
                ->sortable(false),

            TextColumn::make('nama_kelas')
                ->label('Nama Kelas')
                ->searchable()
                ->sortable()
                ->weight('bold'),

            TextColumn::make('tahun_ajaran')
                ->label('Tahun Ajaran')
                ->getStateUsing(function ($record) {
                    $tahunAjaran = TahunAjaran::where('status', true)->first();
                    return $tahunAjaran?->tahun_ajaran ?? '-';
                })
                ->sortable(false),

            TextColumn::make('semester_ganjil')
                ->label('Nilai Semester Ganjil')
                ->state('Lihat Rekap Nilai')
                ->icon('heroicon-o-document-text')
                ->color('info')
                ->url(function ($record) {
                    $semester = Semester::where('semester', 'ganjil')
                        ->where('status', true)
                        ->first();

                    if (!$semester) {
                        $semester = Semester::where('semester', 'ganjil')
                            ->orderBy('id', 'desc')
                            ->first();
                    }

                    return $semester
                        ? route('filament.admin.resources.rekap-nilai-kelas.view-kelas', [
                            'record' => $record->id,
                            'semester' => $semester->id
                        ])
                        : '#';
                })
                ->alignCenter(),

            TextColumn::make('semester_genap')
                ->label('Nilai Semester Genap')
                ->state('Lihat Rekap Nilai')
                ->icon('heroicon-o-document-text')
                ->color('warning')
                ->url(function ($record) {
                    $semester = Semester::where('semester', 'genap')
                        ->where('status', true)
                        ->first();

                    if (!$semester) {
                        $semester = Semester::where('semester', 'genap')
                            ->orderBy('id', 'desc')
                            ->first();
                    }

                    return $semester
                        ? route('filament.admin.resources.rekap-nilai-kelas.view-kelas', [
                            'record' => $record->id,
                            'semester' => $semester->id
                        ])
                        : '#';
                })
                ->alignCenter(),
        ];
    }

    public static function getFilters(): array
    {
        return [
            SelectFilter::make('tahun_ajaran')
                ->label('Tahun Ajaran')
                ->options(function () {
                    return TahunAjaran::orderBy('tahun_awal', 'desc')
                        ->get()
                        ->mapWithKeys(fn ($ta) => [$ta->id => $ta->tahun_ajaran]);
                })
                ->searchable()
                ->preload()
                ->placeholder('Semua Tahun Ajaran')
                ->query(function (Builder $query, array $data) {
                    // This is just for display, filtering handled in page query
                    return $query;
                }),
        ];
    }

}
