<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruangan extends Model
{
    protected $table = 'ruangans';

    protected $fillable = ['nama_ruangan', 'deskripsi'];

    public function barangInventaris(): HasMany
    {
        return $this->hasMany(BarangInventaris::class, 'ruangan_id');
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'ruangan_id');
    }
}
