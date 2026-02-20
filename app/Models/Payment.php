<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'invoice_id', 'amount', 'payment_date', 'method', 'reference', 'gateway_payment_id', 'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function reviews()
    {
        return $this->hasMany(ClientReview::class);
    }
}
