<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Santri;
use App\Models\TahunAjaran;

foreach (Santri::all() as $s) {
    $first = $s->riwayatKelas()->with('kelas')->first();
    echo "SANTRI|{$s->id}|{$s->nama_lengkap}|kelas_id={$s->kelas_id}|riwayat_count=" . $s->riwayatKelas()->count() . "|first=" . ($first ? ($first->kelas ? $first->kelas->nama_kelas : 'NO_KELAS') : 'NULL') . "\n";
}
$active = TahunAjaran::getAktif();
echo 'ACTIVE|' . ($active ? $active->tahun_ajaran : 'NONE') . '|' . ($active ? $active->semester : 'NONE') . "\n";
