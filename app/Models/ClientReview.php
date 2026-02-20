<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'project_id',
        'invoice_id',
        'payment_id',
        'review_token',
        'reviewer_name',
        'reviewer_email',
        'company_name',
        'rating',
        'review_title',
        'review_text',
        'result_summary',
        'is_approved',
        'approved_by_admin_user_id',
        'submitted_at',
        'approved_at',
        'email_sent_at',
        'submitted_ip',
        'submitted_country',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'email_sent_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(AdminUser::class, 'approved_by_admin_user_id');
    }
}

