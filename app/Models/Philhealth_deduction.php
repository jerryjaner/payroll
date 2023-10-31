<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class philhealth_deduction extends Model
{
    use HasFactory;

    protected $table ='philhealth_deductions';
    protected $fillable = [

    'monthly_basic_salary_from',
    'monthly_basic_salary_to',
    'premium_rate',
    'monthly_premium',
    
    ];
}
