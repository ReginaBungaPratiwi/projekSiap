<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Santri;
use App\Models\TahunAjaran;

$tahunAjaranAktif = TahunAjaran::getAktif();
if (! $tahunAjaranAktif) {
    echo "Tidak ada tahun ajaran aktif. Jalankan dulu dan aktifkan Tahun Ajaran.\n";
    exit(1);
}

$tahunAkademik = $tahunAjaranAktif->tahun_ajaran;
$semester = $tahunAjaranAktif->semester ?? 'ganjil';

$santriCount = 0;
$createdCount = 0;

foreach (Santri::with('riwayatKelas')->cursor() as $santri) {
    $santriCount++;

    if (! $santri->kelas_id) {
        continue;
    }

    $exists = $santri->riwayatKelas
        ->where('kelas_id', $santri->kelas_id)
        ->where('tahun_akademik', $tahunAkademik)
        ->where('semester', $semester)
        ->first();

    if ($exists) {
        continue;
    }

    $santri->riwayatKelas()->create([
        'kelas_id' => $santri->kelas_id,
        'tahun_akademik' => $tahunAkademik,
        'semester' => $semester,
    ]);

    $createdCount++;
    echo "Backfill: Santri {$santri->id} ({$santri->nama_lengkap}) -> kelas_id={$santri->kelas_id}\n";
}

echo "Selesai. Santri diperiksa: {$santriCount}. Riwayat dibuat: {$createdCount}.\n";
