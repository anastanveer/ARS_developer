<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'website_url',
        'frequency',
        'status',
        'next_run_at',
        'last_run_at',
        'created_by_admin_user_id',
    ];

    protected function casts(): array
    {
        return [
            'next_run_at' => 'datetime',
            'last_run_at' => 'datetime',
        ];
    }
}
