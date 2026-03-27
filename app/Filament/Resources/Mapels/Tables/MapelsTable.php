<?php
// app/Filament/Resources/Mapels/Tables/MapelsTable.php

namespace App\Filament\Resources\Mapels\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions;

class MapelsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getColumns())
            ->filters(self::getFilters())
            ->actions(self::getActions())
            ->bulkActions(self::getBulkActions());
    }

    public static function getColumns(): array
    {
        return [
            TextColumn::make('no')
                ->label('No')
                ->rowIndex(),

            TextColumn::make('nama_mapel')
                ->searchable()
                ->sortable()
                ->label('Nama Mapel'),

            TextColumn::make('nama_jurusan')
                ->label('Jurusan')
                ->badge()
                ->color('gray')
                ->searchable(query: function ($query, $search) {
                    $query->whereHas('jurusan', function ($q) use ($search) {
                        $q->where('nama_jurusan', 'like', "%{$search}%");
                    });
                })
                ->sortable(query: function ($query, $direction) {
                    $query->join('jurusans', 'mapels.jurusan_id', '=', 'jurusans.id')
                        ->orderBy('jurusans.nama_jurusan', $direction)
                        ->select('mapels.*'); // ✅ Tambahkan ini untuk menghindari konflik kolom
                }),

            TextColumn::make('jenjang')
                ->searchable()
                ->sortable()
                ->label('Jenjang')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'SD' => 'success',
                    'SMP' => 'primary',
                    'SMA' => 'warning',
                    'SMK' => 'info',
                    default => 'gray',
                }),

            // Kolom pengampu ditampilkan tanpa warna badge
            TextColumn::make('pengampu')
                ->label('Pengampu')
                ->formatStateUsing(function ($record) {
                    // Cek jika $record->pengampu adalah Collection (relasi baru)
                    if ($record->pengampu instanceof \Illuminate\Database\Eloquent\Collection) {
                        if ($record->pengampu->isNotEmpty()) {
                            return $record->pengampu->pluck('nama')->join(', ');
                        }
                    }
                    // Cek jika $record->pengampu adalah string (data lama)
                    elseif (is_string($record->pengampu) && !empty($record->pengampu)) {
                        return $record->pengampu;
                    }
                    // Cek jika ada relasi yang terload
                    elseif ($record->relationLoaded('pengampu') && $record->pengampu->isNotEmpty()) {
                        return $record->pengampu->pluck('nama')->join(', ');
                    }

                    return 'Belum ada';
                })
                ->sortable(false),

            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getFilters(): array
    {
        return [
            SelectFilter::make('jurusan_id')
                ->label('Jurusan')
                ->relationship('jurusan', 'nama_jurusan')
                ->searchable()
                ->preload()
                ->placeholder('Semua Jurusan'),

            SelectFilter::make('jenjang')
                ->options([
                    'SD' => 'SD',
                    'SMP' => 'SMP',
                    'SMA' => 'SMA',
                    'SMK' => 'SMK',
                ])
                ->label('Jenjang')
                ->placeholder('Semua Jenjang'),
        ];
    }

    public static function getActions(): array
    {
        return [
            Actions\ViewAction::make()->label('View'),
            Actions\EditAction::make()->label('Edit'),
            Actions\DeleteAction::make()->label('Hapus'),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            Actions\BulkActionGroup::make([
                Actions\DeleteBulkAction::make()->label('Hapus yang dipilih'),
            ]),
        ];
    }
}
