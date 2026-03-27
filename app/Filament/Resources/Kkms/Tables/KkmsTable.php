<?php

namespace App\Filament\Resources\Kkms\Tables;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;

class KkmsTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('no')
                ->label('No')
                ->rowIndex()
                ->sortable(false),

            TextColumn::make('mapel.nama_mapel')
                ->label('Nama Mapel')
                ->searchable()
                ->sortable(),

            TextColumn::make('ustadz.nama')
                ->label('Ustadz Pengampu')
                ->searchable()
                ->sortable()
                ->placeholder('-'),

            TextColumn::make('kelas.nama_kelas')
                ->label('Kelas')
                ->searchable()
                ->sortable(),

            TextColumn::make('tahunAjaran.tahun_ajaran')
                ->label('Tahun Ajaran')
                ->sortable(
                    query: fn(Builder $query, string $direction) => $query
                        ->leftJoin('tahun_ajarans', 'kkms.tahun_ajaran_id', '=', 'tahun_ajarans.id')
                        ->orderBy('tahun_ajarans.tahun_awal', $direction)
                        ->select('kkms.*')
                ),

            TextColumn::make('semester.semester')
                ->label('Semester')
                ->formatStateUsing(fn($state) => ucfirst($state))
                ->sortable()
                ->badge()
                ->color(fn($state) => $state === 'ganjil' ? 'info' : 'warning'),

            TextColumn::make('nilai_kkm')
                ->label('KKM')
                ->alignCenter()
                ->badge()
                ->color('primary')
                ->sortable(),

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
            SelectFilter::make('kelas_id')
                ->label('Kelas')
                ->options(function () {
                    return Kelas::orderBy('nama_kelas')
                        ->pluck('nama_kelas', 'id');
                })
                ->searchable()
                ->preload()
                ->placeholder('Semua Kelas'),

            SelectFilter::make('mapel_id')
                ->label('Mata Pelajaran')
                ->options(function () {
                    return Mapel::orderBy('nama_mapel')
                        ->pluck('nama_mapel', 'id');
                })
                ->searchable()
                ->preload()
                ->placeholder('Semua Mapel'),

            SelectFilter::make('semester_id')
                ->label('Semester')
                ->options(function () {
                    return Semester::with('tahunAjaran')
                        ->orderBy('id', 'desc')
                        ->get()
                        ->mapWithKeys(function ($s) {
                            $label = ucfirst($s->semester) . ' - ' . $s->tahunAjaran->tahun_ajaran;
                            return [$s->id => $label];
                        });
                })
                ->searchable()
                ->preload()
                ->placeholder('Semua Semester'),

            SelectFilter::make('tahun_ajaran_id')
                ->label('Tahun Ajaran')
                ->options(function () {
                    return TahunAjaran::orderBy('tahun_awal', 'desc')
                        ->get()
                        ->mapWithKeys(fn($ta) => [$ta->id => $ta->tahun_ajaran]);
                })
                ->searchable()
                ->preload()
                ->placeholder('Semua Tahun Ajaran'),
        ];
    }

    public static function getActions(): array
    {
        return [
            ViewAction::make()
                ->label('View')
                ->color('info'),
            EditAction::make()
                ->label('Edit')
                ->color('warning'),
            DeleteAction::make()
                ->label('Hapus')
                ->color('danger'),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make()->label('Hapus yang dipilih'),
            ]),
        ];
    }
}
