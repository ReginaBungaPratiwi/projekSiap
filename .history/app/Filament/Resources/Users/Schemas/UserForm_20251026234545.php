<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Hash;

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
                
            // ✅ Password untuk create user (hanya muncul di create)
            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required()
                ->maxLength(255)
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->hiddenOn('edit'),
                
            // ✅ TAMBAH INI - Ganti password untuk edit user (hanya muncul di edit)
            TextInput::make('new_password')
                ->label('Ganti Password')
                ->password()
                ->maxLength(255)
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn ($state) => filled($state))
                ->hiddenOn('create'),
                
            Select::make('roles')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->searchable(),
        ];
    }
}