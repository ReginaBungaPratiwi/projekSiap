<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TahunAjaran;
use Illuminate\Auth\Access\HandlesAuthorization;

class TahunAjaranPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TahunAjaranResource');
    }

    public function view(AuthUser $authUser, TahunAjaran $tahunAjaran): bool
    {
        return $authUser->can('View:TahunAjaranResource');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TahunAjaranResource');
    }

    public function update(AuthUser $authUser, TahunAjaran $tahunAjaran): bool
    {
        return $authUser->can('Update:TahunAjaranResource');
    }

    public function delete(AuthUser $authUser, TahunAjaran $tahunAjaran): bool
    {
        return $authUser->can('Delete:TahunAjaranResource');
    }

    public function restore(AuthUser $authUser, TahunAjaran $tahunAjaran): bool
    {
        return $authUser->can('Restore:TahunAjaranResource');
    }

    public function forceDelete(AuthUser $authUser, TahunAjaran $tahunAjaran): bool
    {
        return $authUser->can('ForceDelete:TahunAjaranResource');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TahunAjaranResource');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TahunAjaranResource');
    }

    public function replicate(AuthUser $authUser, TahunAjaran $tahunAjaran): bool
    {
        return $authUser->can('Replicate:TahunAjaranResource');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TahunAjaranResource');
    }

}