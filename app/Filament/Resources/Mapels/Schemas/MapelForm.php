<?php
// app/Filament/Resources/Mapels/Schemas/MapelForm.php

namespace App\Filament\Resources\Mapels\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use App\Models\Ustadz;
use App\Models\Mapel;
use Illuminate\Database\Eloquent\Builder;

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
                ->relationship(
                    name: 'jurusan',
                    titleAttribute: 'nama_jurusan',
                    modifyQueryUsing: fn(Builder $query) => $query->where('status', true)
                )
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

            // FIX: Select tanpa relationship() untuk many-to-many
            Select::make('pengampu_ids')
                ->label('Pengampu')
                ->multiple()
                ->options(function () {
                    return Ustadz::query()
                        ->where('status_aktif', true)
                        ->orderBy('nama')
                        ->pluck('nama', 'id')
                        ->toArray();
                })
                ->searchable()
                ->preload()
                ->native(false)
                ->helperText('Pilih ustadz yang mengampu mata pelajaran ini (hanya ustadz aktif)')
                ->afterStateHydrated(function ($component, $record) {
                    // Load existing relationships saat edit
                    if ($record && $record->pengampu) {
                        $component->state($record->pengampu->pluck('id')->toArray());
                    }
                })
                ->dehydrated(false) // Jangan simpan otomatis
                ->saveRelationshipsUsing(function (Mapel $record, $state) {
                    // Simpan manual relasi many-to-many
                    $record->pengampu()->sync($state ?? []);
                }),
        ];
    }

    /**
     * Schema khusus untuk View (hanya baca)
     */
    public static function getViewSchema(): array
    {
        return [
            TextInput::make('nama_mapel')
                ->label('Nama Mapel')
                ->disabled(),

            Select::make('jurusan_id')
                ->label('Jurusan')
                ->relationship('jurusan', 'nama_jurusan')
                ->disabled(),

            Select::make('jenjang')
                ->label('Jenjang')
                ->options([
                    'SD' => 'SD',
                    'SMP' => 'SMP',
                    'SMA' => 'SMA',
                    'SMK' => 'SMK',
                ])
                ->disabled(),

            Placeholder::make('pengampu')
                ->label('Pengampu')
                ->content(function ($record) {
                    if (!$record || !$record->pengampu) {
                        return 'Tidak ada pengampu';
                    }
                    
                    $pengampu = $record->pengampu()
                        ->where('status_aktif', true)
                        ->pluck('nama')
                        ->implode(', ');
                    
                    return $pengampu ?: 'Tidak ada pengampu';
                }),
        ];
    }
}