<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'author_name',
        'excerpt',
        'content',
        'featured_image',
        'featured_image_alt',
        'published_at',
        'is_published',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_robots',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopeLive(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(function (Builder $nested) {
                $nested->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function isScheduled(): bool
    {
        return (bool) $this->is_published
            && $this->published_at !== null
            && $this->published_at->isFuture();
    }

    public function isLivePublicly(): bool
    {
        return (bool) $this->is_published
            && ($this->published_at === null || $this->published_at->lte(now()));
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class)->latest();
    }
}
