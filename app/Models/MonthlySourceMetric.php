<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySourceMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_metric_id',
        'source_name',
        'leads_count',
        'clients_count',
        'sales_amount',
    ];

    public function monthlyMetric()
    {
        return $this->belongsTo(MonthlyMetric::class);
    }
}
