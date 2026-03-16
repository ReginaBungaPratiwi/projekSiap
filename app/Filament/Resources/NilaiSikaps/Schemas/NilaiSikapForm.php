<?php

namespace App\Filament\Resources\NilaiSikaps\Schemas;

use App\Models\Santri;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\SantriKelas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class NilaiSikapForm
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

                            Select::make('santri_id')
                                ->label('Santri')
                                ->options(function ($get) {
                                    $kelasId = $get('kelas_id');
                                    $semesterId = $get('semester_id');

                                    if (!$kelasId) {
                                        return Santri::orderBy('nama_lengkap')->get()->mapWithKeys(fn ($s) => [$s->id => $s->nama_lengkap . ' (' . $s->nis . ')']);
                                    }

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
                ]),

            Section::make('Sikap Spiritual (KI-1)')
                ->description('Penilaian sikap spiritual meliputi: ketaatan beribadah, berperilaku syukur, berdoa, toleransi')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Select::make('sikap_spiritual')
                                ->label('Nilai Sikap Spiritual')
                                ->options([
                                    'SB' => 'SB - Sangat Baik',
                                    'B' => 'B - Baik',
                                    'C' => 'C - Cukup',
                                    'K' => 'K - Kurang',
                                ])
                                ->required()
                                ->default('B'),

                            Textarea::make('catatan_spiritual')
                                ->label('Catatan Sikap Spiritual')
                                ->rows(2)
                                ->placeholder('Deskripsi pencapaian sikap spiritual...'),
                        ]),
                ]),

            Section::make('Sikap Sosial (KI-2)')
                ->description('Penilaian sikap sosial meliputi: jujur, disiplin, tanggung jawab, santun, peduli, percaya diri')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Select::make('sikap_sosial')
                                ->label('Nilai Sikap Sosial')
                                ->options([
                                    'SB' => 'SB - Sangat Baik',
                                    'B' => 'B - Baik',
                                    'C' => 'C - Cukup',
                                    'K' => 'K - Kurang',
                                ])
                                ->required()
                                ->default('B'),

                            Textarea::make('catatan_sosial')
                                ->label('Catatan Sikap Sosial')
                                ->rows(2)
                                ->placeholder('Deskripsi pencapaian sikap sosial...'),
                        ]),
                ]),

            Section::make('Akhlak & Kedisiplinan')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Select::make('akhlak')
                                ->label('Nilai Akhlak')
                                ->options([
                                    'SB' => 'SB - Sangat Baik',
                                    'B' => 'B - Baik',
                                    'C' => 'C - Cukup',
                                    'K' => 'K - Kurang',
                                ])
                                ->required()
                                ->default('B'),

                            TextInput::make('kehadiran_persen')
                                ->label('Persentase Kehadiran')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->default(100)
                                ->suffix('%'),
                        ]),

                    Grid::make(2)
                        ->schema([
                            Textarea::make('catatan_akhlak')
                                ->label('Catatan Akhlak')
                                ->rows(2)
                                ->placeholder('Catatan mengenai akhlak santri...'),

                            Textarea::make('catatan_kedisiplinan')
                                ->label('Catatan Kedisiplinan')
                                ->rows(2)
                                ->placeholder('Catatan mengenai kedisiplinan santri...'),
                        ]),
                ]),
        ];
    }
}
