<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarangInventaris extends Model
{
    use SoftDeletes;

    protected $table = 'barang_inventaris';

    protected $fillable = [
        'kode_barang', 'nama_barang', 'merk', 'foto_barang', 'kategori_id',
        'stok_baik', 'stok_rusak_ringan', 'stok_rusak_berat', 'stok_hilang',
        'ruangan_id', 'tanggal_pengadaan', 'keterangan',
    ];

    /**
     * Get the total stock across all conditions.
     */
    public function getTotalStokAttribute()
    {
        return $this->stok_baik + $this->stok_rusak_ringan + $this->stok_rusak_berat + $this->stok_hilang;
    }

    protected $casts = [
        'tanggal_pengadaan' => 'date',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class, 'barang_id');
    }

    public function kelasPraktikums(): BelongsToMany
    {
        return $this->belongsToMany(KelasPraktikum::class, 'kelas_praktikum_barang', 'barang_id', 'kelas_praktikum_id');
    }

    public function penggunaanBarangs(): HasMany
    {
        return $this->hasMany(PenggunaanBarang::class, 'barang_id');
    }

    public function kategoriBarang(): BelongsTo
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }
}