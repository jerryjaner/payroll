<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    protected $table ='overtimes';
    protected $fillable = [
        'attendance_id',
        'emp_number',
        'hours_OT',
        'date',
        'reason',
        'isApproved_TL',
        'isApproved_HR',
        'isApproved_SVP',
        'isApproved_VPO',
        'isApproved_COO',
        'isDecline_HR',
        'isDecline_TL',
        'isDecline_SVP',
        'isDecline_VPO',
        'isDecline_COO',
        'RDOT',
        'SHOT',
        'RHOT',
        'RDSHOT',
        'RDRHOT'
        
    ];

    //Relationship of Employee
    public function employee(){

        return $this->belongsTo(Employee::class,  'emp_number', 'employee_no')->withDefault();
    }

    //relation to attendance
    public function Attendance(){

        return $this->belongsTo(Attendance::class,  'attendance_id', 'id')->withDefault();
    }

  

   
}
