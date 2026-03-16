<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SantriResource\Pages;
use App\Models\Santri;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Santri';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $slug = 'santri';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Santri')
                    ->schema([
                        Forms\Components\TextInput::make('nama_santri')
                            ->label('Nama Santri')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama lengkap santri'),
                            
                        Forms\Components\TextInput::make('nis')
                            ->label('NIS')
                            ->required()
                            ->unique('santris', 'nis', ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Contoh: 2401234567')
                            ->validationMessages([
                                'unique' => 'NIS sudah digunakan oleh santri lain.',
                            ]),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Data Akademik')
                    ->schema([
                        Forms\Components\TextInput::make('kelas')
                            ->required()
                            ->maxLength(10)
                            ->placeholder('Contoh: 7A, 8B, 10TKJ'),
                            
                        Forms\Components\Select::make('jenjang')
                            ->options([
                                'SD' => 'SD',
                                'SMP' => 'SMP', 
                                'SMA' => 'SMA',
                                'SMK' => 'SMK',
                            ])
                            ->required()
                            ->placeholder('Pilih jenjang'),
                            
                        Forms\Components\Select::make('tahun_masuk')
                            ->options(function () {
                                $years = [];
                                $currentYear = date('Y');
                                for ($year = $currentYear; $year >= 2000; $year--) {
                                    $years[$year] = $year;
                                }
                                return $years;
                            })
                            ->required()
                            ->placeholder('Pilih tahun masuk'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSantris::route('/'),
            'create' => Pages\CreateSantri::route('/create'),
            'edit' => Pages\EditSantri::route('/{record}/edit'),
        ];
    }
}