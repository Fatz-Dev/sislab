<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    protected $fillable = [
        'kelas_praktikum_id', 'tanggal', 'jam_mulai', 'jam_selesai',
        'ruangan_id', 'topik', 'status', 'created_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_praktikum_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class, 'jadwal_id');
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function laporanLaboran()
    {
        return $this->hasOne(LaporanLaboran::class, 'jadwal_id');
    }

    /**
     * Memperbarui status jadwal secara otomatis berdasarkan waktu saat ini.
     * Aturan:
     * - Sebelum jam mulai: terjadwal
     * - Dari jam mulai s.d (jam selesai + 30 menit toleransi): berlangsung
     * - Lewat dari (jam selesai + 30 menit): selesai
     */
    public function updateStatusOtomatis(): void
    {
        if ($this->status === 'dibatalkan') {
            return; // Jangan diubah jika sudah dibatalkan manual
        }

        $now = \Carbon\Carbon::now();
        $waktuMulai = \Carbon\Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . $this->jam_mulai);
        $waktuSelesai = \Carbon\Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . $this->jam_selesai);

        // Memberikan masa toleransi +30 menit untuk tetap berstatus berlangsung
        $waktuSelesaiToleransi = $waktuSelesai->copy()->addMinutes(30);

        $newStatus = 'terjadwal';
        
        if ($now >= $waktuMulai && $now <= $waktuSelesaiToleransi) {
            $newStatus = 'berlangsung';
        } elseif ($now > $waktuSelesaiToleransi) {
            $newStatus = 'selesai';
        }

        if ($this->status !== $newStatus) {
            $this->status = $newStatus;
            $this->save();
        }
    }
}