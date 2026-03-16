<?php

namespace App\Filament\Resources\SantriResource\Pages;

use App\Filament\Resources\SantriResource;
use App\Filament\Resources\SantriResource\Schemas\SantriInfolist;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSantri extends ViewRecord
{
    protected static string $resource = SantriResource::class;

    // UBAH MENJADI public
    public function getInfolistSchema(): array
    {
        return SantriInfolist::schema($this->infolist);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}