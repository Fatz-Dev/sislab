<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'photo', 'role', 'is_active', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'is_active'            => 'boolean',
            'is_profile_completed' => 'boolean',
        ];
    }

    // ─── Role Helpers ────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    public function isLaboran(): bool
    {
        return $this->role === 'laboran';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isProfileCompleted(): bool
    {
        return (bool) $this->is_profile_completed;
    }

    /**
     * Dapatkan nama route dashboard sesuai role user.
     */
    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'admin'     => 'admin.dashboard',
            'dosen'     => 'dosen.dashboard',
            'laboran'   => 'laboran.dashboard',
            'mahasiswa' => 'mahasiswa.dashboard',
            default     => 'login',
        };
    }

    // ─── Relasi ──────────────────────────────────────────────

    public function mahasiswaProfile()
    {
        return $this->hasOne(Mahasiswa::class, 'user_id');
    }

    public function dosenProfile()
    {
        return $this->hasOne(Dosen::class, 'user_id');
    }

    public function laboranProfile()
    {
        return $this->hasOne(Laboran::class, 'user_id');
    }

    // Relasi jika user adalah Dosen
    public function kelasSebagaiDosen(): HasMany
    {
        return $this->hasMany(KelasPraktikum::class, 'dosen_id');
    }

    // Relasi jika user adalah Laboran (1 laboran : 1 kelas)
    public function kelasSebagaiLaboran()
    {
        return $this->hasOne(KelasPraktikum::class, 'laboran_id');
    }

    // Relasi jika user adalah Mahasiswa (semua enrollment termasuk pending/rejected)
    public function kelasDiikuti(): BelongsToMany
    {
        return $this->belongsToMany(KelasPraktikum::class, 'kelas_praktikum_mahasiswa', 'mahasiswa_id', 'kelas_praktikum_id')
            ->using(KelasPraktikumMahasiswa::class)
            ->withPivot('status', 'catatan_admin')
            ->withTimestamps();
    }

    // Kelas yang sudah di-approve
    public function approvedKelas(): BelongsToMany
    {
        return $this->kelasDiikuti()->wherePivot('status', 'approved');
    }

    // Kelas yang masih pending approval
    public function pendingEnrollments(): BelongsToMany
    {
        return $this->kelasDiikuti()->wherePivot('status', 'pending');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'mahasiswa_id');
    }

    public function submissionLaporans(): HasMany
    {
        return $this->hasMany(SubmissionLaporan::class, 'mahasiswa_id');
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class, 'user_id');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class, 'laboran_id');
    }

    public function pengumumans(): HasMany
    {
        return $this->hasMany(Pengumuman::class, 'admin_id');
    }

    public function penggunaanBarangs(): HasMany
    {
        return $this->hasMany(PenggunaanBarang::class, 'laboran_id');
    }

    public function laporanLaborans(): HasMany
    {
        return $this->hasMany(LaporanLaboran::class, 'laboran_id');
    }

    public function setPasswordAttribute($value)
    {
        if (!$value) {
            return;
        }

        // If value already looks like a bcrypt/argon hash, keep it; otherwise hash
        if (Str::startsWith($value, ['$2y$', '$argon2i$', '$argon2id$'])) {
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}