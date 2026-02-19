<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditActionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_report_id',
        'audit_scan_run_id',
        'title',
        'details',
        'severity',
        'status',
        'owner_name',
        'due_date',
        'created_by_admin_user_id',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }
}
