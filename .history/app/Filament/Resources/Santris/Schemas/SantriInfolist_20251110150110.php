<?php

namespace App\Filament\Resources\SantriResource\Schemas;

use Filament\Infolists;
use Filament\Infolists\Infolist;

class SantriInfolist
{
    public static function schema(): array
    {
        return [
            Infolists\Components\Section::make('Informasi Santri')
                ->schema([
                    Infolists\Components\TextEntry::make('nama_santri')
                        ->label('Nama Santri'),
                        
                    Infolists\Components\TextEntry::make('nis')
                        ->label('NIS'),
                ])
                ->columns(2),
                
            Infolists\Components\Section::make('Data Akademik')
                ->schema([
                    Infolists\Components\TextEntry::make('kelas'),
                        
                    Infolists\Components\TextEntry::make('jenjang'),
                        
                    Infolists\Components\TextEntry::make('tahun_masuk'),
                ])
                ->columns(3),
        ];
    }
}