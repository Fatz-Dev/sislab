<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceLog extends Model
{
    protected $fillable = ['tanggal', 'deskripsi', 'status'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangInventaris::class, 'barang_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }
}