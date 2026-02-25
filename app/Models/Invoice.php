<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'invoice_number', 'client_invoice_number', 'invoice_date', 'due_date',
        'public_token', 'amount', 'paid_amount', 'status', 'notes',
        'invoice_payload', 'show_pay_button', 'sent_to_email', 'sent_at',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'invoice_payload' => 'array',
        'show_pay_button' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(ClientReview::class);
    }
}
