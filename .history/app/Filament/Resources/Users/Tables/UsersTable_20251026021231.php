<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction; // ✅ Import actions
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

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
            EditAction::make(), // ✅ Langsung pakai class
            DeleteAction::make(),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }
}