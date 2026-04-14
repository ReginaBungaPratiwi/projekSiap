<?php

namespace App\Filament\Resources\Santris\Tables;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class SantrisTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('no')
                ->label('No')
                ->rowIndex()
                ->sortable(query: function ($query, string $direction) {
                    // Sort berdasarkan ID santri
                    return $query->orderBy('id', $direction);
                }),

            TextColumn::make('nama_lengkap')
                ->searchable()
                ->sortable()
                ->label('Nama Santri'),

            TextColumn::make('nis')
                ->searchable()
                ->sortable()
                ->label('NIS'),

            // ✅ SORTING UNTUK KELAS SAAT INI
            TextColumn::make('kelasSaatIni')
                ->label('Kelas Saat Ini')
                ->sortable(query: function ($query, string $direction) {
                    // Join dengan tabel kelas untuk sorting
                    return $query
                        ->leftJoin('santri_kelas as sk_latest', function ($join) {
                            $join->on('santris.id', '=', 'sk_latest.santri_id')
                                ->whereRaw('sk_latest.id = (
                                    SELECT MAX(id) 
                                    FROM santri_kelas 
                                    WHERE santri_id = santris.id
                                )');
                        })
                        ->leftJoin('kelas', 'sk_latest.kelas_id', '=', 'kelas.id')
                        ->orderBy('kelas.nama_kelas', $direction)
                        ->select('santris.*'); // Penting: hanya select santris
                })
                ->searchable(query: function ($query, $search) {
                    // Custom search untuk kelas saat ini
                    $query->whereHas('riwayatKelas', function ($q) use ($search) {
                        $q->whereIn('id', function ($subQuery) {
                            $subQuery->selectRaw('MAX(id)')
                                ->from('santri_kelas')
                                ->groupBy('santri_id');
                        })->whereHas('kelas', function ($kelasQuery) use ($search) {
                            $kelasQuery->where('nama_kelas', 'like', "%{$search}%");
                        });
                    });
                })
                ->placeholder('-')
                ->getStateUsing(function ($record) {
                    $riwayatTerbaru = $record->riwayatKelas()
                        ->with('kelas')
                        ->orderBy('tahun_akademik', 'desc')
                        ->orderBy('semester', 'desc')
                        ->first();

                    return $riwayatTerbaru?->kelas?->nama_kelas
                        ?? $record->kelas?->nama_kelas;
                }),

            TextColumn::make('jenjang')
                ->badge()
                ->sortable()
                ->color(fn($state) => match ($state) {
                    'SD' => 'success',
                    'SMP' => 'primary',
                    'SMA' => 'warning',
                    'SMK' => 'info',
                    default => 'gray',
                }),

            TextColumn::make('tahun_masuk')
                ->sortable()
                ->label('Tahun Masuk'),

            TextColumn::make('status')
                ->badge()
                ->sortable()
                ->label('Status')
                ->color(fn($state) => match ($state) {
                    'aktif' => 'success',
                    'nonaktif' => 'danger',
                    'lulus' => 'warning',
                    default => 'gray',
                }),
        ];
    }

    public static function getFilters(): array
    {
        return [
            SelectFilter::make('jenjang')
                ->options([
                    'SD' => 'SD',
                    'SMP' => 'SMP',
                    'SMA' => 'SMA',
                    'SMK' => 'SMK',
                ])
                ->placeholder('Semua Jenjang'),

            SelectFilter::make('status')
                ->options([
                    'aktif' => 'Aktif',
                    'nonaktif' => 'Nonaktif',
                    'lulus' => 'Lulus',
                ])
                ->placeholder('Semua Status'),

            // ✅ PERBAIKI: Filter berdasarkan riwayat kelas terbaru
            SelectFilter::make('kelas_saat_ini')
                ->label('Kelas Saat Ini')
                ->options(function () {
                    // Ambil kelas dari riwayat terbaru setiap santri
                    $kelasIds = \App\Models\SantriKelas::query()
                        ->select('kelas_id')
                        ->whereIn('id', function ($query) {
                            $query->selectRaw('MAX(id)')
                                ->from('santri_kelas')
                                ->groupBy('santri_id');
                        })
                        ->pluck('kelas_id')
                        ->toArray();

                    return \App\Models\Kelas::whereIn('id', $kelasIds)
                        ->pluck('nama_kelas', 'id')
                        ->toArray();
                })
                ->searchable()
                ->preload()
                ->placeholder('Semua Kelas')
                ->query(function ($query, $data) {
                    if (!empty($data['value'])) {
                        $query->whereHas('riwayatKelas', function ($q) use ($data) {
                            $q->whereIn('id', function ($subQuery) {
                                $subQuery->selectRaw('MAX(id)')
                                    ->from('santri_kelas')
                                    ->groupBy('santri_id');
                            })->where('kelas_id', $data['value']);
                        });
                    }
                }),
        ];
    }

    public static function getActions(): array
    {
        return [
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make()
                    ->label('Hapus yang dipilih'),
            ]),
        ];
    }
}
