<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Ustadz;
use App\Models\Santri;
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
                ->label('Password')
                ->password()
                ->revealable()
                ->required()
                ->maxLength(255)
                ->dehydrateStateUsing(fn($state) => Hash::make($state))
                ->dehydrated(fn($state) => filled($state))
                ->hiddenOn('edit'),

            TextInput::make('password')
                ->label('Reset Password')
                ->password()
                ->revealable()
                ->maxLength(255)
                ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn($state) => filled($state))
                ->hiddenOn('create')
                ->helperText('Kosongkan jika tidak ingin mengganti password'),

            Select::make('roles')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->searchable()
                ->live(),

            // Hubungkan User dengan Ustadz
            Select::make('ustadz_id')
                ->label('Data Ustadz')
                ->options(function () {
                    return Ustadz::where('status_aktif', true)
                        ->orderBy('nama')
                        ->pluck('nama', 'id');
                })
                ->searchable()
                ->preload()
                ->placeholder('Pilih Ustadz (opsional)')
                ->helperText('Hubungkan user ini dengan data Ustadz')
                ->disabled(fn($get) => !empty($get('santri_id'))),

            // Hubungkan User dengan Santi
            Select::make('santri_id')
                ->label('Data Santri')
                ->options(function () {
                    return Santri::orderBy('nama_lengkap')
                        ->pluck('nama_lengkap', 'id');
                })
                ->searchable()
                ->preload()
                ->placeholder('Pilih Santi (opsional)')
                ->helperText('Hubungkan user ini dengan data Santi')
                ->disabled(fn($get) => !empty($get('ustadz_id'))),
        ];
    }
}
