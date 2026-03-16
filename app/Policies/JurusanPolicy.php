<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Jurusan;
use Illuminate\Auth\Access\HandlesAuthorization;

class JurusanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JurusanResource');
    }

    public function view(AuthUser $authUser, Jurusan $jurusan): bool
    {
        return $authUser->can('View:JurusanResource');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JurusanResource');
    }

    public function update(AuthUser $authUser, Jurusan $jurusan): bool
    {
        return $authUser->can('Update:JurusanResource');
    }

    public function delete(AuthUser $authUser, Jurusan $jurusan): bool
    {
        return $authUser->can('Delete:JurusanResource');
    }

    public function restore(AuthUser $authUser, Jurusan $jurusan): bool
    {
        return $authUser->can('Restore:JurusanResource');
    }

    public function forceDelete(AuthUser $authUser, Jurusan $jurusan): bool
    {
        return $authUser->can('ForceDelete:JurusanResource');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JurusanResource');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JurusanResource');
    }

    public function replicate(AuthUser $authUser, Jurusan $jurusan): bool
    {
        return $authUser->can('Replicate:JurusanResource');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JurusanResource');
    }

}