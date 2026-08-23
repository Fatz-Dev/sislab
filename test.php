<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $kelas = \App\Models\KelasPraktikum::first();
    $mahasiswas = $kelas->approvedMahasiswas;
    $tugas = \App\Models\TugasLaporan::where('kelas_praktikum_id', $kelas->id)->latest()->first();

    if ($tugas && $mahasiswas->count() > 0) {
        foreach($mahasiswas as $mhs) {
            echo "Notifying Mhs ID: " . $mhs->id . "\n";
            $mhs->notify(new \App\Notifications\TugasBaruNotification($tugas, $kelas));
            broadcast(new \App\Events\TugasBaruEvent($tugas, $kelas, $mhs->id));
        }
        echo "Success!\n";
    } else {
        echo "No data to test.\n";
    }
} catch (\Exception $e) {
    echo "EXCEPTION THROWN:\n";
    echo $e->getMessage() . "\n";
}
