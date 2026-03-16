<?php
// app/Filament/Resources/Mapels/Schemas/MapelForm.php

namespace App\Filament\Resources\Mapels\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Ustadz;

class MapelForm
{
    public static function getSchema(): array
    {
        return [
            TextInput::make('nama_mapel')
                ->label('Nama Mapel')
                ->required()
                ->maxLength(100)
                ->placeholder('Contoh: Fiqih, Matematika, Bahasa Arab, Jaringan Komputer, Akuntansi Dasar')
                ->helperText('Nama mata pelajaran. Maksimal 100 karakter.')
                ->unique(
                    table: 'mapels',
                    column: 'nama_mapel',
                    ignoreRecord: true
                )
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, $set) {
                    if ($state) {
                        $cleaned = ucwords(trim($state));
                        $set('nama_mapel', $cleaned);
                    }
                }),

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
                ->native(false),

            // ✅ UBAH INI: TextInput -> Select dengan relasi
            Select::make('pengampu') // Tetap nama 'pengampu' tapi sekarang relasi
                ->label('Pengampu')
                ->relationship('pengampu', 'nama') // Relasi ke model Ustadz
                ->multiple() // Bisa pilih banyak ustadz
                ->required()
                ->preload()
                ->searchable()
                ->helperText('Pilih ustadz yang mengampu mata pelajaran ini')
                ->options(function () {
                    // Hanya tampilkan ustadz yang aktif
                    return Ustadz::where('status_aktif', true)
                        ->get()
                        ->pluck('nama', 'id');
                }),
        ];
    }
}