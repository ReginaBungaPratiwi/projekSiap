<?php

namespace App\Filament\Resources\SantriResource\Pages;

use App\Filament\Resources\SantriResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSantri extends EditRecord
{
    protected static string $resource = SantriResource::class;

    protected function getFormSchema(): array
    {
        return $this->getResource()::getFormSchema();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}