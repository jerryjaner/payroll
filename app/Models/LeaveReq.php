<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveReq extends Model
{
    use HasFactory;

    protected $table ='leave_reqs';
    protected $fillable = [

        'leave_day',
        'user_id',
        'name',
        'start_date',
        'end_date',
        'leave_type',
        'department',
        'reason',
        'address',
        'contact',
        'person1',
        'person2',
        'is_TL_Approved',
        'is_HR_Approved',
        'is_SVP_Approved',
        'is_VPO_Approved',
        'is_COO_Approved',
        'is_TL_Decline',
        'is_HR_Decline',
        'is_SVP_Decline',
        'is_VPO_Decline',
        'is_COO_Decline',
    ];

    public function employee(){

        return $this->belongsTo(Employee::class,  'emp_number', 'employee_no')->withDefault();
    }

 
}
