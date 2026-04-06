<?php

namespace App\Filament\Resources\RekapNilaiSikaps;

use App\Filament\Resources\RekapNilaiSikaps\Pages;
use App\Models\Kelas;
use App\Models\NilaiSikap;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * @property User $user
 */
class RekapNilaiSikapResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $slug = 'laporan-nilai-sikap';

    /**
     * Cek apakah user memiliki permission RekapNilaiSikap (kompatibel dengan Shield)
     */
    private static function hasRekapNilaiSikapPermission(string $action = 'viewAny'): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Super Admin bisa akses semuanya
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Admin juga bisa akses
        if ($user->hasRole('admin')) {
            return true;
        }

        // Format permission yang benar untuk Shield: snake_case
        $permissionMap = [
            'viewAny' => 'view_any_rekap_nilai_sikap',
            'view' => 'view_rekap_nilai_sikap',
            'create' => 'create_rekap_nilai_sikap',
            'edit' => 'edit_rekap_nilai_sikap',
            'update' => 'update_rekap_nilai_sikap',
            'delete' => 'delete_rekap_nilai_sikap',
        ];

        $permissionName = $permissionMap[$action] ?? $action . '_rekap_nilai_sikap';

        // Cek apakah user punya permission
        try {
            if ($user->can($permissionName)) {
                return true;
            }
        } catch (\Exception $e) {
            // Permission tidak ada, lanjut ke cek berikutnya
        }

        // Fallback: cek apakah user punya permission yang mengandung 'rekap' atau 'nilai'
        $roles = $user->roles();

        if ($roles->count() > 0) {
            $permissions = $user->getAllPermissions();
            $permissionNames = $permissions->pluck('name')->toArray();

            // Cek jika ada permission yang mengandung 'rekap' atau 'nilai'
            foreach ($permissionNames as $perm) {
                $permLower = strtolower($perm);
                if (str_contains($permLower, 'rekap') || str_contains($permLower, 'nilai')) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function canViewAny(): bool
    {
        return self::hasRekapNilaiSikapPermission('viewAny');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::hasRekapNilaiSikapPermission('viewAny');
    }

    public static function canView($record): bool
    {
        return self::hasRekapNilaiSikapPermission('view');
    }

    public static function canCreate(): bool
    {
        return false; // Tidak ada yang bisa create
    }

    public static function canEdit($record): bool
    {
        return false; // Tidak ada yang bisa edit
    }

    public static function canDelete($record): bool
    {
        return false; // Tidak ada yang bisa delete
    }

    public static function getNavigationLabel(): string
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Jika role adalah Santri, ubah label menu menjadi "Laporan Nilai Sikap"
        if ($user && $user->hasRole('santri')) {
            return 'Laporan Nilai Sikap';
        }

        if ($user && $user->hasRole('ustadz')) {
            return 'Laporan Nilai Sikap';
        }

        return 'Rekap Nilai Akhlak/Sikap';
    }

    public static function getModelLabel(): string
    {
        return 'Rekap Nilai Sikap';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Rekap Nilai Akhlak & Sikap';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-clipboard-document-list';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Laporan';
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Untuk role SANTRI - SELALU tampilkan nilai sikap siswa sendiri
        if ($user && $user->hasRole('santri')) {
            // Ganti model ke NilaiSikap untuk Santri
            static::$model = \App\Models\NilaiSikap::class;

            $query = parent::getEloquentQuery()->with(['santri', 'kelas', 'semester', 'tahunAjaran']);

            // Jika Santri_id ada, filter hanya nilai sikap siswa ini
            if (!empty($user->santri_id)) {
                $query->where('santri_id', $user->santri_id);
            }

            $query->orderBy('created_at', 'desc');

            return $query;
        }

        // Default: gunakan model Kelas untuk role lain (admin, ustadz)
        static::$model = \App\Models\Kelas::class;

        $query = parent::getEloquentQuery()->with(['waliKelas']);

        // Jika ustadz, tampilkan kelas yang dia walikan atau dia mengajar
        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
            $query->where(function ($q) use ($user) {
                // Kelas yang dia walikan
                $q->where('wali_kelas_id', $user->ustadz_id)
                    // Atau kelas yang dia mengajar (melalui jadwal pelajaran)
                    ->orWhereHas('jadwalPelajarans', function ($jadwal) use ($user) {
                        $jadwal->where('ustadz_id', $user->ustadz_id);
                    });
            });
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Untuk role SANTRI - TAMPILKAN tabel nilai sikap siswa sendiri
        if ($user && $user->hasRole('santri')) {
            return $table
                ->columns([
                    TextColumn::make('semester.tahunAjaran.tahun_ajaran')
                        ->label('Tahun Ajaran')
                        ->sortable(query: function (Builder $query, string $direction): Builder {
                            return $query->orderBy(
                                \App\Models\TahunAjaran::select('tahun_awal')
                                    ->join('semesters', 'semesters.tahun_ajaran_id', '=', 'tahun_ajarans.id')
                                    ->whereColumn('semesters.id', 'nilai_sikaps.semester_id')
                                    ->limit(1),
                                $direction
                            );
                        })
                        ->searchable(),

                    TextColumn::make('semester.semester')
                        ->label('Semester')
                        ->sortable()
                        ->formatStateUsing(fn($state) => 'Semester ' . ucfirst($state)),

                    TextColumn::make('kelas.nama_kelas')
                        ->label('Kelas')
                        ->searchable(),

                    TextColumn::make('disiplin')
                        ->label('Disiplin')
                        ->badge()
                        ->colors([
                            'success' => 'A',
                            'primary' => 'B',
                            'warning' => 'C',
                            'danger' => 'D',
                        ]),

                    TextColumn::make('tanggung_jawab')
                        ->label('Tanggung Jawab')
                        ->badge()
                        ->colors([
                            'success' => 'A',
                            'primary' => 'B',
                            'warning' => 'C',
                            'danger' => 'D',
                        ]),

                    TextColumn::make('kejujuran')
                        ->label('Kejujuran')
                        ->badge()
                        ->colors([
                            'success' => 'A',
                            'primary' => 'B',
                            'warning' => 'C',
                            'danger' => 'D',
                        ]),

                    TextColumn::make('sopan_santun')
                        ->label('Sopan Santun')
                        ->badge()
                        ->colors([
                            'success' => 'A',
                            'primary' => 'B',
                            'warning' => 'C',
                            'danger' => 'D',
                        ]),

                    TextColumn::make('kepedulian')
                        ->label('Kepedulian')
                        ->badge()
                        ->colors([
                            'success' => 'A',
                            'primary' => 'B',
                            'warning' => 'C',
                            'danger' => 'D',
                        ]),

                    TextColumn::make('catatan')
                        ->label('Catatan')
                        ->limit(50)
                        ->toggleable(),
                ])
                ->defaultSort('created_at', 'desc')
                ->emptyStateHeading('Belum ada nilai sikap')
                ->emptyStateDescription('Data nilai sikap Anda akan muncul di sini.')
                ->emptyStateIcon('heroicon-o-clipboard-document-list');
        }

        // Default: tampilkan tabel kelas (untuk admin, ustadz)
        return $table
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
                    ->label('Nama Ustadz')
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
                        // Get semester from filter or use active semester
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

                        // Ustadz hanya lihat kelas yang dia walikan atau dia mengajar
                        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
                            $query->where(function ($q) use ($user) {
                                $q->where('wali_kelas_id', $user->ustadz_id)
                                    ->orWhereHas('jadwalPelajarans', function ($jadwal) use ($user) {
                                        $jadwal->where('ustadz_id', $user->ustadz_id);
                                    });
                            });
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
                Actions\Action::make('lihat_rekap')
                    ->label('Lihat Rekap Nilai Sikap Siswa')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(function (Kelas $record, $livewire) {
                        // Get semester from filter
                        $semesterValue = $livewire->tableFilters['semester']['value'] ?? 'ganjil';
                        $tahunAjaranId = $livewire->tableFilters['tahun_ajaran']['value'] ?? TahunAjaran::where('status', true)->first()?->id;

                        // Find semester record
                        $semester = Semester::where('semester', $semesterValue)
                            ->where('tahun_ajaran_id', $tahunAjaranId)
                            ->first();

                        if (!$semester) {
                            return '#';
                        }

                        return static::getUrl('rekap-siswa', [
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
            'index' => Pages\ListRekapNilaiSikaps::route('/'),
            'rekap-siswa' => Pages\RekapNilaiSikapSiswa::route('/{record}/semester/{semester}'),
            'detail' => Pages\DetailRekapNilaiSikap::route('/{record}/detail'),
        ];
    }
}
