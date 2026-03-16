<?php

namespace App\Filament\Resources\JadwalPelajarans\Schemas;

use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Models\Ustadz;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class JadwalPelajaranForm
{
    public static function getSchema(): array
    {
        return [
            Section::make('Informasi')
                ->description('Pilih tahun ajaran, semester, dan kelas')
                ->schema([
                    Grid::make(3)->schema([
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
                                        ->mapWithKeys(fn ($s) => [$s->id => $s->nama_lengkap]);
                                }
                                return Semester::where('tahun_ajaran_id', $tahunAjaranId)
                                    ->get()
                                    ->mapWithKeys(fn ($s) => [$s->id => $s->semester_label]);
                            })
                            ->default(function () {
                                return Semester::where('status', true)->first()?->id;
                            })
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(function () {
                                return Kelas::orderBy('nama_kelas')
                                    ->get()
                                    ->mapWithKeys(fn ($k) => [$k->id => $k->nama_kelas]);
                            })
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),
                ]),

            Section::make('Jadwal Pelajaran')
                ->description('Atur jadwal pelajaran')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('hari')
                            ->label('Hari')
                            ->options([
                                'senin' => 'Senin',
                                'selasa' => 'Selasa',
                                'rabu' => 'Rabu',
                                'kamis' => 'Kamis',
                                'jumat' => 'Jumat',
                                'sabtu' => 'Sabtu',
                            ])
                            ->required(),

                        Select::make('jam_pelajaran_id')
                            ->label('Jam Pelajaran')
                            ->options(function () {
                                return JamPelajaran::where('status', true)
                                    ->orderBy('nama_jam')
                                    ->get()
                                    ->mapWithKeys(function ($jp) {
                                        $waktuMulai = date('H:i', strtotime($jp->jam_mulai));
                                        $waktuSelesai = date('H:i', strtotime($jp->jam_selesai));
                                        return [$jp->id => "{$jp->nama_jam} ({$waktuMulai} - {$waktuSelesai})"];
                                    });
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state) {
                                    $jp = JamPelajaran::find($state);
                                    if ($jp) {
                                        $set('waktu_display', date('H:i', strtotime($jp->jam_mulai)) . ' - ' . date('H:i', strtotime($jp->jam_selesai)));
                                    }
                                }
                            }),
                    ]),

                    Grid::make(2)->schema([
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
                            ->live(),

                        Select::make('ustadz_id')
                            ->label('Pengampu')
                            ->options(function ($get) {
                                $mapelId = $get('mapel_id');
                                if (!$mapelId) {
                                    return [];
                                }

                                // Ambil ustadz yang mengampu mapel ini DAN aktif
                                $ustadzIds = DB::table('mapel_ustadz')
                                    ->where('mapel_id', $mapelId)
                                    ->pluck('ustadz_id');

                                if ($ustadzIds->isEmpty()) {
                                    return [];
                                }

                                return Ustadz::whereIn('id', $ustadzIds)
                                    ->where('status_aktif', true)
                                    ->orderBy('nama')
                                    ->get()
                                    ->mapWithKeys(fn ($u) => [$u->id => $u->nama]);
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih Ustadz'),
                    ]),

                    Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->rows(3)
                        ->placeholder('Keterangan tambahan (opsional)')
                        ->columnSpanFull(),
                ]),
        ];
    }
}
