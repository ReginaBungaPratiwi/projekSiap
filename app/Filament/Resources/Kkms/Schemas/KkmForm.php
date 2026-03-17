<?php

namespace App\Filament\Resources\Kkms\Schemas;

use App\Models\Kelas;
use App\Models\Kkm;
use App\Models\Mapel;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Models\JadwalPelajaran;
use App\Models\Ustadz;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class KkmForm
{
    public static function getSchema(): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $isUstadz = $user && $user->hasRole('ustadz');

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
                                    ->mapWithKeys(fn($ta) => [$ta->id => $ta->tahun_ajaran]);
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
                                        ->mapWithKeys(fn($s) => [$s->id => $s->semester]);
                                }
                                return Semester::where('tahun_ajaran_id', $tahunAjaranId)
                                    ->get()
                                    ->mapWithKeys(fn($s) => [$s->id => ucfirst($s->semester)]);
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
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
                                    $ustadzId = $user->ustadz_id;
                                    return Kelas::whereHas('jadwalPelajarans', function ($query) use ($ustadzId) {
                                        $query->where('ustadz_id', $ustadzId);
                                    })
                                        ->orderBy('nama_kelas')
                                        ->pluck('nama_kelas', 'id');
                                }
                                return Kelas::orderBy('nama_kelas')
                                    ->pluck('nama_kelas', 'id');
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),

                        Select::make('mapel_id')
                            ->label('Mata Pelajaran')
                            ->options(function ($get) {
                                $kelasId = $get('kelas_id');
                                if (!$kelasId) {
                                    return [];
                                }
                                return Kelas::find($kelasId)
                                    ?->jadwalPelajarans()
                                    ->with('mapel')
                                    ->distinct('mapel_id')
                                    ->get()
                                    ->pluck('mapel.nama_mapel', 'mapel.id');
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih kelas terlebih dahulu')
                            ->live()
                            ->rules([
                                function ($get, $record) {
                                    return function ($attribute, $value, $fail) use ($get, $record) {
                                        $exists = Kkm::where('mapel_id', $value)
                                            ->where('kelas_id', $get('kelas_id'))
                                            ->where('tahun_ajaran_id', $get('tahun_ajaran_id'))
                                            ->where('semester_id', $get('semester_id'))
                                            ->when($record, fn($q) => $q->where('id', '!=', $record->id))
                                            ->exists();

                                        if ($exists) {
                                            $fail('KKM untuk kombinasi Mapel, Kelas, Tahun Ajaran, dan Semester ini sudah ada.');
                                        }
                                    };
                                },
                            ]),
                    ]),

                    Select::make('ustadz_id')
                        ->label('Ustadz Pengampu')
                        ->options(function ($get) {
                            $kelasId = $get('kelas_id');
                            $mapelId = $get('mapel_id');

                            if (!$kelasId || !$mapelId) {
                                return [];
                            }

                            return Ustadz::where('status_aktif', true)
                                ->whereHas('jadwalPelajarans', function ($query) use ($kelasId, $mapelId) {
                                    $query->where('kelas_id', $kelasId)
                                        ->where('mapel_id', $mapelId);
                                })
                                ->orderBy('nama')
                                ->pluck('nama', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->placeholder('Pilih kelas dan mapel terlebih dahulu')
                        ->helperText('Ustadz yang mengajar mapel ini di kelas terpilih')
                        ->columnSpanFull(),

                    TextInput::make('nilai_kkm')
                        ->label('Nilai KKM')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->required($isUstadz)
                        ->placeholder('Contoh: 75')
                        ->helperText($isUstadz ? 'Masukkan nilai KKM (0-100)' : 'Diisi oleh Ustadz pengampu')
                        ->columnSpanFull(),
                ]),
        ];
    }
}