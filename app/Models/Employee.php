<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table ='employees';
    protected $fillable = [

        'user_id',
        'employee_name',
        'employee_address',
        'employee_birthday',
        'employee_contact_number',
        'emergency_contact_name',
        'emergency_contact_number',
        'sss_number',
        'pagibig_number',
        'philhealth_number',
        'image',
        'employee_no',
        'employee_position',
        'employee_department',
        'daily_rate',
        'monthly_rate',
        'sched_start',
        'sched_end',
        'date_hired',
        'qr',
        'base_salary',
        'employee_shift',
        'employee_allowance',
        'breaktime_start',
        'breaktime_end',
        
    ];

   

    //Relationship
    public function attendance(){

        return $this->hasOne(Attendance::class, 'emp_no', 'employee_no')->withDefault();
    }

    public function payroll(){

        return $this->hasOne(Attendance::class, 'employee_number', 'employee_no')->withDefault();
    }

    //Relation for Overtime
    public function OverTime(){

        return $this->hasOne(Overtime::class, 'emp_number', 'employee_no')->withDefault();
    }

    //RElation for User
    public function user(){
        
        return $this->belongsTo(User::class);
    }

}
