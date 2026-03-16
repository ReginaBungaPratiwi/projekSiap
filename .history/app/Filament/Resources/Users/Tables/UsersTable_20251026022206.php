<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Filament\Resources\Users\Pages;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $recordTitleAttribute = 'name';

    \
    // ✅ Tetap atur urutan menu
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema(UserForm::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(UsersTable::getColumns())
            ->actions(UsersTable::getActions())
            ->bulkActions(UsersTable::getBulkActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // ✅ OPTIONAL: Atur navigation label jika perlu
    public static function getNavigationLabel(): string
    {
        return 'Users';
    }
}