<?php

namespace App\Filament\Resources\SantriResource\Tables;

use App\Models\Santri;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SantrisTable
{
    public function __invoke(Table $table): Table
    {
        return $table
            ->query(Santri::query())
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No')
                    ->rowIndex()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nama_santri')
                    ->label('Nama Santri')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('kelas')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('jenjang')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('tahun_masuk')
                    ->sortable(),
                    
                Tables\Columns\ActionsColumn::make('actions')
                    ->label('Aksi')
                    ->actions([
                        Tables\Actions\ActionGroup::make([
                            Tables\Actions\ViewAction::make(),
                            Tables\Actions\EditAction::make(),
                            Tables\Actions\DeleteAction::make(),
                        ])
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenjang')
                    ->options([
                        'SD' => 'SD',
                        'SMP' => 'SMP',
                        'SMA' => 'SMA', 
                        'SMK' => 'SMK',
                    ]),
                    
                Tables\Filters\SelectFilter::make('tahun_masuk')
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
                // Actions akan ditampilkan di column Aksi
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada data santri')
            ->emptyStateDescription('Klik tombol "Tambah Santri" untuk menambahkan data pertama.')
            ->emptyStateIcon('heroicon-o-user-group')
            ->defaultPaginationPageOption(10);
    }
}