<?php

namespace App\Filament\Resources\InputNilais;

use App\Filament\Resources\InputNilais\Pages;
use App\Models\JadwalPelajaran;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class InputNilaiResource extends Resource
{
    protected static ?string $model = JadwalPelajaran::class;

    protected static ?string $slug = 'input-nilai-akademik';

    protected static ?string $policyClass = \App\Policies\InputNilaiPolicy::class;

    public static function canViewAny(): bool
    {
        return Gate::allows('ViewAny:InputNilai');
    }

    public static function canView($record): bool
    {
        return Gate::allows('View:InputNilai');
    }

    public static function canEdit($record): bool
    {
        return Gate::allows('Update:InputNilai');
    }

    public static function getNavigationLabel(): string
    {
        return 'Input Nilai Akademik';
    }

    public static function getModelLabel(): string
    {
        return 'Input Nilai';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Input Nilai Akademik';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-pencil-square';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Penilaian Santri';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Build subquery for unique kelas + mapel combinations
        $subquery = JadwalPelajaran::selectRaw('MIN(id) as id');

        // Jika user adalah ustadz, filter hanya jadwal yang dia ajar
        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
            $subquery->where('ustadz_id', $user->ustadz_id);
        }

        $subquery->groupBy('kelas_id', 'mapel_id');

        return parent::getEloquentQuery()
            ->whereIn('id', $subquery)
            ->with(['kelas', 'mapel']);
    }

    public static function table(Table $table): Table
    {
        $activeTahunAjaran = TahunAjaran::where('status', true)->first();

        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex()
                    ->sortable(false),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Nama Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('mapel.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('semester_ganjil')
                    ->label('Nilai Semester Ganjil')
                    ->state('Masukkan Nilai')
                    ->color('success')
                    ->icon('heroicon-o-pencil-square')
                    ->url(function ($record) use ($activeTahunAjaran) {
                        if (!$activeTahunAjaran) {
                            return '#';
                        }

                        $semester = Semester::where('semester', 'ganjil')
                            ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                            ->first();

                        if (!$semester) {
                            return '#';
                        }

                        return static::getUrl('input-kelas') . '?' . http_build_query([
                            'kelas' => $record->kelas_id,
                            'mapel' => $record->mapel_id,
                            'semester' => $semester->id,
                        ]);
                    })
                    ->alignCenter(),

                TextColumn::make('semester_genap')
                    ->label('Nilai Semester Genap')
                    ->state('Masukkan Nilai')
                    ->color('warning')
                    ->icon('heroicon-o-pencil-square')
                    ->url(function ($record) use ($activeTahunAjaran) {
                        if (!$activeTahunAjaran) {
                            return '#';
                        }

                        $semester = Semester::where('semester', 'genap')
                            ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                            ->first();

                        if (!$semester) {
                            return '#';
                        }

                        return static::getUrl('input-kelas') . '?' . http_build_query([
                            'kelas' => $record->kelas_id,
                            'mapel' => $record->mapel_id,
                            'semester' => $semester->id,
                        ]);
                    })
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('kelas_id', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInputNilais::route('/'),
            'input-kelas' => Pages\InputNilaiKelas::route('/input'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
