<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditScanRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_target_id',
        'business_name',
        'website_url',
        'overall_score',
        'performance_score',
        'seo_score',
        'ux_score',
        'security_score',
        'grade',
        'risk_level',
        'response_time_ms',
        'findings_json',
        'scanned_at',
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
            'response_time_ms' => 'integer',
            'findings_json' => 'array',
            'scanned_at' => 'datetime',
        ];
    }
}
