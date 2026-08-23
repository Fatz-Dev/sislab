<?php

namespace App\Events;

use App\Models\KelasPraktikumMahasiswa;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EnrollmentStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $enrollment;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(KelasPraktikumMahasiswa $enrollment, $message = null)
    {
        $this->enrollment = $enrollment;
        
        $kelas = $enrollment->kelasPraktikum->nama_kelas ?? 'Kelas Praktikum';
        $status = $enrollment->status === 'approved' ? 'disetujui' : 'ditolak';
        
        $this->message = $message ?? "Pendaftaran Anda pada kelas {$kelas} telah {$status}.";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('notifikasi.user.' . $this->enrollment->mahasiswa_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'enrollment_id' => $this->enrollment->id,
            'kelas_id' => $this->enrollment->kelas_praktikum_id,
            'status' => $this->enrollment->status,
            'message' => $this->message,
            'catatan_admin' => $this->enrollment->catatan_admin,
            'title' => 'Update Status Pendaftaran',
        ];
    }
}
