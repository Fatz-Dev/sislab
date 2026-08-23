<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TugasBaruNotification extends Notification
{
    use Queueable;

    protected $tugas;
    protected $kelas;

    public function __construct($tugas, $kelas)
    {
        $this->tugas = $tugas;
        $this->kelas = $kelas;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tugas_id' => $this->tugas->id,
            'kelas_id' => $this->kelas->id,
            'message' => "Tugas baru \"{$this->tugas->judul}\" ditambahkan pada kelas {$this->kelas->nama_kelas}",
            'url' => route('mahasiswa.tugas.show', [$this->kelas->id, $this->tugas->id]),
        ];
    }
}
