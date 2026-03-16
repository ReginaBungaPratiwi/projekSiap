<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JamPelajaran;
use Illuminate\Auth\Access\HandlesAuthorization;

class JamPelajaranPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JamPelajaranResource');
    }

    public function view(AuthUser $authUser, JamPelajaran $jamPelajaran): bool
    {
        return $authUser->can('View:JamPelajaranResource');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JamPelajaranResource');
    }

    public function update(AuthUser $authUser, JamPelajaran $jamPelajaran): bool
    {
        return $authUser->can('Update:JamPelajaranResource');
    }

    public function delete(AuthUser $authUser, JamPelajaran $jamPelajaran): bool
    {
        return $authUser->can('Delete:JamPelajaranResource');
    }

    public function restore(AuthUser $authUser, JamPelajaran $jamPelajaran): bool
    {
        return $authUser->can('Restore:JamPelajaranResource');
    }

    public function forceDelete(AuthUser $authUser, JamPelajaran $jamPelajaran): bool
    {
        return $authUser->can('ForceDelete:JamPelajaranResource');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JamPelajaranResource');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JamPelajaranResource');
    }

    public function replicate(AuthUser $authUser, JamPelajaran $jamPelajaran): bool
    {
        return $authUser->can('Replicate:JamPelajaranResource');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JamPelajaranResource');
    }

}