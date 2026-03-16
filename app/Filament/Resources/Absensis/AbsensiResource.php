<?php

namespace App\Filament\Resources\Absensis;

use App\Filament\Resources\Absensis\Pages;
use App\Models\Kelas;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * @property User $user
 */
class AbsensiResource extends Resource
{
    protected static ?string $model = Kelas::class;
    protected static ?string $slug = 'absensi';

    /**
     * Cek apakah user memiliki permission Absensi (kompatibel dengan Shield)
     */
    private static function hasAbsensiPermission(string $action = 'viewAny'): bool
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
        // viewAny -> view_any_absensi, create -> create_absensi, dll
        $permissionMap = [
            'viewAny' => 'view_any_absensi',
            'view' => 'view_absensi',
            'create' => 'create_absensi',
            'edit' => 'edit_absensi',
            'update' => 'update_absensi',
            'delete' => 'delete_absensi',
        ];

        $permissionName = $permissionMap[$action] ?? $action . '_absensi';

        // Cek apakah user punya permission
        try {
            if ($user->can($permissionName)) {
                return true;
            }
        } catch (\Exception $e) {
            // Permission tidak ada, lanjut ke cek berikutnya
        }

        // Fallback: cek apakah user punya role yang дает akses
        // Jika user punya role (bukan super_admin/admin), cek apakah role tersebut punya permission terkait absensi
        /** @var \Illuminate\Database\Eloquent\Collection<int, Role> $roles */
        $roles = $user->roles();

        if ($roles->count() > 0) {
            // Cek permission via role
            /** @var \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions */
            $permissions = $user->getAllPermissions();
            $permissionNames = $permissions->pluck('name')->toArray();

            // Cek jika ada permission yang mengandung 'absensi'
            foreach ($permissionNames as $perm) {
                if (str_contains(strtolower($perm), 'absensi')) {
                    return true;
                }
            }
        }

