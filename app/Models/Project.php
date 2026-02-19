<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'title', 'type', 'status', 'start_date', 'delivery_date', 'delivery_months',
        'budget_total', 'paid_total', 'currency', 'portal_token', 'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'delivery_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function milestones()
    {
        return $this->hasMany(ProjectMilestone::class)->orderBy('sort_order');
    }

    public function requirements()
    {
        return $this->hasMany(ProjectRequirement::class)->latest();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class)->latest();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class)->latest('payment_date');
    }
}
