<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Kkm;
use Illuminate\Auth\Access\HandlesAuthorization;

class KkmPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:KkmResource');
    }

    public function view(AuthUser $authUser, Kkm $kkm): bool
    {
        return $authUser->can('View:KkmResource');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:KkmResource');
    }

    public function update(AuthUser $authUser, Kkm $kkm): bool
    {
        return $authUser->can('Update:KkmResource');
    }

    public function delete(AuthUser $authUser, Kkm $kkm): bool
    {
        return $authUser->can('Delete:KkmResource');
    }

    public function restore(AuthUser $authUser, Kkm $kkm): bool
    {
        return $authUser->can('Restore:KkmResource');
    }

    public function forceDelete(AuthUser $authUser, Kkm $kkm): bool
    {
        return $authUser->can('ForceDelete:KkmResource');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:KkmResource');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:KkmResource');
    }

    public function replicate(AuthUser $authUser, Kkm $kkm): bool
    {
        return $authUser->can('Replicate:KkmResource');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:KkmResource');
    }

}