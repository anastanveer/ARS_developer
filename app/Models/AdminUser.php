<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    use HasFactory;

    public const ROLE_SUPER = 'super_admin';
    public const ROLE_ADVANCED = 'advanced_admin';
    public const ROLE_BLOG = 'blog_seo_admin';

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public static function roles(): array
    {
        return [
            self::ROLE_SUPER,
            self::ROLE_ADVANCED,
            self::ROLE_BLOG,
        ];
    }
}
