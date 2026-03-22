<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_post_id',
        'full_name',
        'email',
        'website',
        'comment',
        'newsletter_opt_in',
        'is_approved',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'newsletter_opt_in' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
