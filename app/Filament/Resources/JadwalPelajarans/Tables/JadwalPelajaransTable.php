<?php

namespace App\Filament\Resources\JadwalPelajarans\Tables;

use App\Models\Kelas;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions;

class JadwalPelajaransTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('no')
                ->label('No')
                ->rowIndex()
                ->sortable(false),

            TextColumn::make('jamPelajaran.nama_jam')
                ->label('Jam Ke')
                ->sortable()
                ->badge()
                ->color('primary'),

            TextColumn::make('waktu')
                ->label('Waktu')
                ->getStateUsing(fn ($record) => $record->waktu)
                ->sortable(query: fn ($query, $direction) => $query->orderBy('jam_pelajaran_id', $direction)),

            TextColumn::make('hari')
                ->label('Hari')
                ->formatStateUsing(fn ($state) => ucfirst($state))
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'senin' => 'info',
                    'selasa' => 'success',
                    'rabu' => 'warning',
                    'kamis' => 'danger',
                    'jumat' => 'primary',
                    'sabtu' => 'gray',
                    default => 'secondary',
                })
                ->sortable(),

            TextColumn::make('mapel.nama_mapel')
                ->label('Mata Pelajaran')
                ->searchable()
                ->sortable(),

            TextColumn::make('ustadz.nama')
                ->label('Pengampu')
                ->searchable()
                ->sortable()
                ->default('-'),

            TextColumn::make('kelas.nama_kelas')
                ->label('Kelas')
                ->searchable()
                ->sortable()
                ->badge()
                ->color('success'),

            TextColumn::make('semester.semester')
                ->label('Semester')
                ->formatStateUsing(fn ($state) => ucfirst($state))
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),

            TextColumn::make('tahunAjaran.tahun_ajaran')
                ->label('Tahun Ajaran')
                ->getStateUsing(fn ($record) => $record->tahunAjaran?->tahun_ajaran ?? '-')
                ->sortable(query: fn ($query, $direction) => $query
                    ->leftJoin('tahun_ajarans', 'jadwal_pelajarans.tahun_ajaran_id', '=', 'tahun_ajarans.id')
                    ->orderBy('tahun_ajarans.tahun_awal', $direction)
                    ->select('jadwal_pelajarans.*')
                )
                ->toggleable(isToggledHiddenByDefault: false),

            TextColumn::make('keterangan')
                ->label('Keterangan')
                ->limit(30)
                ->tooltip(fn ($state) => $state)
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                ->label('Dibuat Pada')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getFilters(): array
    {
        return [
            SelectFilter::make('tahun_ajaran_id')
                ->label('Tahun Ajaran')
                ->options(function () {
                    return TahunAjaran::orderBy('tahun_awal', 'desc')
                        ->get()
                        ->mapWithKeys(fn ($ta) => [$ta->id => $ta->tahun_ajaran]);
                })
                ->searchable()
                ->preload()
                ->placeholder('Semua Tahun Ajaran'),

            SelectFilter::make('semester_id')
                ->label('Semester')
                ->options(function () {
                    return Semester::with('tahunAjaran')
                        ->orderBy('id', 'desc')
                        ->get()
                        ->mapWithKeys(fn ($s) => [$s->id => $s->nama_lengkap]);
                })
                ->searchable()
                ->preload()
                ->placeholder('Semua Semester'),

            SelectFilter::make('kelas_id')
                ->label('Kelas')
                ->options(function () {
                    return Kelas::orderBy('nama_kelas')
                        ->pluck('nama_kelas', 'id');
                })
                ->searchable()
                ->preload()
                ->placeholder('Semua Kelas'),

            SelectFilter::make('hari')
                ->label('Hari')
                ->options([
                    'senin' => 'Senin',
                    'selasa' => 'Selasa',
                    'rabu' => 'Rabu',
                    'kamis' => 'Kamis',
                    'jumat' => 'Jumat',
                    'sabtu' => 'Sabtu',
                ])
                ->placeholder('Semua Hari'),
        ];
    }

    public static function getActions(): array
    {
        return [
            Actions\ViewAction::make()->label('View'),
            Actions\EditAction::make()->label('Edit'),
            Actions\DeleteAction::make()->label('Hapus'),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            Actions\BulkActionGroup::make([
                Actions\DeleteBulkAction::make()->label('Hapus yang dipilih'),
            ]),
        ];
    }
}
