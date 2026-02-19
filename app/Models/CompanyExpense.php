<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_date',
        'category',
        'employee_name',
        'vendor_name',
        'description',
        'amount',
        'currency',
        'project_id',
        'notes',
        'created_by_admin_user_id',
    ];

    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
            'amount' => 'float',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
