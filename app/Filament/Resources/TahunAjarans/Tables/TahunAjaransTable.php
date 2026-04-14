<?php

namespace App\Filament\Resources\TahunAjarans\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;

class TahunAjaransTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getColumns())
            ->filters(self::getFilters())
            ->actions(self::getActions())
            ->bulkActions(self::getBulkActions())
            ->defaultSort('created_at', 'desc');
    }

    public static function getColumns(): array
    {
        return [
            TextColumn::make('no')
                ->label('No')
                ->rowIndex()
                ->sortable(false),

            TextColumn::make('tahun_ajaran')
                ->label('Tahun Ajaran')
                ->formatStateUsing(fn ($record) => $record->tahun_awal . '/' . $record->tahun_akhir)
                ->searchable(['tahun_awal', 'tahun_akhir'])
                ->sortable(query: function (Builder $query, string $direction): Builder {
                    return $query->orderBy('tahun_awal', $direction)
                                 ->orderBy('tahun_akhir', $direction);
                }),

            TextColumn::make('tahun_awal')
                ->label('Tahun Awal')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(),

            TextColumn::make('tahun_akhir')
                ->label('Tahun Akhir')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(),

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
                    \App\Models\TahunAjaran::where('id', '!=', $record->id)->update(['status' => false]);
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
                ->hidden(fn ($record) => ! $record->status)
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
