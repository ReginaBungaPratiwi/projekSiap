<?php

namespace App\Filament\Resources\Jurusans\Schemas;

use Filament\Forms\Components;

class JurusanForm
{
    public static function schema(): array
    {
        return [
            Components\TextInput::make('nama_jurusan')
                ->required()
                ->maxLength(255)
                ->label('Nama Jurusan')
                ->placeholder('Masukkan nama jurusan...')
                ->columnSpan(1),
            
            Components\Toggle::make('status')
                ->label('Status Aktif')
                ->helperText('Jika diaktifkan, jurusan ini akan tersedia untuk dipilih')
                ->default(true)
                ->columnSpan(1),
            
            Components\Textarea::make('deskripsi')
                ->required()
                ->maxLength(65535)
                ->label('Deskripsi')
                ->placeholder('Masukkan deskripsi jurusan...')
                ->rows(4)
                ->columnSpanFull(),
        ];
    }
}