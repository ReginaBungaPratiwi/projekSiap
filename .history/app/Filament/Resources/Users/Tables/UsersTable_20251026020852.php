<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

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
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}