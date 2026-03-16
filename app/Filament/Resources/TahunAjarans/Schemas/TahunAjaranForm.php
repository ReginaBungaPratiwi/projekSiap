<?php

namespace App\Filament\Resources\TahunAjarans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class TahunAjaranForm
{
    public static function getSchema(): array
    {
        return [
            TextInput::make('tahun_awal')
                ->label('Tahun Ajaran Awal')
                ->required()
                ->numeric()
                ->length(4)
                ->minLength(4)
                ->maxLength(4)
                ->placeholder('Contoh: 2024')
                ->helperText('Masukkan tahun awal periode ajaran')
                ->unique(
                    table: 'tahun_ajarans', 
                    column: 'tahun_awal',
                    ignoreRecord: true
                )
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, $set) {
                    if ($state) {
                        $set('tahun_awal', trim($state));
                    }
                }),

            TextInput::make('tahun_akhir')
                ->label('Tahun Ajaran Akhir')
                ->required()
                ->numeric()
                ->length(4)
                ->minLength(4)
                ->maxLength(4)
                ->placeholder('Contoh: 2025')
                ->helperText('Masukkan tahun akhir periode ajaran')
                ->unique(
                    table: 'tahun_ajarans',
                    column: 'tahun_akhir', 
                    ignoreRecord: true
                )
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, $set) {
                    if ($state) {
                        $set('tahun_akhir', trim($state));
                    }
                }),

            Toggle::make('status')
                ->label('Status Aktif')
                ->reactive()
                ->helperText('Jika diaktifkan, tahun ajaran lain akan otomatis dinonaktifkan')
                ->afterStateUpdated(function ($state) {
                    if ($state === true) {
                        \App\Models\TahunAjaran::where('status', true)
                            ->update(['status' => false]);
                    }
                }),
        ];
    }
}
