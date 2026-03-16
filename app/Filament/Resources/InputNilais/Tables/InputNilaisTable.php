<?php

namespace App\Filament\Resources\InputNilais\Tables;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;

class InputNilaisTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('no')
                ->label('No')
                ->rowIndex()
                ->sortable(false),

            TextColumn::make('santri.nama_lengkap')
                ->label('Nama Santri')
                ->searchable()
                ->sortable(),

            TextColumn::make('santri.nis')
                ->label('NIS')
                ->searchable()
                ->sortable(),

            TextColumn::make('mapel.nama_mapel')
                ->label('Mata Pelajaran')
                ->searchable()
                ->sortable(),

            TextColumn::make('kelas.nama_kelas')
                ->label('Kelas')
                ->sortable(),

            TextColumn::make('nilai_harian')
                ->label('Harian')
                ->alignCenter()
                ->sortable(),

            TextColumn::make('nilai_uts')
                ->label('UTS')
                ->alignCenter()
                ->sortable(),

            TextColumn::make('nilai_uas')
                ->label('UAS')
                ->alignCenter()
                ->sortable(),

            TextColumn::make('nilai_praktik')
                ->label('Praktik')
                ->alignCenter()
                ->sortable(),

            TextColumn::make('nilai_akhir')
                ->label('Nilai Akhir')
                ->alignCenter()
                ->weight('bold')
                ->color(fn ($state) => $state >= 70 ? 'success' : 'danger')
                ->sortable(),

            TextColumn::make('nilai_huruf')
                ->label('Grade')
                ->alignCenter()
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'A' => 'success',
                    'B' => 'info',
                    'C' => 'warning',
                    'D', 'E' => 'danger',
                    default => 'gray',
                })
                ->sortable(),

            TextColumn::make('semester.semester')
                ->label('Semester')
                ->formatStateUsing(fn ($state) => ucfirst($state))
                ->sortable(),
        ];
    }

    public static function getFilters(): array
    {
        return [
            SelectFilter::make('kelas_id')
                ->label('Kelas')
                ->options(Kelas::orderBy('nama_kelas')->get()->mapWithKeys(fn ($k) => [$k->id => $k->nama_kelas]))
                ->searchable()
                ->preload(),

            SelectFilter::make('mapel_id')
                ->label('Mata Pelajaran')
                ->options(Mapel::orderBy('nama_mapel')->get()->mapWithKeys(fn ($m) => [$m->id => $m->nama_mapel]))
                ->searchable()
                ->preload(),

            SelectFilter::make('tahun_ajaran_id')
                ->label('Tahun Ajaran')
                ->options(TahunAjaran::orderBy('tahun_awal', 'desc')->get()->mapWithKeys(fn ($ta) => [$ta->id => $ta->tahun_ajaran]))
                ->searchable()
                ->preload(),

            SelectFilter::make('semester_id')
                ->label('Semester')
                ->options(function () {
                    return Semester::with('tahunAjaran')
                        ->orderBy('id', 'desc')
                        ->get()
                        ->mapWithKeys(fn ($s) => [$s->id => ucfirst($s->semester) . ' - ' . $s->tahunAjaran->tahun_ajaran]);
                })
                ->searchable()
                ->preload(),
        ];
    }

    public static function getActions(): array
    {
        return [
            Actions\ViewAction::make()->label(''),
            Actions\EditAction::make()->label(''),
            Actions\DeleteAction::make()->label(''),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            Actions\BulkActionGroup::make([
                Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}
