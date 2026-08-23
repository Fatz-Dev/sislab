<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterSop extends Model
{
    protected $table = 'master_sops';

    protected $fillable = ['item_checklist'];

    public function sopChecklists(): HasMany
    {
        return $this->hasMany(SopChecklist::class, 'master_sop_id');
    }
}
