<?php

namespace App\Filament\Resources\SantriResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ActionsColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;

class SantrisTable
{
    public static function make(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('No')
                    ->rowIndex()
                    ->sortable(),
                    
                TextColumn::make('nama_santri')
                    ->label('Nama Santri')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('kelas')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('jenjang')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('tahun_masuk')
                    ->sortable(),
                    
                ActionsColumn::make('actions')
                    ->label('Aksi')
                    ->actions([
                        ActionGroup::make([
                            ViewAction::make(),
                            EditAction::make(),
                            DeleteAction::make(),
                        ])
                    ]),
            ])
            ->filters([
                SelectFilter::make('jenjang')
                    ->options([
                        'SD' => 'SD',
                        'SMP' => 'SMP',
                        'SMA' => 'SMA', 
                        'SMK' => 'SMK',
                    ]),
                    
                SelectFilter::make('tahun_masuk')
                    ->options(function () {
                        $years = [];
                        $currentYear = date('Y');
                        for ($year = $currentYear; $year >= 2000; $year--) {
                            $years[$year] = $year;
                        }
                        return $years;
                    }),
            ])
            ->actions([
                // Actions sudah di handle di column Aksi
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada data santri')
            ->emptyStateDescription('Klik tombol "Tambah Santri" untuk menambahkan data pertama.')
            ->emptyStateIcon('heroicon-o-user-group')
            ->defaultPaginationPageOption(10);
    }
}