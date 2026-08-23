<?php

namespace App\Events;

use App\Models\Pengumuman;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PengumumanPublished implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pengumuman;
    public $targetRole;

    /**
     * Create a new event instance.
     */
    public function __construct(Pengumuman $pengumuman, $targetRole)
    {
        $this->pengumuman = $pengumuman;
        $this->targetRole = $targetRole;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcast ke channel sesuai role
        return [
            new Channel('notifikasi.' . $this->targetRole),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->pengumuman->id,
            'title' => $this->pengumuman->judul,
            'message' => $this->pengumuman->isi,
            'time' => $this->pengumuman->tanggal_publish->format('H:i'),
            'type' => 'info',
            'read' => false
        ];
    }
}
