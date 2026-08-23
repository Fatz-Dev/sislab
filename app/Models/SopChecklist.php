<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SopChecklist extends Model
{
    protected $fillable = [
        'tanggal', 'master_sop_id', 'status', 'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => 'boolean',
    ];

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_praktikum_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }

    public function masterSop(): BelongsTo
    {
        return $this->belongsTo(MasterSop::class, 'master_sop_id');
    }
}