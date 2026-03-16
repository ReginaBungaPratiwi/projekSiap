<?php

namespace App\Filament\Resources\Kkms\Schemas;

use App\Models\Kelas;
use App\Models\Kkm;
use App\Models\Mapel;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Models\Ustadz;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Validation\Rules\Unique;

class KkmForm
{
    public static function getSchema(): array
    {
        return [
            Section::make('Informasi KKM')
                ->description('Tentukan KKM untuk mata pelajaran di kelas tertentu')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('tahun_ajaran_id')
                            ->label('Tahun Ajaran')
                            ->options(function () {
                                return TahunAjaran::orderBy('tahun_awal', 'desc')
                                    ->get()
                                    ->mapWithKeys(fn ($ta) => [$ta->id => $ta->tahun_ajaran]);
                            })
                            ->default(function () {
                                return TahunAjaran::where('status', true)->first()?->id;
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),

                        Select::make('semester_id')
                            ->label('Semester')
                            ->options(function ($get) {
                                $tahunAjaranId = $get('tahun_ajaran_id');
                                if (!$tahunAjaranId) {
                                    return Semester::orderBy('id', 'desc')
                                        ->get()
                                        ->mapWithKeys(fn ($s) => [$s->id => $s->semester]);
                                }
                                return Semester::where('tahun_ajaran_id', $tahunAjaranId)
                                    ->get()
                                    ->mapWithKeys(fn ($s) => [$s->id => ucfirst($s->semester)]);
                            })
                            ->default(function () {
                                return Semester::where('status', true)->first()?->id;
                            })
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),

                    Grid::make(2)->schema([
                        Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(function () {
                                return Kelas::orderBy('nama_kelas')
                                    ->get()
                                    ->mapWithKeys(fn ($k) => [$k->id => $k->nama_kelas]);
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),

                        Select::make('mapel_id')
                            ->label('Mata Pelajaran')
                            ->options(function () {
                                return Mapel::orderBy('nama_mapel')
                                    ->get()
                                    ->mapWithKeys(fn ($m) => [$m->id => $m->nama_mapel]);
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->rules([
                                fn ($get, $record) => function ($attribute, $value, $fail) use ($get, $record) {
                                    $exists = Kkm::where('mapel_id', $value)
                                        ->where('kelas_id', $get('kelas_id'))
                                        ->where('tahun_ajaran_id', $get('tahun_ajaran_id'))
                                        ->where('semester_id', $get('semester_id'))
                                        ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                                        ->exists();

                                    if ($exists) {
                                        $fail('KKM untuk kombinasi Mapel, Kelas, Tahun Ajaran, dan Semester ini sudah ada.');
                                    }
                                },
                            ]),
                    ]),

                    Select::make('ustadz_id')
                        ->label('Ustadz Pengampu')
                        ->options(function ($get) {
                            $mapelId = $get('mapel_id');

                            if (!$mapelId) {
                                return Ustadz::where('status_aktif', true)
                                    ->orderBy('nama')
                                    ->pluck('nama', 'id');
                            }

                            // Ambil ustadz yang mengajar mapel tersebut
                            return Ustadz::where('status_aktif', true)
                                ->whereHas('mataPelajarans', function ($query) use ($mapelId) {
                                    $query->where('mapels.id', $mapelId);
                                })
                                ->orderBy('nama')
                                ->pluck('nama', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->placeholder('Pilih Ustadz Pengampu')
                        ->helperText('Pilih ustadz yang mengajar mapel ini')
                        ->columnSpanFull(),

                    TextInput::make('nilai_kkm')
                        ->label('Nilai KKM')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->required()
                        ->placeholder('Contoh: 75')
                        ->helperText('Masukkan nilai KKM antara 0-100')
                        ->columnSpanFull(),
                ]),
        ];
    }
}
