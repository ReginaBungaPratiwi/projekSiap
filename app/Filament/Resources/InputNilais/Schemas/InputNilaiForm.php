<?php

namespace App\Filament\Resources\InputNilais\Schemas;

use App\Models\Santri;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\SantriKelas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class InputNilaiForm
{
    public static function getSchema(): array
    {
        return [
            Section::make('Data Penilaian')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Select::make('tahun_ajaran_id')
                                ->label('Tahun Ajaran')
                                ->options(TahunAjaran::orderBy('tahun_awal', 'desc')->get()->mapWithKeys(fn ($ta) => [$ta->id => $ta->tahun_ajaran]))
                                ->required()
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(fn ($set) => $set('semester_id', null)),

                            Select::make('semester_id')
                                ->label('Semester')
                                ->options(function ($get) {
                                    $tahunAjaranId = $get('tahun_ajaran_id');
                                    if (!$tahunAjaranId) {
                                        return [];
                                    }
                                    return Semester::where('tahun_ajaran_id', $tahunAjaranId)
                                        ->get()
                                        ->mapWithKeys(fn ($s) => [$s->id => ucfirst($s->semester)]);
                                })
                                ->required()
                                ->searchable()
                                ->preload()
                                ->live(),
                        ]),

                    Grid::make(2)
                        ->schema([
                            Select::make('kelas_id')
                                ->label('Kelas')
                                ->options(Kelas::orderBy('nama_kelas')->get()->mapWithKeys(fn ($k) => [$k->id => $k->nama_kelas]))
                                ->required()
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(fn ($set) => $set('santri_id', null)),

                            Select::make('mapel_id')
                                ->label('Mata Pelajaran')
                                ->options(Mapel::orderBy('nama_mapel')->get()->mapWithKeys(fn ($m) => [$m->id => $m->nama_mapel]))
                                ->required()
                                ->searchable()
                                ->preload(),
                        ]),

                    Select::make('santri_id')
                        ->label('Santri')
                        ->options(function ($get) {
                            $kelasId = $get('kelas_id');
                            $semesterId = $get('semester_id');

                            if (!$kelasId) {
                                return Santri::orderBy('nama_lengkap')->get()->mapWithKeys(fn ($s) => [$s->id => $s->nama_lengkap . ' (' . $s->nis . ')']);
                            }

                            // Get santri from santri_kelas
                            $santriIds = SantriKelas::where('kelas_id', $kelasId)
                                ->when($semesterId, fn ($q) => $q->where('semester_id', $semesterId))
                                ->pluck('santri_id');

                            return Santri::whereIn('id', $santriIds)
                                ->orderBy('nama_lengkap')
                                ->get()
                                ->mapWithKeys(fn ($s) => [$s->id => $s->nama_lengkap . ' (' . $s->nis . ')']);
                        })
                        ->required()
                        ->searchable()
                        ->preload(),
                ]),

            Section::make('Input Nilai')
                ->description('Nilai akan dihitung otomatis: (20% Harian) + (30% UTS) + (40% UAS) + (10% Praktik)')
                ->schema([
                    Grid::make(4)
                        ->schema([
                            TextInput::make('nilai_harian')
                                ->label('Nilai Harian')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->step(0.01)
                                ->suffix('/ 100'),

                            TextInput::make('nilai_uts')
                                ->label('Nilai UTS')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->step(0.01)
                                ->suffix('/ 100'),

                            TextInput::make('nilai_uas')
                                ->label('Nilai UAS')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->step(0.01)
                                ->suffix('/ 100'),

                            TextInput::make('nilai_praktik')
                                ->label('Nilai Praktik')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->step(0.01)
                                ->suffix('/ 100'),
                        ]),

                    Textarea::make('catatan')
                        ->label('Catatan')
                        ->rows(3)
                        ->placeholder('Catatan tambahan untuk santri (opsional)'),
                ]),
        ];
    }
}
