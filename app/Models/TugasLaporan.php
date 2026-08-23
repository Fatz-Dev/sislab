<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TugasLaporan extends Model
{
    protected $fillable = ['judul', 'deskripsi', 'deadline', 'jadwal_id'];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_praktikum_id');
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }

    public function submissionLaporans(): HasMany
    {
        return $this->hasMany(SubmissionLaporan::class, 'tugas_laporan_id');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'tugas_laporan_id');
    }
}