<?php

namespace App\Filament\Resources\SantriResource\Schemas;

use Filament\Forms;
use Filament\Forms\Form;

class SantriForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\Section::make('Informasi Santri')
                ->schema([
                    Forms\Components\TextInput::make('nama_santri')
                        ->label('Nama Santri')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Masukkan nama lengkap santri'),
                        
                    Forms\Components\TextInput::make('nis')
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
                
            Forms\Components\Section::make('Data Akademik')
                ->schema([
                    Forms\Components\TextInput::make('kelas')
                        ->required()
                        ->maxLength(10)
                        ->placeholder('Contoh: 7A, 8B, 10TKJ'),
                        
                    Forms\Components\Select::make('jenjang')
                        ->options([
                            'SD' => 'SD',
                            'SMP' => 'SMP', 
                            'SMA' => 'SMA',
                            'SMK' => 'SMK',
                        ])
                        ->required()
                        ->placeholder('Pilih jenjang'),
                        
                    Forms\Components\Select::make('tahun_masuk')
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