<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Work extends Model
{
    use HasFactory;

    public function work(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'work_pivots',
            'work_id',
            'enable_work_id'
        );
    }
}
