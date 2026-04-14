<?php

namespace App\Filament\Resources\Semesters\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;

class SemestersTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('no')
                ->label('No')
                ->rowIndex()
                ->sortable(false),

            TextColumn::make('tahunAjaran.tahun_ajaran')
                ->label('Tahun Ajaran')
                ->formatStateUsing(fn ($record) => $record->tahunAjaran?->tahun_awal . '/' . $record->tahunAjaran?->tahun_akhir)
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->where(function (Builder $q) use ($search) {
                        // Search tahun ajaran
                        $q->whereHas('tahunAjaran', function (Builder $subQ) use ($search) {
                            $subQ->where('tahun_awal', 'like', "%{$search}%")
                                ->orWhere('tahun_akhir', 'like', "%{$search}%")
                                ->orWhereRaw("CONCAT(tahun_awal, '/', tahun_akhir) LIKE ?", ["%{$search}%"]);
                        })
                        // Search semester
                        ->orWhere('semester', 'like', "%{$search}%")
                        // Search tanggal mulai & selesai (format d/m/Y)
                        ->orWhereRaw("DATE_FORMAT(tanggal_mulai, '%d/%m/%Y') LIKE ?", ["%{$search}%"])
                        ->orWhereRaw("DATE_FORMAT(tanggal_selesai, '%d/%m/%Y') LIKE ?", ["%{$search}%"]);
                    });
                })
                ->sortable(query: function (Builder $query, string $direction): Builder {
                    return $query->orderBy(
                        \App\Models\TahunAjaran::select('tahun_awal')
                            ->whereColumn('tahun_ajarans.id', 'semesters.tahun_ajaran_id'),
                        $direction
                    );
                }),

            TextColumn::make('semester')
                ->label('Semester')
                ->formatStateUsing(fn ($state) => $state === 'ganjil' ? 'Ganjil' : 'Genap')
                ->badge()
                ->color(fn ($state) => $state === 'ganjil' ? 'info' : 'warning')
                ->sortable(),

            TextColumn::make('tanggal_mulai')
                ->label('Tanggal Mulai')
                ->date('d/m/Y')
                ->sortable(),

            TextColumn::make('tanggal_selesai')
                ->label('Tanggal Selesai')
                ->date('d/m/Y')
                ->sortable(),

            IconColumn::make('status')
                ->label('Status')
                ->boolean()
                ->trueIcon('heroicon-o-check-badge')
                ->falseIcon('heroicon-o-x-mark')
                ->trueColor('success')
                ->falseColor('danger')
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Dibuat Pada')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getFilters(): array
    {
        return [
            SelectFilter::make('tahun_ajaran_id')
                ->label('Tahun Ajaran')
                ->relationship('tahunAjaran', 'tahun_awal')
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->tahun_awal . '/' . $record->tahun_akhir)
                ->searchable()
                ->preload()
                ->placeholder('Semua Tahun Ajaran'),

            SelectFilter::make('semester')
                ->label('Semester')
                ->options([
                    'ganjil' => 'Ganjil',
                    'genap' => 'Genap',
                ])
                ->placeholder('Semua Semester'),

            SelectFilter::make('status')
                ->label('Status')
                ->options([
                    true => 'Aktif',
                    false => 'Tidak Aktif',
                ])
                ->placeholder('Semua Status'),
        ];
    }

    public static function getActions(): array
    {
        return [
            Actions\ViewAction::make()->label('View'),
            Actions\EditAction::make()->label('Edit'),

            // AKTIFKAN (muncul saat status = false)
            Actions\Action::make('activate')
                ->label('Aktifkan')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action(function ($record) {
                    \App\Models\Semester::where('id', '!=', $record->id)->update(['status' => false]);
                    $record->update(['status' => true]);
                })
                ->hidden(fn ($record) => $record->status)
                ->requiresConfirmation(),

            // NONAKTIFKAN (muncul saat status = true)
            Actions\Action::make('deactivate')
                ->label('Nonaktifkan')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->action(function ($record) {
                    $record->update(['status' => false]);
                })
                ->hidden(fn ($record) => !$record->status)
                ->requiresConfirmation(),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            Actions\BulkActionGroup::make([
            ]),
        ];
    }
}
