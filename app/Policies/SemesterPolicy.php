<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Semester;
use Illuminate\Auth\Access\HandlesAuthorization;

class SemesterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SemesterResource');
    }

    public function view(AuthUser $authUser, Semester $semester): bool
    {
        return $authUser->can('View:SemesterResource');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SemesterResource');
    }

    public function update(AuthUser $authUser, Semester $semester): bool
    {
        return $authUser->can('Update:SemesterResource');
    }

    public function delete(AuthUser $authUser, Semester $semester): bool
    {
        return $authUser->can('Delete:SemesterResource');
    }

    public function restore(AuthUser $authUser, Semester $semester): bool
    {
        return $authUser->can('Restore:SemesterResource');
    }

    public function forceDelete(AuthUser $authUser, Semester $semester): bool
    {
        return $authUser->can('ForceDelete:SemesterResource');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SemesterResource');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SemesterResource');
    }

    public function replicate(AuthUser $authUser, Semester $semester): bool
    {
        return $authUser->can('Replicate:SemesterResource');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SemesterResource');
    }

}