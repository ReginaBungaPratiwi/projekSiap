<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class UsersTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('name')
                ->searchable(),
                
            TextColumn::make('email')
                ->searchable(),
                
            TextColumn::make('roles.name')
                ->badge()
                ->searchable(),
                
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->visible(fn ($record): bool => ! static::isAdminUser($record))
                ->disabled(fn ($record): bool => static::isAdminUser($record)),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make()
                    ->visible(fn ($records): bool => ! $records->contains(fn ($record) => static::isAdminUser($record)))
                    ->disabled(fn ($records): bool => $records->contains(fn ($record) => static::isAdminUser($record))),
            ]),
        ];
    }

    protected static function isAdminUser($record): bool
    {
        $roles = collect($record->getRoleNames())
            ->map(fn (string $name): string => strtolower($name));
        return $roles->contains('admin') || $roles->contains('super_admin');
    }
}