<?php

namespace App\Filament\Resources\RekapNilais;

use App\Filament\Resources\RekapNilais\Pages;
use App\Filament\Resources\RekapNilais\Tables\RekapNilaisTable;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RekapNilaiResource extends Resource
{
    // Model will be set dynamically based on user role
    protected static ?string $model = Kelas::class;

    protected static ?string $slug = 'rekap-nilai-kelas';

    protected static ?string $policyClass = \App\Policies\RekapNilaiPolicy::class;

    /**
     * Check if user is Santi (student)
     */
    protected static function isSantri(): bool
    {
        $user = Auth::user();

        // Pastikan user ada dan punya atribut santo_id
        if (!$user instanceof User) {
            return false;
        }

        return !empty($user->santri_id);
    }

    /**
     * Check if user is Ustadz
     */
    protected static function isUstadz(): bool
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            return false;
        }

        // Cek punya ustadz_id dan rolenya ustadz
        return !empty($user->ustadz_id) && $user->hasRole('ustadz');
    }

    /**
     * Dynamically set model based on user role
     */
    public static function resolveRecordRouteBinding($recordKey, $routeKey = null): ?\Illuminate\Database\Eloquent\Model
    {
        // If user is Santi, use Nilai model
        if (static::isSantri()) {
            static::$model = Nilai::class;
        } else {
            static::$model = Kelas::class;
        }

        return parent::resolveRecordRouteBinding($recordKey, $routeKey);
    }

    public static function canViewAny(): bool
    {
        // Santi bisa akses
        if (static::isSantri()) {
            return true;
        }

        // Others check Gate permission
        return Gate::allows('ViewAny:RekapNilai');
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Santi - tampilkan menu "Nilai Saya"
        if (static::isSantri()) {
            return true;
        }

        return Gate::allows('ViewAny:RekapNilai');
    }

    public static function canView($record): bool
    {
        // Santi hanya bisa lihat nilainya sendiri
        if (static::isSantri()) {
            $user = Auth::user();
            if ($user instanceof User && !empty($user->santri_id)) {
                return $record->santri_id == $user->santri_id;
            }
            return false;
        }

        return Gate::allows('View:RekapNilai');
    }

    public static function canEdit($record): bool
    {
        return Gate::allows('Update:RekapNilai');
    }

    public static function getModelLabel(): string
    {
        return 'Rekap Nilai';
    }

    public static function getPluralModelLabel(): string
    {
        if (static::isSantri()) {
            return 'Nilai Saya';
        }
        return 'Rekap Nilai';
    }

    public static function getNavigationLabel(): string
    {
        if (static::isSantri()) {
            return 'Nilai Saya';
        }
        return 'Rekap Nilai Kelas';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-document-chart-bar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Penilaian Santri';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    // ✅ Filter rekap nilai berdasarkan role
    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        // Jika user adalah Santi, gunakan model Nilai
        if ($user instanceof User && !empty($user->santri_id)) {
            static::$model = Nilai::class;
            $query = parent::getEloquentQuery();
            $query->where('santri_id', $user->santri_id);
            return $query;
        }

        // Untuk Admin/Ustadz, gunakan model Kelas
        static::$model = Kelas::class;
        $query = parent::getEloquentQuery();

        // Jika user adalah ustadz, filter kelas yang dia ampu
        if (static::isUstadz()) {
            $ustadzId = $user->ustadz_id;

            // Get kelas where ustadz is wali kelas or pengampu
            $query->where(function ($q) use ($ustadzId) {
                $q->where('wali_kelas_id', $ustadzId)
                    ->orWhereHas('jadwalPelajarans', function ($jq) use ($ustadzId) {
                        $jq->where('ustadz_id', $ustadzId);
                    });
            });
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();

        // Santi menggunakan tabel Nilai
        if ($user instanceof User && !empty($user->santri_id)) {
            return $table
                ->columns([
                    \Filament\Tables\Columns\TextColumn::make('semester.semester')
                        ->label('Semester')
                        ->sortable(),
                    \Filament\Tables\Columns\TextColumn::make('tahunAjaran.tahun_ajaran')
                        ->label('Tahun Ajaran')
                        ->sortable(query: function (Builder $query, string $direction): Builder {
                            return $query->orderBy(
                                \App\Models\TahunAjaran::select('tahun_awal')
                                    ->whereColumn('tahun_ajarans.id', 'nilais.tahun_ajaran_id'),
                                $direction
                            );
                        }),
                    \Filament\Tables\Columns\TextColumn::make('mapel.nama_mapel')
                        ->label('Mata Pelajaran')
                        ->searchable(),
                    \Filament\Tables\Columns\TextColumn::make('nilai_akhir')
                        ->label('Nilai')
                        ->numeric()
                        ->sortable(),
                ])
                ->defaultSort('tahun_ajaran_id', 'desc')
                ->defaultSort('semester_id', 'desc');
        }

        // Admin/Ustadz - tabel Kelas ( tampilan asli )
        return $table
            ->columns(RekapNilaisTable::getColumns())
            ->filters(RekapNilaisTable::getFilters())
            ->defaultSort('nama_kelas', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRekapNilais::route('/'),
            'view-kelas' => Pages\ViewRekapNilaiKelas::route('/{record}/kelas/{semester}'),
            'rapor' => Pages\ViewRaporSantri::route('/{record}/santri/{santri}/rapor/{semester}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