        return false;
    }

    // ✅ TAMBAHKAN INI untuk mengatur urutan
    public static function canViewAny(): bool
    {
        return self::hasAbsensiPermission('viewAny');
    }

    public static function canView($record): bool
    {
        return self::hasAbsensiPermission('view');
    }

    public static function canCreate(): bool
    {
        return self::hasAbsensiPermission('create');
    }

    public static function canEdit($record): bool
    {
        return self::hasAbsensiPermission('edit') || self::hasAbsensiPermission('update');
    }

    public static function canDelete($record): bool
    {
        return self::hasAbsensiPermission('delete');
    }

    public static function getNavigationLabel(): string
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Jika role adalah Santi, ubah label menu menjadi "Rekap Absensi"
        if ($user && $user->hasRole('santri')) {
            return 'Rekap Absensi';
        }

        return 'Absensi';
    }

    public static function getModelLabel(): string
    {
        return 'Absensi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Absensi Santri';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-calendar-days';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Penilaian Santri';
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Untuk role SANTRI - SELALU tampilkan data absensi siswa sendiri (tanpa fallback ke Kelas)
        if ($user && $user->hasRole('santri')) {
            // Ganti model ke Absensi untuk sanksi
            static::$model = \App\Models\Absensi::class;

            $query = parent::getEloquentQuery();

            // Jika Santi_id ada, filter hanya absensi siswa ini
            if (!empty($user->santri_id)) {
                $query->where('santri_id', $user->santri_id);
            }

            $query->orderBy('tanggal', 'desc');

            return $query;
        }

        // Default: gunakan model Kelas untuk role lain (admin, ustadz)
        static::$model = \App\Models\Kelas::class;

        $query = parent::getEloquentQuery();

        // Filter berdasarkan role user
        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
            // Ustadz melihat kelas yang:
            // 1. Dia ajar (via jadwal pelajaran), ATAU
            // 2. Dia jadi wali kelas

            $ustadz = \App\Models\Ustadz::find($user->ustadz_id);

            if ($ustadz) {
                $kelasUstadz = $ustadz->getKelasUntukAbsensi()->pluck('id')->toArray();

                // Jika ada kelas yang bisa di-input, filter dengan whereIn
                if (!empty($kelasUstadz)) {
                    $query->whereIn('id', $kelasUstadz);
                } else {
                    // Tidak ada kelas yang bisa di-input, return empty result
                    $query->whereIn('id', [0]); // Pakai [0] agar tidak error
                }
            }
        }
        // Admin bisa melihat semua kelas (tanpa filter)

        return $query;
    }

    public static function table(Table $table): Table
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Untuk role SANTRI - SELALU tampilkan tabel absensi siswa sendiri (tanpa fallback ke tabel Kelas)
        if ($user && $user->hasRole('santri')) {
            return $table
                ->columns([
                    TextColumn::make('tanggal')
                        ->label('Tanggal')
                        ->date('d-m-Y')
                        ->sortable(),

                    TextColumn::make('kelas.nama_kelas')
                        ->label('Kelas')
                        ->searchable(),

                    TextColumn::make('status')
                        ->label('Status')
                        ->badge()
                        ->colors([
                            'success' => 'hadir',
                            'warning' => 'izin',
                            'danger' => 'sakit',
                            'gray' => 'alpha',
                        ]),

                    TextColumn::make('keterangan')
                        ->label('Keterangan')
                        ->limit(50)
                        ->toggleable(),
                ])
                ->defaultSort('tanggal', 'desc')
                ->emptyStateHeading('Belum ada absensi')
                ->emptyStateDescription('Data absensi Anda akan muncul di sini.')
                ->emptyStateIcon('heroicon-o-calendar-days');
        }

        // Default: tampilkan tabel kelas (untuk admin, ustadz)
        return $table
            ->columns([
                TextColumn::make('nama_kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('jurusan.nama_jurusan')
                    ->label('Jurusan')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('waliKelas.nama')
                    ->label('Wali Kelas')
                    ->searchable()
                    ->toggleable(),

                BadgeColumn::make('jumlah_santri')
                    ->label('Jumlah Santri')
                    ->getStateUsing(fn($record) => $record->santris()->count())
                    ->colors(['primary'])
                    ->icon('heroicon-o-users'),

                BadgeColumn::make('status_absensi')
                    ->label('Absensi Hari Ini')
                    ->getStateUsing(function ($record) {
                        $tanggal = now()->toDateString();
                        $totalSantri = $record->santris()->count();

                        if ($totalSantri == 0) {
                            return 'Tidak Ada Santri';
                        }

                        $sudahAbsen = $record->absensis()
                            ->whereDate('tanggal', $tanggal)
                            ->count();

                        if ($sudahAbsen == 0) {
                            return 'Belum Input';
                        } elseif ($sudahAbsen < $totalSantri) {
                            return $sudahAbsen . '/' . $totalSantri;
                        } else {
                            return 'Lengkap';
                        }
                    })
                    ->colors([
                        'danger' => 'Belum Input',
                        'warning' => fn($state) => str_contains($state, '/'),
                        'success' => 'Lengkap',
                        'gray' => 'Tidak Ada Santri',
                    ]),
            ])
            ->actions([
                // Action untuk melihat detail absensi (READ ONLY)
                Actions\Action::make('detail')
                    ->label('Lihat Absensi')
                    ->icon('heroicon-o-eye')
                    ->url(
                        fn(Kelas $record) =>
                        static::getUrl('detail', [
                            'record' => $record->id
                        ])
                    )
                    ->color('info')
                    ->openUrlInNewTab(false),

                // Action untuk input/ubah absensi
                Actions\Action::make('input')
                    ->label('Input Absensi')
                    ->icon('heroicon-o-pencil-square')
                    ->url(
                        fn(Kelas $record) =>
                        static::getUrl('input', [
                            'record' => $record->id
                        ])
                    )
                    ->color('success')
                    ->openUrlInNewTab(false)
                    ->visible(function () {
                        $user = Auth::user();
                        if (!$user) return false;

                        // ✅ PERBAIKAN: Cek langsung ke tabel ustadz tanpa menggunakan hasRole()
                        // Cek apakah user memiliki ustadz_id dan ustadz tersebut ada di database
                        if ($user->ustadz_id) {
                            return \App\Models\Ustadz::where('id', $user->ustadz_id)->exists();
                        }

                        return false;
                    }),
            ])
            ->bulkActions([])
            ->defaultSort('nama_kelas')
            ->emptyStateHeading('Tidak ada kelas')
            ->emptyStateDescription('Belum ada data kelas yang tersedia.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbsensis::route('/'),
            'input' => Pages\InputAbsensi::route('/{record}/input'),
            'detail' => Pages\DetailAbsensi::route('/{record}/detail'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
            $ustadz = \App\Models\Ustadz::find($user->ustadz_id);
            if ($ustadz) {
                $totalKelas = $ustadz->getKelasUntukAbsensi()->count();
                return $totalKelas > 0 ? (string) $totalKelas : null;
            }
        }

        // Admin melihat total semua kelas
        if ($user && ($user->hasRole('admin') || $user->hasRole('super_admin'))) {
            $totalKelas = Kelas::count();
            return $totalKelas > 0 ? (string) $totalKelas : null;
        }

        return null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
}
