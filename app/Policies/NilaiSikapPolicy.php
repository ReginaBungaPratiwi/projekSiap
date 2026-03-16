<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\NilaiSikap;
use Illuminate\Auth\Access\HandlesAuthorization;

class NilaiSikapPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RekapNilaiSikapResource');
    }

    public function view(AuthUser $authUser, NilaiSikap $nilaiSikap): bool
    {
        return $authUser->can('View:RekapNilaiSikapResource');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RekapNilaiSikapResource');
    }

    public function update(AuthUser $authUser, NilaiSikap $nilaiSikap): bool
    {
        return $authUser->can('Update:RekapNilaiSikapResource');
    }

    public function delete(AuthUser $authUser, NilaiSikap $nilaiSikap): bool
    {
        return $authUser->can('Delete:RekapNilaiSikapResource');
    }

    public function restore(AuthUser $authUser, NilaiSikap $nilaiSikap): bool
    {
        return $authUser->can('Restore:RekapNilaiSikapResource');
    }

    public function forceDelete(AuthUser $authUser, NilaiSikap $nilaiSikap): bool
    {
        return $authUser->can('ForceDelete:RekapNilaiSikapResource');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RekapNilaiSikapResource');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RekapNilaiSikapResource');
    }

    public function replicate(AuthUser $authUser, NilaiSikap $nilaiSikap): bool
    {
        return $authUser->can('Replicate:RekapNilaiSikapResource');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RekapNilaiSikapResource');
    }

}