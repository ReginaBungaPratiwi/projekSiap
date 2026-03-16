<?php

namespace App\Filament\Resources\NilaiSikaps;

use App\Filament\Resources\NilaiSikaps\Pages;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NilaiSikapResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $slug = 'nilai-akhlak-sikap';

    protected static ?string $policyClass = \App\Policies\NilaiSikapPolicy::class;

    protected static function isUstadzWaliKelas(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || !$user->hasRole('ustadz') || !$user->ustadz_id) {
            return true;
        }

        return Kelas::where('wali_kelas_id', $user->ustadz_id)->exists();
    }

    public static function canViewAny(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && ($user->hasRole('super_admin') || $user->hasRole('admin'))) {
            return false;
        }

        if (!static::isUstadzWaliKelas()) {
            return false;
        }

        return Gate::allows('ViewAny:NilaiSikap');
    }

    public static function shouldRegisterNavigation(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && ($user->hasRole('super_admin') || $user->hasRole('admin'))) {
            return false;
        }

        if (!static::isUstadzWaliKelas()) {
            return false;
        }

        return Gate::allows('ViewAny:NilaiSikap');
    }

    public static function canView($record): bool
    {
        return Gate::allows('View:NilaiSikap');
    }

    public static function canCreate(): bool
    {
        return Gate::allows('Create:NilaiSikap');
    }

    public static function canEdit($record): bool
    {
        return Gate::allows('Update:NilaiSikap');
    }

    public static function canDelete($record): bool
    {
        return Gate::allows('Delete:NilaiSikap');
    }

    public static function getNavigationLabel(): string
    {
        return 'Nilai Akhlak & Sikap';
    }

    public static function getModelLabel(): string
    {
        return 'Nilai Sikap';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Nilai Akhlak & Sikap';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-heart';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Penilaian Santri';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['waliKelas']);

        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
            $query->where('wali_kelas_id', $user->ustadz_id);
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                TextColumn::make('no')
                    ->label('NO')
                    ->rowIndex()
                    ->sortable(false),

                TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('waliKelas.nama')
                    ->label('Nama Ustadz Pengampu')
                    ->sortable()
                    ->default('-'),

                TextColumn::make('tahun_ajaran_display')
                    ->label('Tahun Ajaran')
                    ->state(function ($record, $livewire) {
                        $tahunAjaranId = $livewire->tableFilters['tahun_ajaran']['value'] ?? null;
                        if ($tahunAjaranId) {
                            $tahunAjaran = TahunAjaran::find($tahunAjaranId);
                            return $tahunAjaran?->tahun_ajaran ?? '-';
                        }
                        $activeTahunAjaran = TahunAjaran::where('status', true)->first();
                        return $activeTahunAjaran?->tahun_ajaran ?? '-';
                    }),

                TextColumn::make('semester_display')
                    ->label('Semester')
                    ->state(function ($record, $livewire) {
                        $semesterValue = $livewire->tableFilters['semester']['value'] ?? Semester::where('status', true)->first()?->semester ?? 'ganjil';
                        return 'Semester ' . ucfirst($semesterValue);
                    }),
            ])
            ->filters([
                SelectFilter::make('nama_kelas')
                    ->label('Kelas')
                    ->options(function () {
                        /** @var User|null $user */
                        $user = Auth::user();
                        $query = Kelas::orderBy('nama_kelas');

                        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
                            $query->where('wali_kelas_id', $user->ustadz_id);
                        }

                        return $query->pluck('nama_kelas', 'nama_kelas');
                    })
                    ->searchable()
                    ->preload()
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('nama_kelas', $data['value']);
                        }
                    }),

                SelectFilter::make('semester')
                    ->label('Semester')
                    ->options([
                        'ganjil' => 'Semester Ganjil',
                        'genap' => 'Semester Genap',
                    ])
                    ->default(fn() => Semester::where('status', true)->first()?->semester ?? 'ganjil')
                    ->query(fn(Builder $query) => $query)
                    ->indicateUsing(fn() => null),

                SelectFilter::make('tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->options(function () {
                        return TahunAjaran::orderByDesc('tahun_awal')
                            ->get()
                            ->pluck('tahun_ajaran', 'id');
                    })
                    ->default(fn() => TahunAjaran::where('status', true)->first()?->id)
                    ->query(fn(Builder $query) => $query)
                    ->indicateUsing(fn() => null),
            ])
            ->actions([
                \Filament\Actions\Action::make('masuk_input')
                    ->label('Masuk Untuk Input Nilai')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('primary')
                    ->url(function ($record, $livewire) {
                        $semesterValue = $livewire->tableFilters['semester']['value'] ?? null;
                        $tahunAjaranId = $livewire->tableFilters['tahun_ajaran']['value'] ?? TahunAjaran::where('status', true)->first()?->id;

                        if (!$tahunAjaranId) {
                            return '#';
                        }

                        if ($semesterValue) {
                            $semester = Semester::where('semester', $semesterValue)
                                ->where('tahun_ajaran_id', $tahunAjaranId)
                                ->first();
                        } else {
                            $semester = Semester::where('status', true)->first();
                        }

                        if (!$semester) {
                            return '#';
                        }

                        return static::getUrl('input-nilai', [
                            'record' => $record->id,
                            'semester' => $semester->id,
                        ]);
                    }),
            ])
            ->defaultSort('nama_kelas', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNilaiSikaps::route('/'),
            'input-nilai' => Pages\InputNilaiSikapSiswa::route('/{record}/input/{semester}'),
            'edit' => Pages\DetailNilaiSikap::route('/{record}/santri/{santri}/semester/{semester}/edit'),
            'view' => Pages\DetailNilaiSikap::route('/{record}/santri/{santri}/semester/{semester}/view'),
            'create' => Pages\DetailNilaiSikap::route('/{record}/santri/{santri}/semester/{semester}/create'),
        ];
    }
}
