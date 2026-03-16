<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JadwalPelajaran;
use Illuminate\Auth\Access\HandlesAuthorization;

class JadwalPelajaranPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JadwalPelajaranResource');
    }

    public function view(AuthUser $authUser, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $authUser->can('View:JadwalPelajaranResource');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JadwalPelajaranResource');
    }

    public function update(AuthUser $authUser, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $authUser->can('Update:JadwalPelajaranResource');
    }

    public function delete(AuthUser $authUser, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $authUser->can('Delete:JadwalPelajaranResource');
    }

    public function restore(AuthUser $authUser, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $authUser->can('Restore:JadwalPelajaranResource');
    }

    public function forceDelete(AuthUser $authUser, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $authUser->can('ForceDelete:JadwalPelajaranResource');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JadwalPelajaranResource');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JadwalPelajaranResource');
    }

    public function replicate(AuthUser $authUser, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $authUser->can('Replicate:JadwalPelajaranResource');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JadwalPelajaranResource');
    }

}