<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function workContents(): HasMany {
        return $this->hasMany(WorkContent::class);
    }

    public function workPosts(): HasMany {
        return $this->hasMany(PostWork::class);
    }

//    protected function macroImage(): Attribute
//    {
//        return Attribute::make(
//            get: fn (string $value) => env("APP_URL") . '/storage/' . $value,
//        );
//    }
//
//    protected function mediumImage(): Attribute
//    {
//        return Attribute::make(
//            get: fn (string $value) => env("APP_URL") . '/storage/' . $value,
//        );
//    }
//
//    protected function microImage(): Attribute
//    {
//        return Attribute::make(
//            get: fn (string $value) => env("APP_URL") . '/storage/' . $value,
//        );
//    }

//macro_image
//medium_image
//micro_image
}
