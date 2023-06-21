<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use MoonShine\Tests\Fixtures\Models\Category;

/**
 * @property Post[]|Collection $posts
 * @property OtherProject[]|Collection $otherProjects
 * @property PageContent $pageContent
 */
class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_uz',
        'title_en',
        'title_ru',
        'parent_id',
    ];

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function parents(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id', 'id');
    }


    /**
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return HasOne
     */
    public function pageContent(): HasOne
    {
        return $this->hasOne(PageContent::class);
    }

    /**
     * @return BelongsToMany
     */
    public function otherProjects(): BelongsToMany
    {
        return $this->belongsToMany(OtherProject::class, 'enable_or_disable_other_project_for_pages');
    }
}
