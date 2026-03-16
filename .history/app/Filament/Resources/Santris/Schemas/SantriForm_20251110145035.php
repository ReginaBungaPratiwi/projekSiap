<?php

namespace App\Filament\Resources\Santris\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SantriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_santri')
                    ->required(),
                TextInput::make('nis')
                    ->required(),
                TextInput::make('kelas')
                    ->required(),
                Select::make('jenjang')
                    ->options(['SD' => 'S d', 'SMP' => 'S m p', 'SMA' => 'S m a', 'SMK' => 'S m k'])
                    ->required(),
                TextInput::make('tahun_masuk')
                    ->required(),
            ]);
    }
}
