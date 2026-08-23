<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JadwalBaruNotification extends Notification
{
    use Queueable;

    protected $jadwal;
    protected $laboranName;

    /**
     * Create a new notification instance.
     */
    public function __construct($jadwal, $laboranName)
    {
        $this->jadwal = $jadwal;
        $this->laboranName = $laboranName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'jadwal_id' => $this->jadwal->id,
            'kelas_id' => $this->jadwal->kelas_praktikum_id,
            'message' => "Laboran {$this->laboranName} menambahkan jadwal baru: {$this->jadwal->topik}",
            'url' => route('dosen.kelas.show', $this->jadwal->kelas_praktikum_id)
        ];
    }
}
