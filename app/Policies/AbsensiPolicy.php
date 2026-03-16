<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Absensi;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * @property AuthUser $authUser
 */
class AbsensiPolicy
{
    use HandlesAuthorization;

    /**
     * Cek apakah user memiliki permission Absensi (kompatibel dengan Shield)
     */
    private function hasAbsensiPermission(AuthUser $authUser, string $action = 'viewAny'): bool
    {
        if (!$authUser) {
            return false;
        }

        // Super Admin bisa akses semuanya
        if ($authUser->hasRole('super_admin')) {
            return true;
        }

        // Admin juga bisa akses
        if ($authUser->hasRole('admin')) {
            return true;
        }

        // Format permission yang benar untuk Shield: snake_case
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
            if ($authUser->can($permissionName)) {
                return true;
            }
        } catch (\Exception $e) {
            // Permission tidak ada, lanjut ke cek berikutnya
        }

        // Fallback: cek permission yang mengandung 'absensi'
        /** @var \Illuminate\Database\Eloquent\Collection<int, Role> $roles */
        $roles = $authUser->roles();

        if ($roles->count() > 0) {
            /** @var \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions */
            $permissions = $authUser->getAllPermissions();
            $permissionNames = $permissions->pluck('name')->toArray();

            foreach ($permissionNames as $perm) {
                if (str_contains(strtolower($perm), 'absensi')) {
                    return true;
                }
            }
        }

        return false;
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $this->hasAbsensiPermission($authUser, 'viewAny');
    }

    public function view(AuthUser $authUser, Absensi $absensi): bool
    {
        return $this->hasAbsensiPermission($authUser, 'view');
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->hasAbsensiPermission($authUser, 'create');
    }

    public function update(AuthUser $authUser, Absensi $absensi): bool
    {
        return $this->hasAbsensiPermission($authUser, 'update') ||
            $this->hasAbsensiPermission($authUser, 'edit');
    }

    public function delete(AuthUser $authUser, Absensi $absensi): bool
    {
        return $this->hasAbsensiPermission($authUser, 'delete');
    }

    public function restore(AuthUser $authUser, Absensi $absensi): bool
    {
        return false;
    }

    public function forceDelete(AuthUser $authUser, Absensi $absensi): bool
    {
        return false;
    }
}
