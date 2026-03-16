<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Kelas;
use Illuminate\Auth\Access\HandlesAuthorization;

class RekapNilaiPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RekapNilai');
    }

    public function view(AuthUser $authUser, Kelas $kelas): bool
    {
        return $authUser->can('View:RekapNilai');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RekapNilai');
    }

    public function update(AuthUser $authUser, Kelas $kelas): bool
    {
        return $authUser->can('Update:RekapNilai');
    }

    public function delete(AuthUser $authUser, Kelas $kelas): bool
    {
        return $authUser->can('Delete:RekapNilai');
    }

    public function restore(AuthUser $authUser, Kelas $kelas): bool
    {
        return $authUser->can('Restore:RekapNilai');
    }

    public function forceDelete(AuthUser $authUser, Kelas $kelas): bool
    {
        return $authUser->can('ForceDelete:RekapNilai');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RekapNilai');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RekapNilai');
    }

    public function replicate(AuthUser $authUser, Kelas $kelas): bool
    {
        return $authUser->can('Replicate:RekapNilai');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RekapNilai');
    }
}
