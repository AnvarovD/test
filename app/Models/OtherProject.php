<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OtherProject extends Model
{
    use HasFactory;

    protected $casts = [
        'images' => 'collection',
        'data' => 'collection',
        'active' => 'bool'
    ];


    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'enable_or_disable_other_project_for_pages');
    }
}
