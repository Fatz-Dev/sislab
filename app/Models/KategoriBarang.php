<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBarang extends Model
{
    protected $table = 'kategori_barangs';

    protected $fillable = ['nama_kategori'];

    public function barangInventaris(): HasMany
    {
        return $this->hasMany(BarangInventaris::class, 'kategori_id');
    }
}
