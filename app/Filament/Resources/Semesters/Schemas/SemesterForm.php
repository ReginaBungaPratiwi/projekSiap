<?php

namespace App\Filament\Resources\Semesters\Schemas;

use App\Models\Semester;
use App\Models\TahunAjaran;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Section;

class SemesterForm
{
    public static function getSchema(): array
    {
        return [
            Section::make('Data Semester')
                ->schema([
                    Select::make('tahun_ajaran_id')
                        ->label('Tahun Ajaran')
                        ->relationship('tahunAjaran', 'tahun_awal')
                        ->getOptionLabelFromRecordUsing(fn (TahunAjaran $record) => $record->tahun_ajaran)
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live()
                        ->placeholder('Pilih Tahun Ajaran'),

                    Select::make('semester')
                        ->label('Semester')
                        ->options([
                            'ganjil' => 'Ganjil',
                            'genap' => 'Genap',
                        ])
                        ->required()
                        ->placeholder('Pilih Semester')
                        ->rules([
                            fn (Get $get, ?Semester $record): Closure => function (string $attribute, $value, Closure $fail) use ($get, $record) {
                                $tahunAjaranId = $get('tahun_ajaran_id');
                                if (!$tahunAjaranId || !$value) {
                                    return;
                                }

                                $exists = Semester::where('tahun_ajaran_id', $tahunAjaranId)
                                    ->where('semester', $value)
                                    ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                                    ->exists();

                                if ($exists) {
                                    $tahunAjaran = TahunAjaran::find($tahunAjaranId);
                                    $semesterLabel = $value === 'ganjil' ? 'Ganjil' : 'Genap';
                                    $fail("Semester {$semesterLabel} untuk Tahun Ajaran {$tahunAjaran->tahun_awal}/{$tahunAjaran->tahun_akhir} sudah ada.");
                                }
                            },
                        ]),

                    DatePicker::make('tanggal_mulai')
                        ->label('Tanggal Mulai')
                        ->required()
                        ->displayFormat('d/m/Y')
                        ->placeholder('Pilih tanggal mulai'),

                    DatePicker::make('tanggal_selesai')
                        ->label('Tanggal Selesai')
                        ->required()
                        ->displayFormat('d/m/Y')
                        ->afterOrEqual('tanggal_mulai')
                        ->placeholder('Pilih tanggal selesai'),

                    Toggle::make('status')
                        ->label('Status Aktif')
                        ->helperText('Jika diaktifkan, semester lain akan otomatis dinonaktifkan'),
                ])
                ->columns(2),

            Section::make('Keterangan')
                ->schema([
                    Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->rows(4)
                        ->placeholder('Tambahkan keterangan (opsional)')
                        ->columnSpanFull(),
                ]),
        ];
    }
}
