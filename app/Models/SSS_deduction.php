<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sss_deduction extends Model
{
    use HasFactory;

    protected $table ='sss_deductions';
    protected $fillable = [
        
        'from',
        'to',
        'regular_ec',
        'wisp',
        'regular_ER',
        'regular_EE',
        'ECC',
        'wisp_ER',
        'wisp_EE',
        
    ];
}
