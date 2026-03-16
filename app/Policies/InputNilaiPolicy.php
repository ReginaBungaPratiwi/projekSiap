<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JadwalPelajaran;
use Illuminate\Auth\Access\HandlesAuthorization;

class InputNilaiPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:InputNilai');
    }

    public function view(AuthUser $authUser, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $authUser->can('View:InputNilai');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:InputNilai');
    }

    public function update(AuthUser $authUser, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $authUser->can('Update:InputNilai');
    }

    public function delete(AuthUser $authUser, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $authUser->can('Delete:InputNilai');
    }

    public function restore(AuthUser $authUser, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $authUser->can('Restore:InputNilai');
    }

    public function forceDelete(AuthUser $authUser, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $authUser->can('ForceDelete:InputNilai');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:InputNilai');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:InputNilai');
    }

    public function replicate(AuthUser $authUser, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $authUser->can('Replicate:InputNilai');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:InputNilai');
    }
}
