<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Ustadz;
use Illuminate\Auth\Access\HandlesAuthorization;

class UstadzPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UstadzResource');
    }

    public function view(AuthUser $authUser, Ustadz $ustadz): bool
    {
        return $authUser->can('View:UstadzResource');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UstadzResource');
    }

    public function update(AuthUser $authUser, Ustadz $ustadz): bool
    {
        return $authUser->can('Update:UstadzResource');
    }

    public function delete(AuthUser $authUser, Ustadz $ustadz): bool
    {
        return $authUser->can('Delete:UstadzResource');
    }

    public function restore(AuthUser $authUser, Ustadz $ustadz): bool
    {
        return $authUser->can('Restore:UstadzResource');
    }

    public function forceDelete(AuthUser $authUser, Ustadz $ustadz): bool
    {
        return $authUser->can('ForceDelete:UstadzResource');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UstadzResource');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UstadzResource');
    }

    public function replicate(AuthUser $authUser, Ustadz $ustadz): bool
    {
        return $authUser->can('Replicate:UstadzResource');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UstadzResource');
    }

}