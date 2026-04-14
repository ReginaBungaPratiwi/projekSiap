<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages\EditRole as BaseEditRole;
use Filament\Actions\DeleteAction;

class EditRole extends BaseEditRole
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        $actions = array_values(parent::getActions());

        if (strtolower(str_replace('_', ' ', $this->record->name)) === 'super admin') {
            return array_values(array_filter($actions, fn ($action) => ! ($action instanceof DeleteAction)));
        }

        return $actions;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
