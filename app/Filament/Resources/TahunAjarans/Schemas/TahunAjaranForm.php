<?php

namespace App\Filament\Resources\TahunAjarans\Schemas;

use App\Models\TahunAjaran;
use Closure;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;

class TahunAjaranForm
{
    public static function getSchema(): array
    {
        $tahunSekarang = (int) date('Y');
        $tahunDepan = $tahunSekarang + 1;

        return [
            TextInput::make('tahun_awal')
                ->label('Tahun Ajaran Awal')
                ->required()
                ->numeric()
                ->length(4)
                ->minLength(4)
                ->maxLength(4)
                ->placeholder('Contoh: 2024')
                ->default($tahunSekarang)
                ->disabled()
                ->helperText(new HtmlString('<span class="text-red-600 font-semibold">Tahun ajaran otomatis ditentukan dari tahun sekarang dan tidak bisa diubah.</span>'))
                ->rules([
                    fn (Get $get, ?TahunAjaran $record): Closure => function (string $attribute, $value, Closure $fail) use ($get, $record) {
                        $tahunAkhir = $get('tahun_akhir');

                        if (! $value || ! $tahunAkhir) {
                            return;
                        }

                        $exists = TahunAjaran::where('tahun_awal', $value)
                            ->where('tahun_akhir', $tahunAkhir)
                            ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                            ->exists();

                        if ($exists) {
                            $fail("Tahun ajaran {$value}/{$tahunAkhir} sudah terdaftar.");
                        }
                    },
                ])
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, $set) {
                    if ($state) {
                        $set('tahun_awal', trim($state));
                    }
                }),

            TextInput::make('tahun_akhir')
                ->label('Tahun Ajaran Akhir')
                ->required()
                ->numeric()
                ->length(4)
                ->minLength(4)
                ->maxLength(4)
                ->placeholder('Contoh: 2025')
                ->default($tahunDepan)
                ->disabled()
                ->helperText(new HtmlString('<span class="text-red-600 font-semibold">Tahun ajaran otomatis ditentukan dari tahun sekarang dan tidak bisa diubah.</span>'))
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, $set) {
                    if ($state) {
                        $set('tahun_akhir', trim($state));
                    }
                }),

            Toggle::make('status')
                ->label('Status Aktif')
                ->reactive()
                ->helperText('Jika diaktifkan, tahun ajaran lain akan otomatis dinonaktifkan')
                ->afterStateUpdated(function ($state) {
                    if ($state === true) {
                        \App\Models\TahunAjaran::where('status', true)
                            ->update(['status' => false]);
                    }
                }),
        ];
    }
}