<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use SoftDeletes;

    protected $table = 'nilais';

    protected $fillable = [
        'mahasiswa_id', 'kelas_praktikum_id', 'tugas_laporan_id', 'laboran_id', 'nilai', 'keterangan',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_praktikum_id');
    }

    public function tugasLaporan(): BelongsTo
    {
        return $this->belongsTo(TugasLaporan::class, 'tugas_laporan_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }
}