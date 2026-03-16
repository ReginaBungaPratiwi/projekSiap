<?php

namespace App\Filament\Resources\Santris\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SantriInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama_santri'),
                TextEntry::make('nis'),
                TextEntry::make('kelas'),
                TextEntry::make('jenjang'),
                TextEntry::make('tahun_masuk'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
