<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'business_name',
        'website_url',
        'recipient_name',
        'recipient_email',
        'overall_score',
        'performance_score',
        'seo_score',
        'ux_score',
        'security_score',
        'summary',
        'strengths',
        'issues',
        'recommendations',
        'estimated_timeline',
        'created_by_admin_user_id',
    ];

    protected function casts(): array
    {
        return [
            'overall_score' => 'integer',
            'performance_score' => 'integer',
            'seo_score' => 'integer',
            'ux_score' => 'integer',
            'security_score' => 'integer',
        ];
    }
}
