<?php

namespace App\Imports;

use App\Models\Santri;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SantriExcelImport implements ToModel, WithHeadingRow, WithBatchInserts, WithValidation
{
    use Importable;

    protected $kelas_default;
    protected $tahun_masuk_default;

    public function __construct($kelas_default = '10A', $tahun_masuk_default = null)
    {
        $this->kelas_default = $kelas_default;
        $this->tahun_masuk_default = $tahun_masuk_default ?? now()->year;
    }

    public function model(array $row): ?Santri
    {
        if (empty(array_filter($row)) || (empty($row['nama_lengkap']) && empty($row['nis']))) {
            return null;
        }

        return new Santri([
            'nama_lengkap' => trim($row['nama_lengkap'] ?? ''),
            'jenis_kelamin' => strtoupper(trim($row['jenis_kelamin'] ?? '')) === 'L' ? 'L' : 'P',
            'tempat_lahir' => trim($row['tempat_lahir'] ?? ''),
            'tanggal_lahir' => $this->parseDate($row['tanggal_lahir'] ?? null),
            'alamat' => trim($row['alamat'] ?? ''),
            'nis' => trim($row['nis'] ?? ''),
            'kamar' => trim($row['kamar'] ?? ''),
            'jenjang' => strtoupper($row['jenjang'] ?? 'SMP'),
            'kelas_id' => $this->findKelasId($row['kelas'] ?? $this->kelas_default),
            'tahun_masuk' => (int) ($row['tahun_masuk'] ?? $this->tahun_masuk_default),
            'status' => $row['status'] ?? 'aktif',
            'nama_ayah' => trim($row['nama_ayah'] ?? ''),
            'no_hp_ayah' => $row['no_hp_ayah'] ?? '',
            'pekerjaan_ayah' => trim($row['pekerjaan_ayah'] ?? ''),
            'nama_ibu' => trim($row['nama_ibu'] ?? ''),
            'no_hp_ibu' => $row['no_hp_ibu'] ?? '',
            'pekerjaan_ibu' => trim($row['pekerjaan_ibu'] ?? ''),
            'alamat_ortu' => trim($row['alamat_ortu'] ?? $row['alamat'] ?? ''),
        ]);
    }

    private function parseDate($date): ?string
    {
        if (empty($date)) {
            return null;
        }

        if (is_numeric($date)) {
            try {
                $unixTimestamp = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date);
                return Carbon::instance($unixTimestamp)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function findKelasId($namaKelas): ?int
    {
        if (empty(trim($namaKelas))) {
            return null;
        }

        return Kelas::whereRaw('LOWER(nama_kelas) LIKE ?', ['%' . strtolower(trim($namaKelas)) . '%'])
            ->first()?->id;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            '*.nama_lengkap' => 'required|string|max:255',
            '*.nis' => 'nullable|string|max:50|unique:santris,nis',
            '*.jenis_kelamin' => 'nullable|in:L,P',
        ];
    }
}
