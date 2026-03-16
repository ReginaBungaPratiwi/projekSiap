<?php

namespace App\Filament\Resources\Jurusans\Schemas;

use Filament\Infolists\Components;

class JurusanInfolist
{
    public static function schema(): array
    {
        return [
            Components\TextEntry::make('nama_jurusan')
                ->label('Nama Jurusan'),

            Components\TextEntry::make('deskripsi')
                ->label('Deskripsi')
                ->columnSpanFull(),

            Components\IconEntry::make('status')
                ->label('Status')
                ->boolean(),
        ];
    }
}