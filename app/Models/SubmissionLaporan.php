<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionLaporan extends Model
{
    protected $fillable = ['tugas_laporan_id', 'mahasiswa_id', 'file_laporan', 'tanggal_submit', 'status'];

    protected $casts = [
        'tanggal_submit' => 'datetime',
    ];

    public function tugasLaporan(): BelongsTo
    {
        return $this->belongsTo(TugasLaporan::class, 'tugas_laporan_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}