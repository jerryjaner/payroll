<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pagibig_deduction extends Model
{
    use HasFactory;

    protected $table ='pagibig_deductions';
    protected $fillable = [
    'monthly_salary_from',
    'monthly_salary_to',
    'employees_share',
    'employer_share',

    ];
}
