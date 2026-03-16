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
                
            TextInput::make('password')
                ->password()
                ->required()
                ->maxLength(255)
                ->hiddenOn('edit')
                ->dehydrateStateUsing(fn ($state) => Hash::make($state)) // ✅ Hash password
                ->visible(fn ($livewire) => $livewire instanceof \App\Filament\Resources\Users\Pages\CreateUser),
                
            Select::make('roles')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->searchable(),
        ];
    }
}