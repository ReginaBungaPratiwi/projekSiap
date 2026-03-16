<?php

namespace App\Filament\Resources\Kelas\RelationManagers;

use App\Models\SantriKelas;
use App\Models\TahunAjaran;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SantrisRelationManager extends RelationManager
{
    protected static string $relationship = 'santriKelas';

    public function table(Table $table): Table
    {
        $tahunAktif = TahunAjaran::where('status', true)->first();
        $tahunAktifValue = $tahunAktif?->tahun_awal . '/' . $tahunAktif?->tahun_akhir;

        return $table
            ->query(function () {
                return SantriKelas::where('santri_kelas.kelas_id', $this->getOwnerRecord()->id);
            })
            ->columns([
                Tables\Columns\TextColumn::make('santri.nis')
                    ->label('NIS')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('santri.nama_lengkap')
                    ->label('Nama')
                    ->sortable(query: fn (Builder $query, string $direction) => $query
                        ->leftJoin('santris', 'santri_kelas.santri_id', '=', 'santris.id')
                        ->orderBy('santris.nama_lengkap', $direction)
                        ->select('santri_kelas.*')
                    )
                    ->searchable(),

                Tables\Columns\TextColumn::make('santri.jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn ($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan')
                    ->badge()
                    ->color(fn ($state) => $state === 'L' ? 'primary' : 'success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('santri.status')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'aktif' => 'Aktif',
                            'non-aktif' => 'Non-Aktif',
                            'lulus' => 'Lulus',
                            'pindah' => 'Pindah',
                            default => ucfirst($state ?? '-')
                        };
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'aktif' => 'success',
                        'non-aktif' => 'danger',
                        'lulus' => 'primary',
                        'pindah' => 'warning',
                        default => 'gray'
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tahun_akademik')
                    ->label('Tahun Ajaran')
                    ->options(
                        SantriKelas::where('kelas_id', $this->getOwnerRecord()->id)
                            ->orderBy('tahun_akademik', 'desc')
                            ->distinct()
                            ->pluck('tahun_akademik', 'tahun_akademik')
                    )
                    ->default($tahunAktifValue)
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->where('santri_kelas.tahun_akademik', $data['value']);
                        } else {
                            $tahunAktif = TahunAjaran::where('status', true)->first();
                            if ($tahunAktif) {
                                $query->where('santri_kelas.tahun_akademik', $tahunAktif->tahun_awal . '/' . $tahunAktif->tahun_akhir);
                            }
                        }
                    }),
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([])
            ->emptyStateHeading('Belum ada santri di kelas ini')
            ->emptyStateDescription('Santri akan muncul di sini ketika mereka memiliki riwayat di kelas ini.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}