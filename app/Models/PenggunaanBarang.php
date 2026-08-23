<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenggunaanBarang extends Model
{
    protected $fillable = [
        'jumlah_digunakan', 'kondisi_setelah', 'catatan',
    ];

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangInventaris::class, 'barang_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }
}
