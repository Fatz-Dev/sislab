<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laboran extends Model
{
    protected $table = 'laborans';

    protected $fillable = [
        'user_id', 'nip', 'spesialisasi_lab',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
