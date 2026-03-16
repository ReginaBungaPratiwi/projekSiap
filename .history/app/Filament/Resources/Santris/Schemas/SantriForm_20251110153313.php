<?php

namespace App\Filament\Resources\SantriResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;

class SantriForm
{
    public static function getSchema(): array
    {
        return [
            Section::make('Informasi Santri')
                ->schema([
                    TextInput::make('nama_santri')
                        ->label('Nama Santri')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Masukkan nama lengkap santri'),
                        
                    TextInput::make('nis')
                        ->label('NIS')
                        ->required()
                        ->unique('santris', 'nis', ignoreRecord: true)
                        ->maxLength(255)
                        ->placeholder('Contoh: 2401234567')
                        ->validationMessages([
                            'unique' => 'NIS sudah digunakan oleh santri lain.',
                        ]),
                ])
                ->columns(2),
                
            Section::make('Data Akademik')
                ->schema([
                    TextInput::make('kelas')
                        ->required()
                        ->maxLength(10)
                        ->placeholder('Contoh: 7A, 8B, 10TKJ'),
                        
                    Select::make('jenjang')
                        ->options([
                            'SD' => 'SD',
                            'SMP' => 'SMP', 
                            'SMA' => 'SMA',
                            'SMK' => 'SMK',
                        ])
                        ->required()
                        ->placeholder('Pilih jenjang'),
                        
                    Select::make('tahun_masuk')
                        ->options(function () {
                            $years = [];
                            $currentYear = date('Y');
                            for ($year = $currentYear; $year >= 2000; $year--) {
                                $years[$year] = $year;
                            }
                            return $years;
                        })
                        ->required()
                        ->placeholder('Pilih tahun masuk'),
                ])
                ->columns(3),
        ];
    }
}