<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table ='attendances';
    protected $fillable = [
        
        'emp_no',
        'work_hours',
        'time_in',
        'time_out',
        'date',
        // 'overtime_approval',
        'late_hours',
        'night_shift_date',
        'undertime_hours',
        'status',
        'absent',
        'onleave',
        'night_diff_hours',
        'RD',
        'reg_spe',
        'RDSH',
        'RDRH',
        'SH',
        'RH',
        'RDND',
        'SHND',
        'RHND',
        'RDSHND',
        'RDRHND'

    ];

   //Relationship
    public function employee(){

        return $this->belongsTo(Employee::class,  'emp_no', 'employee_no')->withDefault();
    }


    //realtion to overtime
    public function OverTime(){
        
        return $this->hasOne(Overtime::class, 'attendance_id', 'id')->withDefault();
    }

    


  
}
