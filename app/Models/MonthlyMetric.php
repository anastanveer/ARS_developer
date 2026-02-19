<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'sales_amount',
        'work_value',
        'new_clients_count',
        'leads_count',
        'notes',
    ];

    protected $casts = [
        'month' => 'date',
    ];

    public function sourceMetrics()
    {
        return $this->hasMany(MonthlySourceMetric::class);
    }
}
