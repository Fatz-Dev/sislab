<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanLaboran extends Model
{
    protected $table = 'laporan_laborans';

    protected $fillable = [
        'status_sop', 'kelayakan_barang', 'catatan_temuan', 'status_admin',
    ];

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }
}
