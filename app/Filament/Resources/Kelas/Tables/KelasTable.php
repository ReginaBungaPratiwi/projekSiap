<?php

namespace App\Filament\Resources\Kelas\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;

class KelasTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('no')
                ->label('No')
                ->rowIndex(),

            TextColumn::make('nama_kelas')
                ->label('Nama Kelas')
                ->searchable()
                ->sortable(),

            TextColumn::make('jurusan.nama_jurusan')
                ->label('Jurusan')
                ->searchable()
                ->sortable()
                ->badge()
                ->color('gray'),

            // ✅ PERBAIKAN: Hapus formatStateUsing, langsung tampilkan nama
            TextColumn::make('waliKelas.nama')
                ->label('Ustadz Wali')
                ->searchable()
                ->sortable()
                ->badge()
                ->color(fn($state) => $state ? 'success' : 'gray'),

            TextColumn::make('jenjang')
                ->label('Jenjang')
                ->searchable()
                ->sortable()
                ->badge()
                // ✅ PERBAIKAN: Syntax yang benar
                ->color(fn(string $state): string => match ($state) {
                    'SD' => 'success',
                    'SMP' => 'primary',
                    'SMA' => 'warning',
                    'SMK' => 'info',
                    default => 'gray',
                }),
        ];
    }

    public static function getActions(): array
    {
        return [
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }

    public static function getFilters(): array
    {
        return [
            SelectFilter::make('jenjang')
                ->label('Filter by Jenjang')
                ->options([
                    'SD' => 'SD',
                    'SMP' => 'SMP',
                    'SMA' => 'SMA',
                    'SMK' => 'SMK',
                ])
                ->placeholder('Semua Jenjang')
                ->multiple()
                ->searchable(),

            SelectFilter::make('jurusan_id')
                ->label('Filter by Jurusan')
                ->relationship('jurusan', 'nama_jurusan')
                ->searchable()
                ->preload()
                ->placeholder('Semua Jurusan')
                ->multiple(),

            SelectFilter::make('wali_kelas_id')
                ->label('Filter by Wali Kelas')
                ->relationship('waliKelas', 'nama')
                ->searchable()
                ->preload()
                ->placeholder('Semua Wali Kelas')
                ->multiple(),
        ];
    }
}
