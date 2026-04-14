<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        $actions = array_values(parent::getHeaderActions());

        $roles = collect($this->record->getRoleNames())->map(fn (string $name): string => strtolower($name));
        if ($roles->contains('admin') || $roles->contains('super_admin')) {
            return array_values(array_filter($actions, fn ($action) => ! ($action instanceof DeleteAction)));
        }

        return $actions;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
