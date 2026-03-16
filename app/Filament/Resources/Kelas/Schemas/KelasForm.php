<?php

namespace App\Filament\Resources\Kelas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Ustadz;

class KelasForm
{
    public static function getSchema(): array
    {
        return [
            TextInput::make('nama_kelas')
                ->label('Nama Kelas')
                ->required()
                ->maxLength(50)
                ->placeholder('Contoh: 7A, 8B, 9C, 10TKJ, 11MM, 12AK')
                ->unique(
                    table: 'kelas',
                    column: 'nama_kelas',
                    ignoreRecord: true
                )
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, $set) {
                    if ($state) {
                        $cleaned = strtoupper(trim($state));
                        $set('nama_kelas', $cleaned);
                    }
                }),

            Select::make('jenjang')
                ->label('Jenjang')
                ->required()
                ->options([
                    'SD' => 'SD',
                    'SMP' => 'SMP', 
                    'SMA' => 'SMA',
                    'SMK' => 'SMK',
                ])
                ->placeholder('Pilih Jenjang')
                ->native(false)
                ->live(),

            Select::make('jurusan_id')
                ->label('Jurusan')
                ->required()
                ->relationship('jurusan', 'nama_jurusan', function ($query) {
                    return $query->where('status', true);
                })
                ->searchable()
                ->preload()
                ->native(false)
                ->placeholder('Pilih Jurusan')
                ->helperText('Pilih jurusan dari data master jurusan'),

            // ✅ PERBAIKAN: Filter hanya ustadz aktif
            Select::make('wali_kelas_id')
                ->label('Wali Kelas')
                ->relationship('waliKelas', 'nama', function ($query) {
                    return $query->where('status_aktif', true);
                })
                ->searchable()
                ->preload()
                ->native(false)
                ->placeholder('Pilih Wali Kelas')
                ->helperText('Pilih ustadz sebagai wali kelas dari data master ustadz'),
        ];
    }
}