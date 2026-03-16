<?php

namespace App\Filament\Resources\Ustadzs\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use App\Models\Mapel;

class UstadzForm
{
    public static function schema(): array
    {
        return [
            // SECTION: DATA DIRI (2 kolom)
            Section::make('Data Diri')
                ->schema([
                    Grid::make(2)->schema([
                        // Kolom Kiri
                        Grid::make(1)->schema([
                            TextInput::make('nama')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            TextInput::make('nip')
                                ->label('NIP')
                                ->unique('ustadzs', 'nip', ignoreRecord: true)
                                ->maxLength(255),

                            Select::make('jenis_kelamin')
                                ->label('Jenis Kelamin')
                                ->options([
                                    'L' => 'Laki-laki',
                                    'P' => 'Perempuan',
                                ])
                                ->required(),
                        ]),

                        // Kolom Kanan
                        Grid::make(1)->schema([
                            TextInput::make('tempat_lahir')
                                ->label('Tempat Lahir')
                                ->maxLength(255),

                            DatePicker::make('tanggal_lahir')
                                ->label('Tanggal Lahir')
                                ->displayFormat('d/m/Y'),

                            Toggle::make('status_aktif')
                                ->label('Aktif')
                                ->default(true)
                                ->helperText('Jika diaktifkan, ustadz akan muncul dalam daftar pengajar yang aktif'),
                        ]),
                    ]),
                ])
                ->columnSpanFull(),

            // SECTION: MATA PELAJARAN (Opsional)
            Section::make('Mata Pelajaran yang Diajar')
                ->schema([
                    Select::make('mataPelajarans')
                        ->relationship('mataPelajarans', 'nama_mapel')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->label('Mata Pelajaran')
                        ->helperText('Pilih mata pelajaran yang diajar oleh ustadz ini'),
                ])
                ->columnSpanFull(),

            // SECTION: ALAMAT (full width)
            Section::make('Alamat')
                ->schema([
                    Textarea::make('alamat')
                        ->label('Alamat Lengkap')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
        ];
    }
}