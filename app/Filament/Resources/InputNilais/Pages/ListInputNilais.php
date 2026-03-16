<?php

namespace App\Filament\Resources\InputNilais\Pages;

use App\Filament\Resources\InputNilais\InputNilaiResource;
use App\Models\TahunAjaran;
use Filament\Resources\Pages\ListRecords;

class ListInputNilais extends ListRecords
{
    protected static string $resource = InputNilaiResource::class;

    public function getTitle(): string
    {
        $tahunAjaran = TahunAjaran::where('status', true)->first();
        $tahun = $tahunAjaran ? $tahunAjaran->tahun_ajaran : '-';
        return "Data Kelas pada Tahun Ajaran: {$tahun}";
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
