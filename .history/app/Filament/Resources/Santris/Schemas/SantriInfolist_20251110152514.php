<?php

namespace App\Filament\Resources\SantriResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;

class SantriInfolist
{
    public static function getSchema(): array
    {
        return [
            Section::make('Informasi Santri')
                ->schema([
                    TextEntry::make('nama_santri')
                        ->label('Nama Santri'),
                        
                    TextEntry::make('nis')
                        ->label('NIS'),
                ])
                ->columns(2),
                
            Section::make('Data Akademik')
                ->schema([
                    TextEntry::make('kelas'),
                        
                    TextEntry::make('jenjang'),
                        
                    TextEntry::make('tahun_masuk'),
                ])
                ->columns(3),
        ];
    }
}