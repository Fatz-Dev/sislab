<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class KelasPraktikumMahasiswa extends Pivot
{
    protected $table = 'kelas_praktikum_mahasiswa';

    public $incrementing = true;

    protected $fillable = ['status', 'catatan_admin'];

    protected $casts = [
        'status' => 'string',
    ];

    // ─── Status Helpers ─────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    // ─── Relasi ─────────────────────────────────────────────

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_praktikum_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
