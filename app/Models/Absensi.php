<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensis';

    protected $fillable = [
        'kelas_praktikum_id', 'jadwal_id', 'user_id', 'diabsen_oleh',
        'tanggal', 'tipe', 'status_hadir',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_praktikum_id');
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function diabsenOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diabsen_oleh');
    }
}