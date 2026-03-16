<?php

namespace App\Filament\Resources\JamPelajarans\Schemas;

use App\Models\JamPelajaran;
use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;

class JamPelajaranForm
{
    public static function getSchema(): array
    {
        return [
            TextInput::make('nama_jam')
                ->label('Nama Jam')
                ->required()
                ->maxLength(255)
                ->placeholder('Contoh: Jam ke-1')
                ->helperText('Masukkan nama jam pelajaran')
                ->unique(
                    table: JamPelajaran::class,
                    column: 'nama_jam',
                    ignoreRecord: true,
                )
                ->validationMessages([
                    'unique' => 'Nama jam ini sudah ada.',
                ]),

            TimePicker::make('jam_mulai')
                ->label('Jam Mulai')
                ->required()
                ->seconds(false)
                ->live()
                ->helperText('Pilih waktu mulai jam pelajaran'),

            TimePicker::make('jam_selesai')
                ->label('Jam Selesai')
                ->required()
                ->seconds(false)
                ->helperText('Pilih waktu selesai jam pelajaran')
                ->after('jam_mulai')
                ->rules([
                    fn (Get $get, ?JamPelajaran $record): Closure => function (string $attribute, $value, Closure $fail) use ($get, $record) {
                        $jamMulai = $get('jam_mulai');
                        if (!$jamMulai || !$value) {
                            return;
                        }

                        $exists = JamPelajaran::where('jam_mulai', $jamMulai)
                            ->where('jam_selesai', $value)
                            ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                            ->exists();

                        if ($exists) {
                            $fail("Jam pelajaran dengan waktu {$jamMulai} - {$value} sudah ada.");
                        }
                    },
                ]),

            Textarea::make('keterangan')
                ->label('Keterangan')
                ->rows(3)
                ->maxLength(500)
                ->placeholder('Keterangan tambahan (opsional)')
                ->helperText('Keterangan atau catatan tambahan'),

            Toggle::make('status')
                ->label('Status Aktif')
                ->default(true)
                ->helperText('Aktifkan atau nonaktifkan jam pelajaran'),
        ];
    }
}
