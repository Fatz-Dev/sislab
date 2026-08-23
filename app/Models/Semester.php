<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    protected $table = 'semesters';

    protected $fillable = ['nama_semester', 'is_active', 'is_enrollment_open'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_enrollment_open' => 'boolean',
    ];

    public function kelasPraktikums(): HasMany
    {
        return $this->hasMany(KelasPraktikum::class, 'semester_id');
    }
}
