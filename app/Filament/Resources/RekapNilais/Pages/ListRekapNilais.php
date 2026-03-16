<?php

namespace App\Filament\Resources\RekapNilais\Pages;

use App\Filament\Resources\RekapNilais\RekapNilaiResource;
use Filament\Resources\Pages\ListRecords;

class ListRekapNilais extends ListRecords
{
    protected static string $resource = RekapNilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action for reporting feature
        ];
    }
}
