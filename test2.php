<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kelas = \App\Models\KelasPraktikum::find(5);
if ($kelas) {
    echo "Kelas: " . $kelas->nama_kelas . "\n";
    $mahasiswas = $kelas->approvedMahasiswas;
    echo "Count: " . $mahasiswas->count() . "\n";
} else {
    echo "Kelas 5 not found\n";
}
