<?php

namespace App\Filament\Resources\NilaiSikaps\Tables;

use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions;

class NilaiSikapsTable
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

            TextColumn::make('kelas.nama_kelas')
                ->label('Kelas')
                ->sortable(),

            TextColumn::make('sikap_spiritual')
                ->label('Spiritual')
                ->alignCenter()
                ->badge()
                ->color(fn ($state) => self::getBadgeColor($state)),

            TextColumn::make('sikap_sosial')
                ->label('Sosial')
                ->alignCenter()
                ->badge()
                ->color(fn ($state) => self::getBadgeColor($state)),

            TextColumn::make('akhlak')
                ->label('Akhlak')
                ->alignCenter()
                ->badge()
                ->color(fn ($state) => self::getBadgeColor($state)),

            TextColumn::make('kehadiran_persen')
                ->label('Kehadiran')
                ->alignCenter()
                ->suffix('%')
                ->color(fn ($state) => $state >= 80 ? 'success' : ($state >= 60 ? 'warning' : 'danger')),

            TextColumn::make('semester.semester')
                ->label('Semester')
                ->formatStateUsing(fn ($state) => ucfirst($state))
                ->sortable(),

            TextColumn::make('tahunAjaran.tahun_ajaran')
                ->label('Tahun Ajaran')
                ->sortable(),
        ];
    }

    public static function getBadgeColor(string $state): string
    {
        return match ($state) {
            'SB' => 'success',
            'B' => 'info',
            'C' => 'warning',
            'K' => 'danger',
            default => 'gray',
        };
    }

    public static function getFilters(): array
    {
        return [
            SelectFilter::make('kelas_id')
                ->label('Kelas')
                ->options(Kelas::orderBy('nama_kelas')->get()->mapWithKeys(fn ($k) => [$k->id => $k->nama_kelas]))
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

            SelectFilter::make('akhlak')
                ->label('Nilai Akhlak')
                ->options([
                    'SB' => 'Sangat Baik',
                    'B' => 'Baik',
                    'C' => 'Cukup',
                    'K' => 'Kurang',
                ]),
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
