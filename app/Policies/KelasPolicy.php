<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Auth\Access\HandlesAuthorization;

class KelasPolicy
{
    use HandlesAuthorization;

    /**
     * Run before all other authorization checks
     * Return true to allow, false to deny, null to continue to other checks
     */
    public function before(User $user, string $ability): ?bool
    {
        // Admin/super_admin selalu diizinkan
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return true;
        }

        // Ustadz wali kelas diizinkan untuk viewAny, view, update
        if ($user->hasRole('ustadz') && $user->ustadz_id) {
            $isWaliKelas = Kelas::where('wali_kelas_id', (int) $user->ustadz_id)->exists();
            if ($isWaliKelas && in_array($ability, ['viewAny', 'view', 'update'])) {
                return true;
            }
        }

        // Return null untuk melanjutkan ke policy method lainnya
        return null;
    }

    /**
     * Check if user is ustadz wali kelas
     */
    private function isUstadzWaliKelas(User $user, ?Kelas $kelas = null): bool
    {
        if (!$user->hasRole('ustadz') || !$user->ustadz_id) {
            return false;
        }

        // Jika kelas diberikan, cek apakah ustadz adalah wali kelas dari kelas tersebut
        if ($kelas) {
            return (int) $kelas->wali_kelas_id === (int) $user->ustadz_id;
        }

        // Jika tidak ada kelas, cek apakah ustadz adalah wali kelas dari minimal 1 kelas
        return Kelas::where('wali_kelas_id', (int) $user->ustadz_id)->exists();
    }

    public function viewAny(User $user): bool
    {
        // Admin/super_admin bisa akses
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return true;
        }

        // Ustadz wali kelas bisa akses
        if ($this->isUstadzWaliKelas($user)) {
            return true;
        }

        return $user->can('ViewAny:KelulusanResource');
    }

    public function view(User $user, Kelas $kelas): bool
    {
        // Admin/super_admin bisa view semua
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return true;
        }

        // Ustadz bisa view jika dia wali kelas dari kelas ini
        if ($this->isUstadzWaliKelas($user, $kelas)) {
            return true;
        }

        return $user->can('View:KelulusanResource');
    }

    public function create(User $user): bool
    {
        return $user->can('Create:KelulusanResource');
    }

    public function update(User $user, Kelas $kelas): bool
    {
        // Admin/super_admin bisa update semua
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return true;
        }

        // Ustadz bisa update jika dia wali kelas dari kelas ini
        if ($this->isUstadzWaliKelas($user, $kelas)) {
            return true;
        }

        return $user->can('Update:KelulusanResource');
    }

    public function delete(User $user, Kelas $kelas): bool
    {
        return $user->can('Delete:KelulusanResource');
    }

    public function restore(User $user, Kelas $kelas): bool
    {
        return $user->can('Restore:KelulusanResource');
    }

    public function forceDelete(User $user, Kelas $kelas): bool
    {
        return $user->can('ForceDelete:KelulusanResource');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:KelulusanResource');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:KelulusanResource');
    }

    public function replicate(User $user, Kelas $kelas): bool
    {
        return $user->can('Replicate:KelulusanResource');
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:KelulusanResource');
    }
}