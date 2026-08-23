<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TugasBaruEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tugas;
    public $kelas;
    public $mahasiswaIds;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($tugas, $kelas, $mahasiswaIds)
    {
        $this->tugas = $tugas;
        $this->kelas = $kelas;
        // Accept either a single ID or an array of IDs
        $this->mahasiswaIds = is_array($mahasiswaIds) ? $mahasiswaIds : [$mahasiswaIds];
        $this->message = "Tugas baru '{$tugas->judul}' telah ditambahkan pada kelas {$kelas->nama_kelas}";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];
        foreach ($this->mahasiswaIds as $id) {
            $channels[] = new Channel('notifikasi.user.' . $id);
        }
        return $channels;
    }

    public function broadcastWith(): array
    {
        return [
            'tugas_id' => $this->tugas->id,
            'kelas_id' => $this->kelas->id,
            'jadwal_id' => $this->tugas->jadwal_id,
            'message' => $this->message,
            'title' => 'Tugas Baru',
            'url' => route('mahasiswa.tugas.show', [$this->kelas->id, $this->tugas->id]),
        ];
    }
}
