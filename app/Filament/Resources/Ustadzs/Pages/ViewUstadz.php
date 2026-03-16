<?php

namespace App\Filament\Resources\Ustadzs\Pages;

use App\Filament\Resources\Ustadzs\UstadzResource;
use App\Filament\Resources\Ustadzs\Widgets\JadwalMengajarWidget;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewUstadz extends ViewRecord
{
    protected static string $resource = UstadzResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('back')
                ->label('Kembali ke Daftar Ustadz')
                ->url(UstadzResource::getUrl('index'))
                ->color('gray'),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            JadwalMengajarWidget::make([
                'ustadzId' => $this->record->id,
            ]),
        ];
    }

    public function getFooterWidgetsColumns(): int | array
    {
        return 1;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}