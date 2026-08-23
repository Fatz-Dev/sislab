<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JadwalBaruEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jadwal;
    public $dosenId;
    public $laboranName;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($jadwal, $dosenId, $laboranName)
    {
        $this->jadwal = $jadwal;
        $this->dosenId = $dosenId;
        $this->laboranName = $laboranName;
        $this->message = "Laboran {$laboranName} menambahkan jadwal baru: {$jadwal->topik}";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('notifikasi.user.' . $this->dosenId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'jadwal_id' => $this->jadwal->id,
            'kelas_id' => $this->jadwal->kelas_praktikum_id,
            'message' => $this->message,
            'title' => 'Jadwal Baru',
        ];
    }
}
