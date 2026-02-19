<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamHire extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'monthly_cost',
        'one_time_cost',
        'hired_on',
        'status',
        'notes',
    ];

    protected $casts = [
        'hired_on' => 'date',
    ];
}

