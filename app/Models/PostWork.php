<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostWork extends Model
{
    use HasFactory;


    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }
}
