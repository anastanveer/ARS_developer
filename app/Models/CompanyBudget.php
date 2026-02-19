<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_month',
        'department',
        'budget_amount',
        'currency',
        'notes',
        'created_by_admin_user_id',
    ];

    protected function casts(): array
    {
        return [
            'budget_month' => 'date',
            'budget_amount' => 'float',
        ];
    }
}
