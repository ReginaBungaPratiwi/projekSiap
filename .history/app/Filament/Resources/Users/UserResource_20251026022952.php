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

    // ✅ Atur navigation group ke Filament Shield
    protected static ?string $navigationGroup = 'Filament Shield';
    
    // ✅ Atur urutan dalam group (Roles = 1, Users = 2)
    protected static ?int $navigationSort = 2;

    // ✅ Icon untuk Users
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

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
}