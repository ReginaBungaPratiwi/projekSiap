<?php

namespace App\Providers;

use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Kkm;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\NilaiSikap;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\Ustadz;
use App\Policies\JadwalPelajaranPolicy;
use App\Policies\JamPelajaranPolicy;
use App\Policies\JurusanPolicy;
use App\Policies\KelasPolicy;
use App\Policies\KkmPolicy;
use App\Policies\MapelPolicy;
use App\Policies\NilaiPolicy;
use App\Policies\NilaiSikapPolicy;
use App\Policies\RolePolicy;
use App\Policies\SantriPolicy;
use App\Policies\SemesterPolicy;
use App\Policies\TahunAjaranPolicy;
use App\Policies\UserPolicy;
use App\Policies\UstadzPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Data Master Policies
        Gate::policy(Kelas::class, KelasPolicy::class);
        Gate::policy(Mapel::class, MapelPolicy::class);
        Gate::policy(Jurusan::class, JurusanPolicy::class);
        Gate::policy(TahunAjaran::class, TahunAjaranPolicy::class);
        Gate::policy(Semester::class, SemesterPolicy::class);
        Gate::policy(JadwalPelajaran::class, JadwalPelajaranPolicy::class);
        Gate::policy(JamPelajaran::class, JamPelajaranPolicy::class);
        Gate::policy(Santri::class, SantriPolicy::class);
        Gate::policy(Ustadz::class, UstadzPolicy::class);
        Gate::policy(Kkm::class, KkmPolicy::class);
        Gate::policy(Nilai::class, NilaiPolicy::class);
        Gate::policy(NilaiSikap::class, NilaiSikapPolicy::class);

        // User & Role Policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);

        // Custom permissions untuk InputNilai dan RekapNilai (mapping ke Shield format)
        Gate::define('ViewAny:InputNilai', fn ($user) => $user->hasPermissionTo('ViewAny:InputNilaiResource'));
        Gate::define('View:InputNilai', fn ($user) => $user->hasPermissionTo('View:InputNilaiResource'));
        Gate::define('Create:InputNilai', fn ($user) => $user->hasPermissionTo('Create:InputNilaiResource'));
        Gate::define('Update:InputNilai', fn ($user) => $user->hasPermissionTo('Update:InputNilaiResource'));
        Gate::define('Delete:InputNilai', fn ($user) => $user->hasPermissionTo('Delete:InputNilaiResource'));

        Gate::define('ViewAny:RekapNilai', fn ($user) => $user->hasPermissionTo('ViewAny:RekapNilaiResource'));
        Gate::define('View:RekapNilai', fn ($user) => $user->hasPermissionTo('View:RekapNilaiResource'));
        Gate::define('Create:RekapNilai', fn ($user) => $user->hasPermissionTo('Create:RekapNilaiResource'));
        Gate::define('Update:RekapNilai', fn ($user) => $user->hasPermissionTo('Update:RekapNilaiResource'));
        Gate::define('Delete:RekapNilai', fn ($user) => $user->hasPermissionTo('Delete:RekapNilaiResource'));

        // Gate untuk NilaiSikap (HANYA untuk ustadz wali kelas, bukan pengampu)
        Gate::define('ViewAny:NilaiSikap', function ($user) {
            // Ustadz: HANYA wali kelas yang diizinkan
            if ($user->hasRole('ustadz')) {
                if (!$user->ustadz_id) {
                    return false;
                }
                return Kelas::where('wali_kelas_id', (int) $user->ustadz_id)->exists();
            }
            // Role lain (admin, super_admin) tidak akses resource ini, mereka pakai RekapNilaiSikapResource
            return false;
        });

        Gate::define('View:NilaiSikap', function ($user) {
            if ($user->hasRole('ustadz')) {
                if (!$user->ustadz_id) {
                    return false;
                }
                return Kelas::where('wali_kelas_id', (int) $user->ustadz_id)->exists();
            }
            return false;
        });

        Gate::define('Create:NilaiSikap', function ($user) {
            if ($user->hasRole('ustadz')) {
                if (!$user->ustadz_id) {
                    return false;
                }
                return Kelas::where('wali_kelas_id', (int) $user->ustadz_id)->exists();
            }
            return false;
        });

        Gate::define('Update:NilaiSikap', function ($user) {
            if ($user->hasRole('ustadz')) {
                if (!$user->ustadz_id) {
                    return false;
                }
                return Kelas::where('wali_kelas_id', (int) $user->ustadz_id)->exists();
            }
            return false;
        });

        Gate::define('Delete:NilaiSikap', function ($user) {
            if ($user->hasRole('ustadz')) {
                if (!$user->ustadz_id) {
                    return false;
                }
                return Kelas::where('wali_kelas_id', (int) $user->ustadz_id)->exists();
            }
            return false;
        });

        // Gate untuk RekapNilaiSikap (Laporan - SEMUA ustadz bisa lihat, admin juga)
        Gate::define('ViewAny:RekapNilaiSikap', function ($user) {
            // Admin/super_admin bisa akses
            if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
                return true;
            }
            // Semua ustadz bisa lihat laporan nilai sikap
            if ($user->hasRole('ustadz') && $user->ustadz_id) {
                return true;
            }
            return false;
        });

        Gate::define('View:RekapNilaiSikap', function ($user) {
            if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
                return true;
            }
            if ($user->hasRole('ustadz') && $user->ustadz_id) {
                return true;
            }
            return false;
        });

        // Gate untuk KelasResource (diperlukan karena model Kelas dipakai 2 resource)
        Gate::define('ViewAny:KelasResource', fn ($user) => $user->hasPermissionTo('ViewAny:KelasResource'));
        Gate::define('View:KelasResource', fn ($user) => $user->hasPermissionTo('View:KelasResource'));
        Gate::define('Create:KelasResource', fn ($user) => $user->hasPermissionTo('Create:KelasResource'));
        Gate::define('Update:KelasResource', fn ($user) => $user->hasPermissionTo('Update:KelasResource'));
        Gate::define('Delete:KelasResource', fn ($user) => $user->hasPermissionTo('Delete:KelasResource'));

        // Gate untuk Kelulusan (dengan logic ustadz wali kelas AKHIR saja: 12, 9, 6)
        $kelasAkhirCheck = function ($ustadzId) {
            return Kelas::where('wali_kelas_id', (int) $ustadzId)
                ->where(function ($q) {
                    $q->where('nama_kelas', 'LIKE', '12%')
                      ->orWhere('nama_kelas', 'LIKE', '9%')
                      ->orWhere('nama_kelas', 'LIKE', '6%');
                })
                ->exists();
        };

        Gate::define('ViewAny:Kelulusan', function ($user) use ($kelasAkhirCheck) {
            // Admin/super_admin selalu diizinkan
            if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
                return true;
            }
            // Ustadz: HANYA wali kelas AKHIR (12, 9, 6) yang diizinkan
            if ($user->hasRole('ustadz')) {
                if (!$user->ustadz_id) {
                    return false;
                }
                return $kelasAkhirCheck($user->ustadz_id);
            }
            // Role lain ditolak
            return false;
        });

        Gate::define('View:Kelulusan', function ($user) use ($kelasAkhirCheck) {
            // Admin/super_admin selalu diizinkan
            if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
                return true;
            }
            // Ustadz: HANYA wali kelas AKHIR yang diizinkan
            if ($user->hasRole('ustadz')) {
                if (!$user->ustadz_id) {
                    return false;
                }
                return $kelasAkhirCheck($user->ustadz_id);
            }
            // Role lain ditolak
            return false;
        });

        Gate::define('Create:Kelulusan', function ($user) {
            return $user->hasRole('super_admin') || $user->hasRole('admin');
        });

        Gate::define('Update:Kelulusan', function ($user) use ($kelasAkhirCheck) {
            // Admin/super_admin selalu diizinkan
            if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
                return true;
            }
            // Ustadz: HANYA wali kelas AKHIR yang diizinkan
            if ($user->hasRole('ustadz')) {
                if (!$user->ustadz_id) {
                    return false;
                }
                return $kelasAkhirCheck($user->ustadz_id);
            }
            // Role lain ditolak
            return false;
        });

        Gate::define('Delete:Kelulusan', function ($user) {
            return $user->hasRole('super_admin') || $user->hasRole('admin');
        });

        // Gate untuk Absensi
Gate::define('ViewAny:Absensi', function ($user) {
    if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
        return true;
    }

    if ($user->hasRole('ustadz') && $user->ustadz_id) {
        return true; // atau tambahkan logic khusus kalau mau dibatasi
    }

    return false;
});

Gate::define('View:Absensi', function ($user) {
    if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
        return true;
    }

    if ($user->hasRole('ustadz') && $user->ustadz_id) {
        return true;
    }

    return false;
});

// Create dan Update Absensi: HANYA untuk ustadz, TIDAK untuk admin
Gate::define('Create:Absensi', fn ($user) =>
    $user->hasRole('ustadz') && $user->ustadz_id
);

Gate::define('Update:Absensi', fn ($user) =>
    $user->hasRole('ustadz') && $user->ustadz_id
);

Gate::define('Delete:Absensi', fn ($user) =>
    $user->hasRole('super_admin') ||
    $user->hasRole('admin')
);
    }
}