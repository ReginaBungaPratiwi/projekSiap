<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Mapel;
use Illuminate\Auth\Access\HandlesAuthorization;

class MapelPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MapelResource');
    }

    public function view(AuthUser $authUser, Mapel $mapel): bool
    {
        return $authUser->can('View:MapelResource');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MapelResource');
    }

    public function update(AuthUser $authUser, Mapel $mapel): bool
    {
        return $authUser->can('Update:MapelResource');
    }

    public function delete(AuthUser $authUser, Mapel $mapel): bool
    {
        return $authUser->can('Delete:MapelResource');
    }

    public function restore(AuthUser $authUser, Mapel $mapel): bool
    {
        return $authUser->can('Restore:MapelResource');
    }

    public function forceDelete(AuthUser $authUser, Mapel $mapel): bool
    {
        return $authUser->can('ForceDelete:MapelResource');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MapelResource');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MapelResource');
    }

    public function replicate(AuthUser $authUser, Mapel $mapel): bool
    {
        return $authUser->can('Replicate:MapelResource');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MapelResource');
    }

}