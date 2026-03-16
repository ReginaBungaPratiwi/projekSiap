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
                
            // ✅ Password untuk create user
            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required()
                ->maxLength(255)
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->hiddenOn('edit'),
                
            // ✅ FIX: Ganti password untuk edit user - SIMPLE VERSION
            TextInput::make('password') // ⚠️ PAKAI NAMA YANG SAMA 'password'
                ->label('Reset Password')
                ->password()
                ->maxLength(255)
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn ($state) => filled($state))
                ->hiddenOn('create')
                ->helperText('Kosongkan jika tidak ingin mengganti password'),
                
            Select::make('roles')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->searchable(),
        ];
    }
}