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
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Closure;

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
                            ->options(TahunAjaran::orderBy('tahun_awal', 'desc')->get()->mapWithKeys(fn($ta) => [$ta->id => $ta->tahun_ajaran]))
                            ->default(fn() => TahunAjaran::where('status', true)->first()?->id)
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),

                        Select::make('semester_id')
                            ->label('Semester')
                            ->options(fn($get) => $get('tahun_ajaran_id')
                                ? Semester::where('tahun_ajaran_id', $get('tahun_ajaran_id'))->get()->mapWithKeys(fn($s) => [$s->id => $s->semester_label])
                                : Semester::orderBy('id', 'desc')->get()->mapWithKeys(fn($s) => [$s->id => $s->nama_lengkap]))
                            ->default(fn() => Semester::where('status', true)->first()?->id)
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(Kelas::orderBy('nama_kelas')->get()->mapWithKeys(fn($k) => [$k->id => $k->nama_kelas]))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->rule(
                                fn($get) => fn(string $attribute, $value, Closure $fail) => (
                                    filled($get('semester_id')) &&
                                    filled($get('hari')) &&
                                    filled($get('jam_pelajaran_id')) &&
                                    !blank($value) &&
                                    \App\Models\JadwalPelajaran::query()
                                    ->where('kelas_id', $value)
                                    ->where('semester_id', $get('semester_id'))
                                    ->where('hari', $get('hari'))
                                    ->where('jam_pelajaran_id', $get('jam_pelajaran_id'))
                                    ->exists()
                                ) ? $fail('Schedule already exists for this class, day, and time slot in the selected semester.') : null
                            ),
                    ]),
                ]),

            Section::make('Jadwal Pelajaran')
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
                            ->required()
                            ->live()
                            ->rule(
                                fn($get) => fn(string $attribute, $value, Closure $fail) => (
                                    filled($get('semester_id')) &&
                                    filled($get('kelas_id')) &&
                                    filled($get('jam_pelajaran_id')) &&
                                    !blank($value) &&
                                    \App\Models\JadwalPelajaran::query()
                                    ->where('hari', $value)
                                    ->where('semester_id', $get('semester_id'))
                                    ->where('kelas_id', $get('kelas_id'))
                                    ->where('jam_pelajaran_id', $get('jam_pelajaran_id'))
                                    ->exists()
                                ) ? $fail('Schedule already exists for this class, day, and time slot in the selected semester.') : null
                            ),

                        Select::make('jam_pelajaran_id')
                            ->label('Jam')
                            ->options(JamPelajaran::where('status', true)->orderBy('nama_jam')->get()->mapWithKeys(fn($jp) => [$jp->id => $jp->nama_jam . ' (' . date('H:i', strtotime($jp->jam_mulai)) . ' - ' . date('H:i', strtotime($jp->jam_selesai)) . ')']))
                            ->required()
                            ->live()
                            ->rule(
                                fn($get) => fn(string $attribute, $value, Closure $fail) => (
                                    filled($get('semester_id')) &&
                                    filled($get('kelas_id')) &&
                                    filled($get('hari')) &&
                                    !blank($value) &&
                                    \App\Models\JadwalPelajaran::query()
                                    ->where('jam_pelajaran_id', $value)
                                    ->where('semester_id', $get('semester_id'))
                                    ->where('kelas_id', $get('kelas_id'))
                                    ->where('hari', $get('hari'))
                                    ->exists()
                                ) ? $fail('Schedule already exists for this class, day, and time slot in the selected semester.') : null
                            ),
                    ]),

                    Grid::make(2)->schema([
                        Select::make('mapel_id')
                            ->label('Mapel')
                            ->options(Mapel::orderBy('nama_mapel')->get()->mapWithKeys(fn($m) => [$m->id => $m->nama_mapel]))
                            ->required()
                            ->live(),

                        Select::make('ustadz_id')
                            ->label('Ustadz')
                            ->options(fn($get) => $get('mapel_id') ? Ustadz::whereIn('id', DB::table('mapel_ustadz')->where('mapel_id', $get('mapel_id'))->pluck('ustadz_id'))->where('status_aktif', true)->orderBy('nama')->get()->mapWithKeys(fn($u) => [$u->id => $u->nama]) : [])
                            ->required()
                            ->live()
                            ->rule(
                                fn($get) => fn(string $attribute, $value, Closure $fail) => (
                                    filled($get('semester_id')) &&
                                    filled($get('kelas_id')) &&
                                    filled($get('hari')) &&
                                    filled($get('jam_pelajaran_id')) &&
                                    !blank($value) &&
                                    \App\Models\JadwalPelajaran::query()
                                    ->where('ustadz_id', $value)
                                    ->where('semester_id', $get('semester_id'))
                                    ->where('hari', $get('hari'))
                                    ->where('jam_pelajaran_id', $get('jam_pelajaran_id'))
                                    ->where('kelas_id', '!=', $get('kelas_id'))
                                    ->exists()
                                ) ? $fail('Teacher is already scheduled for another class at the same day and time in this semester.') : null
                            ),
                    ]),

                    Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->rows(3)
                        ->placeholder('Optional notes'),
                ]),
        ];
    }
}
