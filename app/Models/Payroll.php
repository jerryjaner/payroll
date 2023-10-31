<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $table ='payrolls';
    protected $fillable = [
        
        'employee_name',
        'employee_number',
        'daily_rate',
        'start_date',
        'end_date',
        'rate_per_hour',
        // 'total_workhour',
        'gov_contribution',
        'undertime_total',
        'late_total',
        'net_pay',
        'payment_type',
        'sss_deduction',
        'pag_ibig_deduction',
        'philhealth_deduction',
        'pay_date',
        'allowance',
        'gross',
        'cash_advance',
        'night_diff',
        'employee_absent',
        'late_undertime',
        'restday',
        'special_holiday',
        'regular_holiday',
        'restday_special_holiday',
        'restday_regular_holiday',
        'overtime',
        'restday_overtime',
        'special_holiday_overtime',
        'regular_holiday_overtime',
        'restday_special_holiday_overtime',
        'restday_regular_holiday_overtime',

        
        
    ];
    public function employee(){
        return $this->belongsTo(Employee::class,  'employee_number', 'employee_no')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
