<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Santri;
use Illuminate\Auth\Access\HandlesAuthorization;

class SantriPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SantriResource');
    }

    public function view(AuthUser $authUser, Santri $santri): bool
    {
        return $authUser->can('View:SantriResource');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SantriResource');
    }

    public function update(AuthUser $authUser, Santri $santri): bool
    {
        return $authUser->can('Update:SantriResource');
    }

    public function delete(AuthUser $authUser, Santri $santri): bool
    {
        return $authUser->can('Delete:SantriResource');
    }

    public function restore(AuthUser $authUser, Santri $santri): bool
    {
        return $authUser->can('Restore:SantriResource');
    }

    public function forceDelete(AuthUser $authUser, Santri $santri): bool
    {
        return $authUser->can('ForceDelete:SantriResource');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SantriResource');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SantriResource');
    }

    public function replicate(AuthUser $authUser, Santri $santri): bool
    {
        return $authUser->can('Replicate:SantriResource');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SantriResource');
    }

}