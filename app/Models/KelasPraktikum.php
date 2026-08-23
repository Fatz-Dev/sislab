<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KelasPraktikum extends Model
{
    use SoftDeletes;

    protected $fillable = ['nama_kelas', 'semester_id', 'dosen_id', 'laboran_id', 'kapasitas', 'status', 'ruangan_id', 'hari', 'jam_mulai', 'jam_selesai'];

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }

    // Semua mahasiswa yang terdaftar (termasuk pending/rejected)
    public function mahasiswas(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kelas_praktikum_mahasiswa', 'kelas_praktikum_id', 'mahasiswa_id')
            ->using(KelasPraktikumMahasiswa::class)
            ->withPivot('id', 'status', 'catatan_admin')
            ->withTimestamps();
    }

    // Mahasiswa yang sudah di-approve
    public function approvedMahasiswas(): BelongsToMany
    {
        return $this->mahasiswas()->wherePivot('status', 'approved');
    }

    // Mahasiswa yang pending approval
    public function pendingMahasiswas(): BelongsToMany
    {
        return $this->mahasiswas()->wherePivot('status', 'pending');
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'kelas_praktikum_id');
    }

    public function modulPraktikums(): HasMany
    {
        return $this->hasMany(ModulPraktikum::class, 'kelas_praktikum_id');
    }

    public function tugasLaporans(): HasMany
    {
        return $this->hasMany(TugasLaporan::class, 'kelas_praktikum_id');
    }

    public function sopChecklists(): HasMany
    {
        return $this->hasMany(SopChecklist::class, 'kelas_praktikum_id');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'kelas_praktikum_id');
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class, 'kelas_praktikum_id');
    }

    public function barangInventaris(): BelongsToMany
    {
        return $this->belongsToMany(BarangInventaris::class, 'kelas_praktikum_barang', 'kelas_praktikum_id', 'barang_id');
    }

    public function laporanLaborans(): HasMany
    {
        return $this->hasMany(LaporanLaboran::class, 'kelas_praktikum_id');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    // ─── Scopes ──────────────────────────────────────────────

    /**
     * Scope: hanya kelas dengan status 'open' (bisa di-apply mahasiswa).
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope: kelas di semester yang sedang aktif.
     */
    public function scopeSemesterAktif(Builder $query): Builder
    {
        return $query->whereHas('semester', fn (Builder $q) => $q->where('is_active', true));
    }

    // ─── Enrollment Helpers ──────────────────────────────────

    /**
     * Jumlah mahasiswa yang sudah di-approve di kelas ini.
     */
    public function approvedCount(): int
    {
        return $this->approvedMahasiswas()->count();
    }

    /**
     * Sisa slot kapasitas yang tersedia.
     */
    public function availableSlots(): int
    {
        return max(0, $this->kapasitas - $this->approvedCount());
    }

    /**
     * Apakah kapasitas kelas sudah penuh (berdasarkan approved).
     */
    public function isFull(): bool
    {
        return $this->availableSlots() <= 0;
    }
}