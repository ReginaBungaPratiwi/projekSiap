<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class UserForm
{
    public static function getSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
                
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
                
            TextInput::make('password')
                ->password()
                ->required()
                ->maxLength(255)
                ->hiddenOn('edit'), // Password hanya di create
                
            Select::make('roles')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->searchable(),
        ];
    }
}