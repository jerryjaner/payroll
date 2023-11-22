<?php

namespace App\Http\Controllers;

use DB;
use DateTime;
use Carbon\Carbon;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\LeaveReq;
use App\Models\Overtime;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        // if(Auth::user()->hasRole(['HR','attendance','accounting','assistantHR'])){

           
            // $employee = LeaveReq::where('is_HR_Approved', '=', '1')
            //                     ->get();

       

            $alleave_reqs = array();
            $allReq = LeaveReq::where('is_HR_Approved', '=', '1')
                              ->get();

            foreach ($allReq as $kalendaryo) {
                $alleave_reqs[] = [

                    'title' => $kalendaryo->name.''.'-'.''.$kalendaryo->leave_type,
                    'start' => $kalendaryo->start_date,
                    'end' => Carbon::parse($kalendaryo->end_date)->addDay()->toDateString(),

                ];
            }
            if ($alleave_reqs){

               // return view('attendance.index', compact('employee', 'alleave_reqs', 'allReq'));
                return view('attendance.index', compact('alleave_reqs', 'allReq'));
            }

           //  return view('attendance.index', compact('employee', 'alleave_reqs', 'allReq'));

             return view('attendance.index', compact('alleave_reqs', 'allReq'));

        //}
    }


    public function today_attendance(){
        
         $attendance_todays = Attendance::whereDate('created_at', Carbon::today())
                                        ->where('absent', false)
                                        ->where('status', '=', null)
                                        ->with('employee')
                                        ->get(['id','time_in','time_out','emp_no','date','created_at','updated_at']);

		$output = '';
		if ($attendance_todays ->count() > 0) {

			$output .= '<table class="tracking-tbl table" style="width: 100%" id="tracking_table">
            <thead>
              <tr>
                <th hidden> </th>
              </tr>
            </thead>
            <tbody>';
			foreach ($attendance_todays as $attendance_today) {

                $output .=
                ' <tr>
                    <td>
                        <div class="card time-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-2">
                                        <div class="d-flex">
                                            <p class="" hidden >'.$attendance_today -> updated_at.'</p>
                                            <p class="" hidden>'.$attendance_today -> id.'</p>';


                                            if($attendance_today -> employee -> image != null){

                                                $output .=  '<img src="storage/employee/images/' . $attendance_today -> employee -> image . '" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:50px; height: 50px;">';

                                            }
                                            else{

                                                $output .= '<i class="bx bx-user icon profile-picture-time"> </i>';
                                            }

                                        $output .= '  </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <h5 class="emp-name">'.$attendance_today -> employee -> employee_name.'</h5>
                                        <p class="emp-no"> Name</p>
                                        <p class="emp-no" hidden>'.$attendance_today->emp_no.'</p>
                                    </div>
                                    <div class="col-xl-2 td-div">';

                                        $output .=  '<h5 class="time">'.Carbon::parse($attendance_today->time_in)->format('g:i A').'</h5>';
                                        $output .= '<p class="type">Time In</p>';
                                    $output .='</div>
                                    <div class="col-xl-2 td-div">';
                                    if($attendance_today->time_out == null){

                                        $output .=  '<h5 class="time">--:--</h5>';
                                        $output .= '<p class="type">Time Out</p>';
                                    }
                                    else
                                    {
                                        $output .=  '<h5 class="time">'.Carbon::parse($attendance_today->time_out)->format('g:i A').'</h5>
                                                     <p class="type">Time Out</p>';
                                    }

                                    $output .='</div>
                                    <div class="col-xl-2 td-div d-flex justify-content-end align-items-center">';


                                        // if($attendance_today->time_in <= $Employee_time_in_AM){

                                        //     $output .=' <span class="status on-time d-flex align-items-center">
                                        //                     <i class="bx bx-time"></i>
                                        //                     On Time
                                        //                 </span>';
                                        // }
                                        // else
                                        // {
                                        //     $output .=' <span class="status late d-flex align-items-center">
                                        //                     <i class="bx bx-x"></i>
                                        //                     Late
                                        //                 </span>';

                                        // }
                                        if($attendance_today -> employee -> sched_start == $attendance_today->time_in){
                                            $output .=' <div class="time bg-success d-flex align-items-center justify-content-center" style="color:#fff; margin:2px; border-radius:100%; width:15px; height:15px; padding:10px;">
                                                            <i class="bx bx-check"></i>
                                                        </div>
                                                        ';
                                        }
                                        else
                                        {
                                            $output .=' <div class="emp-no bg-danger d-flex align-items-center justify-content-center" style="color:#fff; margin:2px; border-radius:100%; width:15px; height:15px; padding:10px;">
                                                            L
                                                        </div>
                                                        ';
                                        }
                         $output .='</div>
                         <div class="col-xl-1 td-div d-flex justify-content-end align-items-center">';

                                        if( $attendance_today->time_out != null  && Carbon::parse($attendance_today->time_out) < Carbon::parse($attendance_today -> employee -> sched_end)){
                                            $output .=' <div class="emp-no bg-warning d-flex align-items-center justify-content-center" style="color:#fff; margin:2px; border-radius:100%; width:15px; height:15px; padding:10px;">
                                                            UT
                                                        </div>
                                                        ';
                                        }

                         $output .='</div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>';

			}
			$output .= '</tbody></table>';
			echo $output;

		}

        else {

			echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
		}


    }

    public function AllAttendance(){

        $all_attendances = Attendance::with('employee')->get();
        // $all_attendances = Attendance::all();

        $Employee_time_in_AM = "08:00:00";
        $Employee_time_out_PM = "17:00:00";

        $output = '';
        if ($all_attendances->count() > 0) {

	 		$output .= '<table class="atendance-tbl table" style="width: 100%" id="attendance_table">
            <thead>
            <tr>
                <th hidden> </th>
            </tr>
            </thead>
            <tbody>';
            foreach ($all_attendances as $all_attendance) {

                $output .= '<tr>
                                <td>
                                    <div class="card time-card">
                                        <div class="card-body">
                                            <div class="row  d-flex align-items-center justify-content-center">
                                                <div class="col-xl-2">
                                                    <div class="d-flex">
                                                    <p class="attt_id" hidden>'.$all_attendance->id.'</p>';

                                                    if($all_attendance -> employee -> image != null){

                                                        $output .=  '<img src="storage/employee/images/' . $all_attendance -> employee -> image . '" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:50px; height: 50px;">';

                                                    }
                                                    else{

                                                        $output .= '<i class="bx bx-user icon profile-picture-time"> </i>';
                                                    }

                                                $output .= '  </div>
                                                </div>
                                                <div class="col-xl-2">
                                                    <h5 class="emp-name">'.$all_attendance->employee->employee_name.'</h5>
                                                    <p class="emp-no">'.$all_attendance->emp_no.'</p>
                                                </div>
                                                <div class="col-xl-2">
                                                    <h5 class="emp-name">'.Carbon::parse($all_attendance->date)->format('M d Y').'</h5>
                                                    <p class="emp-no"> Date </p>
                                                 </div>
                                                <div class="col-xl-2 td-div">';
                                                    
                                                    if($all_attendance -> status == "absent"){

                                                        $output .=  '<h5 class="time">00:00</h5>';
                                                        $output .= '<p class="type">Time In</p>';
                                                    }
                                                    else if($all_attendance -> status == "onleave"){

                                                        $output .=  '<h5 class="time">00:00</h5>';
                                                        $output .= '<p class="type">Time In</p>';
                                                    }
                                                    else{
                                                        $output .=  '<h5 class="time">'.date('h:i A', strtotime($all_attendance->time_in)).'</h5>';
                                                        $output .= '<p class="type">Time In</p>';
                                                    
                                                    }
                                     $output .='</div>
                                                <div class="col-xl-2 td-div">';
                                                 
                                                    if($all_attendance -> status == "absent"){

                                                        $output .=  '<h5 class="time">00:00</h5>';
                                                        $output .= '<p class="type">Time out</p>';
                                                    }
                                                    else if($all_attendance -> status == "onleave"){

                                                        $output .=  '<h5 class="time">00:00</h5>';
                                                        $output .= '<p class="type">Time out</p>';
                                                    }
                                                    else{

                                                    
                                                        if($all_attendance->time_out == null){

                                                            $output .=  '<h5 class="time">--:--</h5>';
                                                            $output .= '<p class="type">Time Out</p>';
                                                        }
                                                        else
                                                        {
                                                            // $output .=  '<h5 class="time">'.Carbon::parse($all_attendance->time_out)->format('H:i A').'</h5>';
                                                            $output .=  '<h5 class="time">'.date('h:i A', strtotime($all_attendance->time_out)).'</h5>';
                                                            $output .= '<p class="type">Time Out</p>';
                                                        }
                                                    }

                                                $output .='</div>
                                                <div class="col-xl-2 d-flex justify-content-end align-items-center">';

                                                        if($all_attendance -> status == "onleave"){

                                                            $output .=' <span class="status bg-warning d-flex align-items-center text-center">
                                                                            <i class="bx bx-calendar-check"></i>
                                                                                On leave
                                                                        </span>';
                                                        }
                                                        else if($all_attendance -> status == "absent"){

                                                            $output .=' <span class="status bg-warning d-flex align-items-center text-center">
                                                                            <i class="bx bx-alarm-off"></i>
                                                                               Absent
                                                                         </span>';
                                                        }
                                                        else{

                                                            if($all_attendance -> employee -> sched_start == $all_attendance -> time_in){
                                                                $output .=' <span class="status on-time d-flex align-items-center">
                                                                                <i class="bx bx-check"></i>
                                                                                On Time
                                                                            </span>';
                                                            }
                                                            else
                                                            {
                                                                $output .=' <span class="status late d-flex align-items-center text-center">
                                                                                <i class="bx bx-x"></i>
                                                                                Late
                                                                            </span>';
                                                            }
                                                        }
                                                
                                                 
                                                $output .='</div>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>';

            }
            $output .= '</tbody></table>';
            echo $output;

        }
        else
        {
            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }

    }

    public function first_scanned(){

        $scanned = Attendance::whereDate('created_at', Carbon::today())
                            ->where('status', '=', null)
                             ->with('employee')
                             ->latest('updated_at')->first();

        $data = '';
		if ($scanned->count() > 0) {

            $data .= '<div class="d-flex align-items-center" id="first_employee">';
                            if($scanned -> employee -> image != null){

                                $data .=  '<img src="storage/employee/images/' . $scanned -> employee -> image . '" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:50px; height: 50px;">';

                            }
                            else{

                                $data .= '<i class="bx bx-user icon profile-picture-time"> </i>';
                            }


                 $data .= '<div class="ms-3">
                                <h5 class="emp-name">'.$scanned -> employee -> employee_name.'</h5>
                                <p class="emp-no"> Name</p>
                                <p class="emp-no" hidden>'.$scanned -> emp_no.'</p>
                                <span class="title">
                                    <i class="bx bxs-user-badge"></i>
                                    '.$scanned -> employee -> employee_position.'
                                </span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button class="btn-view" data-tippy-content="View Profile" data-tippy-arrow="false">
                                <i class="bx bx-user-circle"></i>
                            </button>
                        </div>';

            echo $data;
		}
        else {

			echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
		}


    }

    public function attendance_record(Request $request){

        $validator = \Validator::make($request -> all(),[

            //Check if the scanned Employee id is existed in database
             'scanned' => 'required|exists:employees,employee_no',

        ],
        [
            //Custom Error Message
            'scanned.exists' => 'Employee number is not in database.',
            'scanned.required' => 'No employee number scanned.',
        ]);

         if($validator -> fails())
         {

             // if the validator fails
             return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);

         }
         else if($validator){


                // $attendance_data = Attendance::with('employee', 'overtime')->get(['id','emp_no','time_in','date','time_out','late_hours','night_shift_date','undertime_hours']);
                // $schedules = Employee::get(['id','sched_start', 'sched_end','employee_no','employee_shift']);

                $attendance_data = Attendance::with('employee', 'overtime')->get();
                $schedules = Employee::get(['id','sched_start', 'sched_end','employee_no','employee_shift','breaktime_start','breaktime_end']); 

                $holidays = Holiday::all();

                $date = Carbon::now()->toDateString();
               // $employees = Employee::get('employee_no');


                foreach($schedules as $shift)
                {

                    // ===================================================== DAY SHIFT =================================
                    if($shift -> employee_no == $request -> scanned && $shift -> employee_shift == "Day")
                    {

                        foreach ($attendance_data as $data)
                        {

                            if($data -> emp_no == $request -> scanned && $data -> date == Carbon::now('GMT+8')->format('Y-m-d')  ){

                                //if Overtime Approve
                                // if($data -> overtime -> isApproved_TL == '1' && $data -> overtime -> isApproved_HR == '1' && $data -> overtime -> isApproved_SVP == '1' ) ITO yung luma

                                //BAgong ccondition na pag ok kay HR then maKAKA ot NA  SYA
                                if($data -> overtime -> isApproved_HR == '1') 
                                {

                                    //check if the time in, time out and hours OT is not null
                                    if($data -> time_in != null && $data -> time_out != null && $data -> overtime -> hours_OT != null )
                                    {

                                        return response()->json([
                                            'status'=> 0,
                                            'error' => 'Time in and Time out is already completed'
                                        ]);

                                    }
                                    else
                                    {

                                        //START OF UNDERTIME

                                        $timee = Carbon::createFromTime(11, 00, 00, 'GMT+8');
                                        $timeOUT2 = Carbon::parse($timee)->format('H:i:s');//declared timeout for testing or debugging only

                                        $timeee = Carbon::now('GMT+8')->format('H:i:s');
                                        $timeOUT111 = Carbon::parse($timeee)->format('H:i:s');//realtime used in timeout


                                        Attendance::where('emp_no', '=', $request -> scanned)
                                                  ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                  ->update(['time_out' => $timeOUT111]);




                                        //FOR THE UNDERTIME
                                        $attend11 = Attendance::where('emp_no', '=', $request -> scanned)
                                                              ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                              ->with('employee')->get();

                                        foreach($attend11 as $attends)
                                        {

                                             //-------------- IF UNDERTIME IS TRUE (NAG OUT NG MAAGA) --------------------------------//
                                            if($attends->time_out < $attends->employee->sched_end && $attends->time_out > $attends->time_in)
                                            {

                                                //$PMtime = Carbon::createFromTime(12, 00, 00, 'GMT+8')->format('H:i:s');//declared breaktime
                                                $PMtime = Carbon::parse($attends ->employee-> breaktime_start); //breaktime from employee database
                                                $breakTime = Carbon::parse($PMtime)->format('H:i:s');

                                                if($attends->time_out <= $breakTime)//if timeout is less than breaktime (nag out dire pa breaktime)
                                                {
                                                  
                                                    $sched_Out = Carbon::parse($attends ->employee-> sched_end)->subHour(1);
                                                    $UTDiff = $sched_Out->diffInSeconds($attends->time_out);
                                                    $UTime = gmdate('H:i:s', $UTDiff);

                                                    $startTime = Carbon::parse($attends -> time_in);
                                                    $endTime = Carbon::parse($attends -> time_out);
                                                    $interval = $startTime->diffInSeconds($endTime);
                                                    $totalDuration = gmdate('H:i:s', $interval);

                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                              ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                              ->update(['undertime_hours'=>$UTime,
                                                                        'work_hours' => $totalDuration]);

                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);
                                                }
                                                else if($attends->time_out >= $breakTime && $attends->time_in <= $breakTime)//if timeout is greater than breaktime (nag out pero tapos na breaktime)
                                                {
                                          
                                                    $sched_Out = Carbon::parse($attends ->employee-> sched_end);
                                                    $UTDiff = $sched_Out->diffInSeconds($attends->time_out);
                                                    $UTime = gmdate('H:i:s', $UTDiff);

                                                    $startTime = Carbon::parse($attends -> time_in);
                                                    $endTime = Carbon::parse($attends -> time_out)->subHour(1);
                                                    $interval = $startTime->diffInSeconds($endTime);
                                                    $totalDuration = gmdate('H:i:s', $interval);

                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                              ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                              ->update(['undertime_hours'=>$UTime,
                                                                       'work_hours' => $totalDuration]);

                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                }
                                                else if($attends->time_out >= $breakTime && $attends->time_in >= $breakTime)
                                                {

                                                   
                                                    $sched_Out = Carbon::parse($attends ->employee-> sched_end);
                                                    $UTDiff = $sched_Out->diffInSeconds($attends->time_out);
                                                    $UTime = gmdate('H:i:s', $UTDiff);

                                                    $startTime = Carbon::parse($attends -> time_in);
                                                    $endTime = Carbon::parse($attends -> time_out);
                                                    $interval = $startTime->diffInSeconds($endTime);
                                                    $totalDuration = gmdate('H:i:s', $interval);

                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                              ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                              ->update(['undertime_hours'=>$UTime,
                                                                        'work_hours' => $totalDuration]);

                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);
                                                }

                                            }
                                            else if($attends->time_out <= $attends->time_in && $attends->time_out < $attends->employee->sched_end)
                                            {
                                              
                                                $sched_Out = Carbon::parse($attends ->employee-> sched_end)->subHour(1);
                                                $UTDiff = $sched_Out->diffInSeconds($attends->time_in);
                                                $UTime = gmdate('H:i:s', $UTDiff);

                                                $wHour = Carbon::createFromTime(0, 0, 0, 'GMT+8');
                                                $wHour1 = Carbon::parse($wHour)->format('H:i:s');
                                                // $timeOut = Carbon::parse($attends->time_out);
                                                // $timeIN = Carbon::parse($attends->time_in);
                                                // $sched_Out = Carbon::parse($attends ->employee-> sched_end);
                                                // $UTDiff = $sched_Out->diffInSeconds($timeOut);
                                                // $UTime = gmdate('H:i:s', $UTDiff);

                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update(['undertime_hours'=>$UTime,
                                                                    'time_out'=>$attends->time_in,
                                                                    'work_hours' => $wHour1]);

                                                return response()->json([
                                                    'status' => 200,
                                                    'msg' => 'Attendance updated Successfully',
                                                ]);
                                            }
                                            else
                                            {
                                              
                                                $endTime = Carbon::parse($attends -> time_out);
                                                $schedule_end = Carbon::parse($attends -> employee -> sched_end);
                                                $interval = $schedule_end->diffInSeconds($endTime);
                                                $total_overtime = gmdate('H:00:00', $interval);

                                                //update the hours_ot based on the emp_number and also the attendance id of the employee
                                                switch($data){

                                                    //Rest dsay
                                                    case $data -> RD == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => $total_overtime,
                                                                          'RDOT' => true]);
                                                    break;

                                                    //rest day special holiday
                                                    case $data -> SH == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => $total_overtime,
                                                                          'SHOT' => true]);
                                                    break;

                                                        //rest day regular holiday
                                                        case $data -> RH == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => $total_overtime,
                                                                          'RHOT' => true]);
                                                    break;

                                                    //rest day special holiday
                                                    case $data -> RDSH == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => $total_overtime,
                                                                          'RDSHOT' => true]);
                                                    break;
                                                    
                                                    //rest day regular holiday
                                                    case $data -> RDRH == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => $total_overtime,
                                                                          'RDRHOT' => true]);
                                                    break;
                                                    
                                                    default:
                                                            Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => $total_overtime]);
                                                    break;

                                                }
                                                //Overtime::where('emp_number', '=', $request -> scanned)->where('attendance_id', '=', $data -> id)->update(['hours_OT' => $total_overtime]);

                                                //$PMtime = Carbon::createFromTime(12, 00, 00, 'GMT+8')->format('H:i:s');//declared breaktime
                                                $PMtime = Carbon::parse($attends ->employee-> breaktime_start); //breaktime from employee database
                                                $breakTime1 = Carbon::parse($PMtime)->format('H:i:s');

                                                if($attends->time_in < $breakTime1)
                                                {
                                                    $endTime2 = Carbon::parse($attends -> employee -> sched_end)->subHour(1);
                                                    $startTime = Carbon::parse($attends->time_in);
                                                    $interval2 = $startTime->diffInSeconds($endTime2);
                                                    $totalDuration = gmdate('H:i:s', $interval2);

                                                    // dd($totalDuration);
                                                    Attendance::where('emp_no', '=', $request -> scanned)->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                                                         ->update(['work_hours' => $totalDuration]);
                                                }
                                                else    
                                                {
                                                    $endTime2 = Carbon::parse($attends -> employee -> sched_end);
                                                    $startTime = Carbon::parse($attends->time_in);
                                                    $interval2 = $startTime->diffInSeconds($endTime2);
                                                    $totalDuration = gmdate('H:i:s', $interval2);

                                                    // dd($totalDuration);
                                                    Attendance::where('emp_no', '=', $request -> scanned)->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                                                        ->update(['work_hours' => $totalDuration]);
                                                }
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);
                                            }
                                        }

                                        return response()->json([
                                            'status' => 200,
                                            'msg' => 'Attendance updated Successfully',
                                        ]);
                                    }
                                }
                                else
                                {
                                    //Check if the time out and time in is not null
                                    if($data -> time_in != null && $data -> time_out != null ){

                                        return response()->json([
                                            'status'=>0,
                                            'error' => 'Time in and Time out is already completed'
                                        ]);
                                    }
                                    else
                                    {

                                        //request OT but not Approved
                                        //if($data -> overtime -> isApproved_TL == '0' || $data -> overtime -> isApproved_HR == '0' || $data -> overtime -> isApproved_SVP == '0' ) Luma Rin ito
                                        if($data -> overtime -> isApproved_HR == '0')
                                        {

                                            $timee = Carbon::createFromTime(9, 00, 00, 'GMT+8');
                                            $timeOUT3 = Carbon::parse($timee)->format('H:i:s');//declared and for testing or debugging only

                                            $timeee = Carbon::now('GMT+8')->format('H:i:s');
                                            $timeOUT11 = Carbon::parse($timeee)->format('H:i:s');//realtime used in timeout


                                            Attendance::where('emp_no', '=', $request -> scanned)->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                                                ->update(['time_out' => $timeOUT11]);

                                                                
                                            if(Carbon::now('GMT+8')->format('H:i:s') > $data->employee->sched_end){

                                                $endTime = Carbon::now('GMT+8')->format('H:i:s');
                                                $schedule_end = Carbon::parse($data -> employee -> sched_end);
                                                $interval = $schedule_end->diffInSeconds($endTime);
                                                $total_overtime = gmdate('H:00:00', $interval);

                                                //update the hours_ot based on the emp_number and also the attendance id of the employee
                                                switch($data){

                                                    //Rest dsay
                                                    case $data -> RD == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => $total_overtime,
                                                                          'RDOT' => true]);
                                                    break;

                                                    //rest day special holiday
                                                    case $data -> SH == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => $total_overtime,
                                                                          'SHOT' => true]);
                                                    break;

                                                        //rest day regular holiday
                                                        case $data -> RH == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => $total_overtime,
                                                                          'RHOT' => true]);
                                                    break;

                                                    //rest day special holiday
                                                    case $data -> RDSH == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => $total_overtime,
                                                                          'RDSHOT' => true]);
                                                    break;
                                                    
                                                    //rest day regular holiday
                                                    case $data -> RDRH == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => $total_overtime,
                                                                         'RDRHOT' => true]);
                                                    break;
                                                    
                                                    default:
                                                         Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => $total_overtime]);
                                                    break;
                                                }
                                            }
                                            else
                                            {
                                                switch($data){
                                                    //Rest day
                                                    case $data -> RD == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => '00:00:00',
                                                                          'RDOT' => true]);
                                                    break;

                                                    //rest day special holiday
                                                    case $data -> SH == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => '00:00:00',
                                                                           'SHOT' => true]);
                                                    break;

                                                        //rest day regular holiday
                                                        case $data -> RH == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => '00:00:00',
                                                                            'RHOT' => true]);
                                                    break;

                                                    //rest day special holiday
                                                    case $data -> RDSH == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => '00:00:00',
                                                                           'RDSHOT' => true]);
                                                    break;
                                                    
                                                    //rest day regular holiday
                                                    case $data -> RDRH == true:

                                                        Overtime::where('emp_number', '=', $request -> scanned)
                                                                ->where('attendance_id', '=', $data -> id)
                                                                ->update(['hours_OT' => '00:00:00',
                                                                        'RDRHOT' => true]);
                                                    break;
                                                    
                                                    default:
                                                            Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => '00:00:00']);
                                                    break;
                                                }
                                            }
                                            
                                            
                                            $attend1 = Attendance::where('emp_no', '=', $request -> scanned)
                                                                 ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                 ->with('employee')->get();
                                   
                                            foreach($attend1 as $attends)
                                            {
                                                ////////////////IF UNDERTIME IS TRUE (NAG OUT NG MAAGA)
                                                if($attends->time_out < $attends->employee->sched_end && $attends->time_out > $attends->time_in)
                                                {

                                                   

                                                    //$PMtime = Carbon::createFromTime(12, 00, 00, 'GMT+8')->format('H:i:s');//declared breaktime
                                                    $PMtime = Carbon::parse($attends ->employee-> breaktime_start); //breaktime from employee database
                                                    $breakTime = Carbon::parse($PMtime)->format('H:i:s');

                                                    if($attends->time_out <= $breakTime)//if timeout is less than breaktime (nag out dire pa breaktime)
                                                    {
                                                        $sched_Out = Carbon::parse($attends ->employee-> sched_end)->subHour(1);
                                                        $UTDiff = $sched_Out->diffInSeconds($attends->time_out);
                                                        $UTime = gmdate('H:i:s', $UTDiff);

                                                        $startTime = Carbon::parse($attends -> time_in);
                                                        $endTime = Carbon::parse($attends -> time_out);
                                                        $interval = $startTime->diffInSeconds($endTime);
                                                        $totalDuration = gmdate('H:i:s', $interval);

                                                        Attendance::where('emp_no', '=', $request -> scanned)
                                                                  ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                  ->update(['undertime_hours'=>$UTime,
                                                                              'work_hours' => $totalDuration]);

                                                        return response()->json([
                                                            'status' => 200,
                                                            'msg' => 'Attendance updated Successfully',
                                                        ]);
                                                    }
                                                    //if timeout is greater than breaktime (nag out pero tapos na breaktime)
                                                    else if($attends->time_out >= $breakTime && $attends->time_in <= $breakTime)
                                                    {
                                                        
                                                        $sched_Out = Carbon::parse($attends ->employee-> sched_end);
                                                        $UTDiff = $sched_Out->diffInSeconds($attends->time_out);
                                                        $UTime = gmdate('H:i:s', $UTDiff);

                                                        $startTime = Carbon::parse($attends -> time_in);
                                                        $endTime = Carbon::parse($attends -> time_out)->subHour(1);
                                                        $interval = $startTime->diffInSeconds($endTime);
                                                        $totalDuration = gmdate('H:i:s', $interval);

                                                        Attendance::where('emp_no', '=', $request -> scanned)
                                                                  ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                  ->update(['undertime_hours'=>$UTime,
                                                                            'work_hours' => $totalDuration]);

                                                        return response()->json([
                                                            'status' => 200,
                                                            'msg' => 'Attendance updated Successfully',
                                                        ]);
                                                    }
                                                    else if($attends->time_out >= $breakTime && $attends->time_in >= $breakTime)
                                                    {
                                                       
                                                        $sched_Out = Carbon::parse($attends ->employee-> sched_end);
                                                        $UTDiff = $sched_Out->diffInSeconds($attends->time_out);
                                                        $UTime = gmdate('H:i:s', $UTDiff);

                                                        $startTime = Carbon::parse($attends -> time_in);
                                                        $endTime = Carbon::parse($attends -> time_out);
                                                        $interval = $startTime->diffInSeconds($endTime);
                                                        $totalDuration = gmdate('H:i:s', $interval);

                                                        Attendance::where('emp_no', '=', $request -> scanned)
                                                                 ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                 ->update(['undertime_hours'=>$UTime,
                                                                          'work_hours' => $totalDuration]);

                                                        return response()->json([
                                                            'status' => 200,
                                                            'msg' => 'Attendance updated Successfully',
                                                        ]);
                                                    }

                                                }
                                                elseif($attends->time_out <= $attends->time_in && $attends->time_out < $attends->employee->sched_end)
                                                {
                                                    
                                                    $sched_Out = Carbon::parse($attends ->employee-> sched_end)->subHour(1);
                                                    $UTDiff = $sched_Out->diffInSeconds($attends->time_in);
                                                    $UTime = gmdate('H:i:s', $UTDiff);

                                                    $wHour = Carbon::createFromTime(0, 0, 0, 'GMT+8');
                                                    $wHour1 = Carbon::parse($wHour)->format('H:i:s');
                                                    // $timeOut = Carbon::parse($attends->time_out);
                                                    // $timeIN = Carbon::parse($attends->time_in);
                                                    // $sched_Out = Carbon::parse($attends ->employee-> sched_end);
                                                    // $UTDiff = $sched_Out->diffInSeconds($timeOut);
                                                    // $UTime = gmdate('H:i:s', $UTDiff);

                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                              ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                             ->update(['undertime_hours'=>$UTime,'time_out'=>$attends->time_in,
                                                                       'work_hours' => $wHour1]);

                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                }
                                                else
                                                {
                                                    $PMtime = Carbon::createFromTime(12, 00, 00, 'GMT+8')->format('H:i:s');//declared breaktime
                                                    $breakTime1 = Carbon::parse($PMtime)->format('H:i:s');

                                                    if($attends->time_in < $breakTime1)
                                                    {
                                                     
                                                        $startTime = Carbon::parse($attends -> time_in);
                                                        $endTime = Carbon::parse($attends ->employee->sched_end)->subHour(1);
                                                        $interval = $startTime->diffInSeconds($endTime);
                                                        $totalDuration1 = gmdate('H:i:s', $interval);

                                                        Attendance::where('emp_no', '=', $request -> scanned)
                                                                  ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                 ->update(['work_hours' => $totalDuration1]);
                                                    }
                                                    else
                                                    {
                                                       
                                                        $startTime = Carbon::parse($attends -> time_in);
                                                        $endTime = Carbon::parse($attends ->employee->sched_end);
                                                        $interval = $startTime->diffInSeconds($endTime);
                                                        $totalDuration1 = gmdate('H:i:s', $interval);

                                                        Attendance::where('emp_no', '=', $request -> scanned)
                                                                  ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                  ->update(['work_hours' => $totalDuration1]);
                                                    }

                                                        return response()->json([
                                                            'status' => 200,
                                                            'msg' => 'Attendance updated Successfully',
                                                        ]);
                                                }
                                            }

                                            return response()->json([
                                                'status' => 200,
                                                'msg' => 'Attendance updated Successfully',
                                            ]);
                                        }
                                        /////////////////UNDERTIME WITHOUT OT
                                        else
                                        {

                                            if(Carbon::now('GMT+8')->format('H:i')   >=   $data -> employee -> sched_end)
                                            {

                                                $endTime = Carbon::parse($data -> employee -> sched_end)->subHour(1);
                                                $startTime = Carbon::parse($data -> time_in);
                                                $interval = $startTime->diffInSeconds($endTime);
                                                $totalDuration = gmdate('H:i:s', $interval);

                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                        ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                        ->update(['time_out' => $data -> employee -> sched_end,
                                                                 'work_hours' => $totalDuration]);

                                                return response()->json([
                                                    'status' => 200,
                                                    'msg' => 'Attendance updated Successfully',
                                                ]);

                                            }
                                            
                                            else//UNDERTIME without OT================================================================================================================
                                            {
                                                $timee = Carbon::createFromTime(14, 00, 00, 'GMT+8');
                                                $timeOUT = Carbon::parse($timee)->format('H:i:s');//declared and for testing or debugging only

                                                $timeee = Carbon::now('GMT+8')->format('H:i:s');
                                                $timeOUT1 = Carbon::parse($timeee)->format('H:i:s');//realtime used in timeout


                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update(['time_out' => $timeOUT1]);

                                                $attend = Attendance::where('emp_no', '=', $request -> scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->with('employee')->get();
                                               //return dd(Attendance::with('employee')->get());
                                                foreach($attend as $attends)
                                                {
                                                    //////////11111111
                                                    if($attends->time_out    <     $attends->employee->sched_end       &&       $attends->time_out     >     $attends->time_in)////////////////IF UNDERTIME IS TRUE (NAG OUT NG MAAGA)
                                                    {
                                                        //$PMtime = Carbon::createFromTime(12, 00, 00, 'GMT+8')->format('H:i:s');//declared breaktime
                                                        $PMtime = Carbon::parse($attends ->employee-> breaktime_start); //breaktime from employee database
                                                        $breakTime = Carbon::parse($PMtime)->format('H:i:s');

                                                        $PMtime2 = Carbon::parse($attends ->employee-> breaktime_end); //breaktime from employee database
                                                        $breakTime_end = Carbon::parse($PMtime2)->format('H:i:s');

                                                        if($attends->time_out    <=    $breakTime)//if timeout is less than breaktime (nag out dire pa breaktime) working
                                                        {
                                                            $sched_Out = Carbon::parse($attends ->employee-> sched_end)->subHour(1);
                                                            $UTDiff = $sched_Out->diffInSeconds($attends->time_out);
                                                            $UTime = gmdate('H:i:s', $UTDiff);

                                                            $startTime = Carbon::parse($attends -> time_in);
                                                            $endTime = Carbon::parse($attends -> time_out);
                                                            $interval = $startTime->diffInSeconds($endTime);
                                                            $totalDuration = gmdate('H:i:s', $interval);

                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                      ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                      ->update(['undertime_hours'=>$UTime,
                                                                                'work_hours' => $totalDuration]);

                                                                        return response()->json([
                                                                            'status' => 200,
                                                                            'msg' => 'Attendance updated Successfully',
                                                                        ]);

                                                        }
                                                       //if timeout is greater than breaktime (nag out pero nasa breaktime) working 
                                                        else if($attends->time_in    <=    $breakTime  &&   $attends->time_out    >     $breakTime    &&    $attends->time_out    <    $breakTime_end)
                                                        {
                                                            //nag out na less than sa breaktime start pero less than breaktime end
                                                            $sched_Out = Carbon::parse($attends ->employee-> sched_end);
                                                            $UTDiff = $sched_Out->diffInSeconds($breakTime_end);
                                                            $UTime = gmdate('H:i:s', $UTDiff);

                                                            $startTime = Carbon::parse($attends -> time_in);
                                                            //$endTime = Carbon::parse($attends -> time_out);
                                                            $interval = $startTime->diffInSeconds($breakTime);
                                                            $totalDuration = gmdate('H:i:s', $interval);

                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                      ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                      ->update(['undertime_hours'=>$UTime,
                                                                                'work_hours' => $totalDuration]);

                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);
                                                        }
                                                        else if($attends->time_in    >=     $breakTime    &&    $attends->time_in    <    $breakTime_end   &&   $attends->time_out    >=    $breakTime_end)
                                                        {
                                                            //nag out na sobra sa breaktime start pero less than breaktime end
                                                            $sched_Out = Carbon::parse($attends ->employee-> sched_end);
                                                            $UTDiff = $sched_Out->diffInSeconds($attends ->time_out);
                                                            $UTime = gmdate('H:i:s', $UTDiff);

                                                            $startTime = Carbon::parse($attends -> time_in);
                                                            $endTime = Carbon::parse($attends -> time_out);
                                                            $interval = $startTime->diffInSeconds($endTime);
                                                            $totalDuration = gmdate('00:00:00', $interval);

                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                      ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                      ->update(['undertime_hours'=>$UTime,
                                                                                'work_hours' => $totalDuration]);

                                                                    return response()->json([
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance updated Successfully',
                                                                    ]);
                                                        }
                                                        else if ($attends->time_in   >   $breakTime_end    &&   $attends->time_out    >=    $breakTime_end)///nag in ng lagapas na sa breaktime stu
                                                        {
                                                            $sched_Out = Carbon::parse($attends ->employee-> sched_end);
                                                            $UTDiff = $sched_Out->diffInSeconds($attends ->time_out);
                                                            $UTime = gmdate('H:i:s', $UTDiff);

                                                            $startTime = Carbon::parse($attends -> time_in);
                                                            $endTime = Carbon::parse($attends -> time_out);
                                                            $interval = $startTime->diffInSeconds($endTime);
                                                            $totalDuration = gmdate('H:i:s', $interval);

                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                      ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                      ->update(['undertime_hours'=>$UTime,
                                                                        'work_hours' => $totalDuration]);

                                                            return response()->json([
                                                                'status' => 200,
                                                                'msg' => 'Attendance updated Successfully',
                                                            ]);
                                                        }
                                                        else
                                                        {
                                                            $sched_Out = Carbon::parse($attends ->employee-> sched_end);
                                                            $UTDiff = $sched_Out->diffInSeconds($attends ->time_out);
                                                            $UTime = gmdate('H:i:s', $UTDiff);

                                                            $startTime = Carbon::parse($attends -> time_in);
                                                            $endTime = Carbon::parse($attends -> time_out)->subHour(1);
                                                            $interval = $startTime->diffInSeconds($endTime);
                                                            $totalDuration = gmdate('H:i:s', $interval);

                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                      ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                      ->update(['undertime_hours'=>$UTime,
                                                                        'work_hours' => $totalDuration]);

                                                            return response()->json([
                                                                'status' => 200,
                                                                'msg' => 'Attendance updated Successfully',
                                                            ]);

                                                        }
                                                    }



                                                    ////////////22222222222
                                                    else if($attends->time_out   <   $attends->employee->sched_end    &&    $attends->time_out    <=    $attends->time_in)
                                                    {

                                                        $PMtime = Carbon::parse($attends ->employee-> breaktime_start); //breaktime from employee database
                                                        $breakTime = Carbon::parse($PMtime)->format('H:i:s');

                                                        $PMtime2 = Carbon::parse($attends ->employee-> breaktime_end); //breaktime from employee database
                                                        $breakTime_end = Carbon::parse($PMtime2)->format('H:i:s');


                                                       if($attends->time_in  >=  $breakTime_end)
                                                       {
                                                        $sched_Out = Carbon::parse($attends ->employee-> sched_end);
                                                        $UTDiff = $sched_Out->diffInSeconds($attends->time_in);
                                                        $UTime = gmdate('H:i:s', $UTDiff);

                                                        $wHour = Carbon::createFromTime(0, 0, 0, 'GMT+8');
                                                        $wHour1 = Carbon::parse($wHour)->format('H:i:s');
                                                        // $timeOut = Carbon::parse($attends->time_out);
                                                        // $timeIN = Carbon::parse($attends->time_in);
                                                        // $sched_Out = Carbon::parse($attends ->employee-> sched_end);
                                                        // $UTDiff = $sched_Out->diffInSeconds($timeOut);
                                                        // $UTime = gmdate('H:i:s', $UTDiff);

                                                        Attendance::where('emp_no', '=', $request -> scanned)
                                                                  ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                  ->update(['undertime_hours'=>$UTime,'time_out'=>$attends->time_in,
                                                                           'work_hours' => $wHour1]);
                                                       }
                                                       else
                                                       {
                                                        $sched_Out = Carbon::parse($attends ->employee-> sched_end)->subHour(1);
                                                        $UTDiff = $sched_Out->diffInSeconds($attends->time_in);
                                                        $UTime = gmdate('H:i:s', $UTDiff);

                                                        $wHour = Carbon::createFromTime(0, 0, 0, 'GMT+8');
                                                        $wHour1 = Carbon::parse($wHour)->format('H:i:s');
                                                        // $timeOut = Carbon::parse($attends->time_out);
                                                        // $timeIN = Carbon::parse($attends->time_in);
                                                        // $sched_Out = Carbon::parse($attends ->employee-> sched_end);
                                                        // $UTDiff = $sched_Out->diffInSeconds($timeOut);
                                                        // $UTime = gmdate('H:i:s', $UTDiff);

                                                        Attendance::where('emp_no', '=', $request -> scanned)
                                                                  ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                  ->update(['undertime_hours'=>$UTime,'time_out'=>$attends->time_in,
                                                                           'work_hours' => $wHour1]);
                                                       }
                                                        

                                                                    return response()->json([
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance updated Successfully',
                                                                    ]);
                                                    }




                                                    ///////////////////3333333333
                                                    else
                                                    {
                                                        //$PMtime = Carbon::createFromTime(12, 00, 00, 'GMT+8')->format('H:i:s');//declared breaktime
                                                        $PMtime = Carbon::parse($attends ->employee-> breaktime_start); //breaktime from employee database
                                                        $breakTime1 = Carbon::parse($PMtime)->format('H:i:s');

                                                        if($attends->time_in < $breakTime1)
                                                        { 

                                                            $startTime = Carbon::parse($attends -> time_in);
                                                            $endTime = Carbon::parse($attends ->employee->sched_end)->subHour(1);
                                                            $interval = $startTime->diffInSeconds($endTime);
                                                            $totalDuration1 = gmdate('H:i:s', $interval);

                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                        ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update(['work_hours' => $totalDuration1]);
                                                        }
                                                        else
                                                        {
                                                            

                                                            $startTime = Carbon::parse($attends -> time_in);
                                                            $endTime = Carbon::parse($attends ->employee->sched_end);
                                                            $interval = $startTime->diffInSeconds($endTime);
                                                            $totalDuration1 = gmdate('H:i:s', $interval);

                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                        ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update(['work_hours' => $totalDuration1]);
                                                        }
                                                              
                                                            return response()->json([
                                                                'status' => 200,
                                                                'msg' => 'Attendance updated Successfully',
                                                            ]);



                                                    }
                                                }

                                                return response()->json([
                                                    'status' => 200,
                                                    'msg' => 'Attendance updated Successfully',
                                                ]);
                                            }

                                        }

                                    }
                                }

                            }
                        }

                        foreach($schedules as $sched){
                                                                    
                            foreach($holidays as $holiday){

                                $currentDate = Carbon::today('GMT+8')->format('m-d');
                                $holidayDate = Carbon::parse($holiday->holiday_date)->format('m-d');

                                if ($currentDate === $holidayDate && $holiday->holiday_type === 'Regular') {

                                    //Sabado or Lunes Then Regular holiday pa
                                    if(Carbon::today('GMT+8')->isSunday() || Carbon::today('GMT+8')->isMonday()){

                                        //kun an time in is less than sa sched ng employee
                                        if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){
                                            
                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = $sched -> sched_start;
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> RDRH = true; // Rest day  and Regular holiday
                                            $employee_attendance -> save();
    
                                            return response()->json([
    
                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                        //kun an time in is greater than sa sched ng employee
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){

                                            $timein = Carbon::now('GMT+8')->format('H:i:s');
                                            $sched_in = Carbon::parse($sched -> sched_start);
                                            $interval = $sched_in->diffInSeconds($timein);
                                            $total_late = gmdate('H:i:s', $interval);

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> late_hours = $total_late;
                                            $employee_attendance -> RDRH = true;  // Rest day  and Regular holiday
                                            $employee_attendance -> save();

                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                            //kun an time in is equal sa sched ng employee
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> RDRH = true; // restday
                                            $employee_attendance -> save();

                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                    }
                                    //Hindi sabado or lunes pero Regular Holiday lang
                                    else
                                    {
                                            //kun an sched is less than sa sched ng employee
                                        if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){
                                            
                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = $sched -> sched_start;
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> RH = true; // Rest day  and Regular holiday
                                            $employee_attendance -> save();
    
                                            return response()->json([
    
                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                        //kun an sched is greater than sa sched ng employee
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){

                                            $timein = Carbon::now('GMT+8')->format('H:i:s');
                                            $sched_in = Carbon::parse($sched -> sched_start);
                                            $interval = $sched_in->diffInSeconds($timein);
                                            $total_late = gmdate('H:i:s', $interval);

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> late_hours = $total_late;
                                            $employee_attendance -> RH = true;  // Rest day  and Regular holiday
                                            $employee_attendance -> save();

                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                            //kun an sched is equal sa sched ng employee
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> RH = true; // restday
                                            $employee_attendance -> save();

                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                    }
                                }
                                else if ($currentDate === $holidayDate && $holiday->holiday_type === 'Special') {
                                    //Sabado or Lunes Then Special holiday pa
                                    if(Carbon::today('GMT+8')->isSunday() || Carbon::today('GMT+8')->isMonday()){

                                        //kun an time in is less than sa sched ng employee
                                        if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){
                                            
                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = $sched -> sched_start;
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> RDSH = true; // Rest day  and Special holiday
                                            $employee_attendance -> save();
    
                                            return response()->json([
    
                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                        //kun an time in is greater than sa sched ng employee
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){

                                            $timein = Carbon::now('GMT+8')->format('H:i:s');
                                            $sched_in = Carbon::parse($sched -> sched_start);
                                            $interval = $sched_in->diffInSeconds($timein);
                                            $total_late = gmdate('H:i:s', $interval);

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> late_hours = $total_late;
                                            $employee_attendance -> RDSH = true;  // Rest day  and Special holiday
                                            $employee_attendance -> save();

                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                            //kun an time in is equal sa sched ng employee
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> RDSH = true; // restday and Special holiday
                                            $employee_attendance -> save();

                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                    }
                                    //Hindi sabado or lunes pero Special Holiday lang
                                    else
                                    {
                                            //kun an sched is less than sa sched ng employee
                                        if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){
                                            
                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = $sched -> sched_start;
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> SH = true; // Rest day  and Special holiday
                                            $employee_attendance -> save();
    
                                            return response()->json([
    
                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                        //kun an sched is greater than sa sched ng employee
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){

                                            $timein = Carbon::now('GMT+8')->format('H:i:s');
                                            $sched_in = Carbon::parse($sched -> sched_start);
                                            $interval = $sched_in->diffInSeconds($timein);
                                            $total_late = gmdate('H:i:s', $interval);

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> late_hours = $total_late;
                                            $employee_attendance -> SH = true;  // Rest day  and Special holiday
                                            $employee_attendance -> save();

                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                            //kun an sched is equal sa sched ng employee
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> SH = true; // restday and Spcial holiday
                                            $employee_attendance -> save();

                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                    }
                                }
                            }

                            //INSERT ATTENDANCE FOR DAY SHIFT
                            //REST DAY
                            if(Carbon::today('GMT+8')->isSunday() || Carbon::today('GMT+8')->isMonday()){

                                if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){

                                        $employee_attendance = new Attendance();
                                        $employee_attendance -> emp_no = $request -> scanned;
                                        $employee_attendance -> time_in = $sched -> sched_start;
                                        $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                        $employee_attendance -> RD = true;
                                        $employee_attendance -> save();

                                        return response()->json([

                                            'status' => 200,
                                            'msg' => 'Attendance Recorded Successfully',
                                        ]);

                                }
                                    
                                else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){

                                        $timein = Carbon::now('GMT+8')->format('H:i:s');
                                        $sched_in = Carbon::parse($sched -> sched_start);
                                        $interval = $sched_in->diffInSeconds($timein);
                                        $total_late = gmdate('H:i:s', $interval);

                                        $employee_attendance = new Attendance();
                                        $employee_attendance -> emp_no = $request -> scanned;
                                        $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                        $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                        $employee_attendance -> late_hours = $total_late;
                                         $employee_attendance -> RD = true;
                                        $employee_attendance -> save();

                                        return response()->json([

                                            'status' => 200,
                                            'msg' => 'Attendance Recorded Successfully',
                                        ]);
                                    
                                }
                               
                                else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){
                                    
                                        $employee_attendance = new Attendance();
                                        $employee_attendance -> emp_no = $request -> scanned;
                                        $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                        $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                        $employee_attendance -> RD = true;
                                        $employee_attendance -> save();

                                        return response()->json([

                                            'status' => 200,
                                            'msg' => 'Attendance Recorded Successfully',
                                        ]);
                                    
                                }

                            }
                            else
                            {

                                      //Not holiday and the time in is less than sa employee shed
                                if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){

                                        $employee_attendance = new Attendance();
                                        $employee_attendance -> emp_no = $request -> scanned;
                                        $employee_attendance -> time_in = $sched -> sched_start;
                                        $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                        $employee_attendance -> save();

                                        return response()->json([

                                            'status' => 200,
                                            'msg' => 'Attendance Recorded Successfully',
                                        ]);

                                }
                                     //Not holiday and the time in is greater than sa employee shed
                                else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){

                                        $timein = Carbon::now('GMT+8')->format('H:i:s');
                                        $sched_in = Carbon::parse($sched -> sched_start);
                                        $interval = $sched_in->diffInSeconds($timein);
                                        $total_late = gmdate('H:i:s', $interval);

                                        $employee_attendance = new Attendance();
                                        $employee_attendance -> emp_no = $request -> scanned;
                                        $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                        $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                        $employee_attendance -> late_hours = $total_late;
                                        $employee_attendance -> save();

                                        return response()->json([

                                            'status' => 200,
                                            'msg' => 'Attendance Recorded Successfully',
                                        ]);
                                    
                                }
                                    //Not holiday and the time in is equal sa employee shed
                                else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){
                                    
                                        $employee_attendance = new Attendance();
                                        $employee_attendance -> emp_no = $request -> scanned;
                                        $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                        $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');

                                        $employee_attendance -> save();

                                        return response()->json([

                                            'status' => 200,
                                            'msg' => 'Attendance Recorded Successfully',
                                        ]);
                                    
                                }

                            }
                            
                        }
                    }
                    //========================= NIGHT SHIFT ==========================
                    else if($shift -> employee_no == $request -> scanned && $shift -> employee_shift == "Night")
                    {

                        foreach($attendance_data as $data){

                                if($data -> emp_no == $request -> scanned && $data -> date == Carbon::now('GMT+8')->subDay(1)->format('Y-m-d') && $data -> time_out === null){
                                 
                                    // CHECK IF OVERTIME IS APPROVED
                                    if ($data -> overtime -> isApproved_HR == '1')
                                    {

                                        // CHECK IF THE TIME IN, TIME OUT AND HOURS OT IS NOT NULL
                                        if($data -> time_in !== null && $data -> time_out !== null && $data -> overtime -> hours_OT !== null ){
                                            
                                            return response()->json([
                                                'status'=> 0,
                                                'error' => 'Time in and Time out is already completed'
                                            ]);

                                        }
                                        else
                                        {
                                           

                                            if(Carbon::now('GMT+8')->format('H:i:s') >= $data->employee->sched_end){

                                                    
                                                    // Overtime Computation
                                                    $time_out =  Carbon::now('GMT+8')->format('H:i:s');
                                                    $schedule_end = Carbon::parse($data -> employee -> sched_end);
                                                    $interval = $schedule_end->diffInSeconds($time_out);
                                                    $total_overtime = gmdate('H:i:s', $interval);


                                                    //Work hour
                                                   // $timeout = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                    $timeout = Carbon::parse($data -> employee -> sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                    $timein = Carbon::parse($data -> night_shift_date);
                                                    $interval = $timein->diffInSeconds($timeout);
                                                    $totalDuration = gmdate('H:00:00', $interval);


                                                    if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s'))
                                                    {

                                                        // NIGHT DIFFERENTIAL HOURS
                                                        $start = Carbon::parse($data->night_shift_date);
                                                        //$out =  Carbon::now('GMT+8')->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                        $out = Carbon::createFromFormat('H:i:s', '06:00:00')->addDays(1)->subHour(1); 
                                                        $diff = $start->diffInSeconds($out);
                                                        $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                                    }
                                                    else
                                                    {
                                                        //NIGHT DIFFERENTIAL HOURS
                                                        $start = Carbon::createFromFormat('H:i:s', '22:00:00'); //10pm
                                                        $out = Carbon::createFromFormat('H:i:s', '06:00:00')->addDays(1)->subHour(1); //6am
                                                        $diff = $out ->diffInSeconds($start);
                                                        $night_diff_total_hours = gmdate('H:i:s', $diff);
                                                    }



                                                    switch($data){

                                                        //Rest Day Night diff
                                                        case $data -> RDND == true:

                                                            Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => $total_overtime,
                                                                              'RDOT' => true]);
                                                        break;

                                                        //rest day special holiday Night diff
                                                        case $data -> SHND == true:

                                                            Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => $total_overtime,
                                                                              'SHOT' => true]);
                                                        break;

                                                            //rest day regular holiday Night diff
                                                            case $data -> RHND == true:

                                                            Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => $total_overtime,
                                                                              'RHOT' => true]);
                                                        break;

                                                        //rest day special holiday Night diff
                                                        case $data -> RDSHND == true:

                                                            Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => $total_overtime,
                                                                              'RDSHOT' => true]);
                                                        break;
                                                        
                                                        //rest day regular holiday Night diff
                                                        case $data -> RDRHND == true:

                                                            Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => $total_overtime,
                                                                             'RDRHOT' => true]);
                                                        break;
                                                        
                                                        default:
                                                             Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => $total_overtime]);

                                                        break;
                                                    }

                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                              ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                              ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                        'work_hours' => $totalDuration,
                                                                        'night_diff_hours' => $night_diff_total_hours]);

                                                    return response()->json([

                                                        'status' => 200,
                                                        'msg' => 'Overtime Recorded Successfully',

                                                    ]);
                                            }
                                            else
                                            {
                                                //HOURS NOW IS GREATER THAN EQUAL TO EMPLOYEE BREAKTIME  START AND LESS THAN OR EQUAL TO   EMPLOYEE BREAKTIME  END
                                                if(Carbon::now('GMT+8')->format('H:i:s') >= $data -> employee -> breaktime_start && Carbon::now('GMT+8')->format('H:i:s') <= $data -> employee -> breaktime_end ) 
                                                {

                    
                                                 
                                                    if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){

                                            
                                                        // NIGHT DIFFERENTIAL HOURS
                                                    
                                                        $start = Carbon::parse($data->night_shift_date);
                                                        //$out =  Carbon::now('GMT+8')->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                        $out = Carbon::parse($data->employee->breaktime_start)->addDay(1)->format('Y-m-d H:i:s');
                                                        $diff = $start->diffInSeconds($out);
                                                        $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                                    }
                                                    else
                                                    {
                                                        //NIGHT DIFFERENTIAL HOURS
                                                        $start = Carbon::createFromFormat('H:i:s', '22:00:00'); //10pm
                                                        //$out =  Carbon::now('GMT+8')->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                        $out = Carbon::parse($data->employee->breaktime_start)->addDay(1)->format('Y-m-d H:i:s');
                                                        $diff = $start ->diffInSeconds($out);
                                                        $night_diff_total_hours = gmdate('H:i:s', $diff);
                                                    }
                                                  
                                                    //undertime
                                                    $emp_sched_out = Carbon::parse($data ->employee-> sched_end)->addDay(1);
                                                    $out_today = Carbon::parse($data ->employee-> breaktime_end)->addDay(1);
                                                    $sum = $emp_sched_out -> diffInSeconds($out_today);
                                                    $undertime = gmdate('H:00:00', $sum);
                                                   
                                                    //WORK HOURS
                                                    //$timeout = Carbon::now('GMT+8')->format('Y-m-d H:i:s');

                                                    $timeout = Carbon::parse($data->employee->breaktime_start)->addDay(1)->format('Y-m-d H:i:s');
                                                    $timein = Carbon::parse($data -> night_shift_date);
                                                    $interval = $timein->diffInSeconds($timeout);
                                                    $totalDuration = gmdate('H:i:s', $interval);


                                                    switch($data){

                                                            //Rest day Night diff
                                                            case $data -> RDND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDOT' => true]);
                                                            break;

                                                            //rest day special holiday Night diff
                                                            case $data -> SHND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'SHOT' => true]);
                                                            break;

                                                                //rest day regular holiday
                                                                case $data -> RHND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RHOT' => true]);
                                                            break;

                                                            //rest day special holiday Night diff
                                                            case $data -> RDSHND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDSHOT' => true]);
                                                            break;
                                                            
                                                            //rest day regular holiday Night diff
                                                            case $data -> RDRHND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                 'RDRHOT' => true]);
                                                            break;
                                                            
                                                            default:
                                                                 Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00']);

                                                            break;
                                                    }

                                                  
                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                              ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                              ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                        'work_hours' => $totalDuration,
                                                                        'undertime_hours' => $undertime,
                                                                        'night_diff_hours' => $night_diff_total_hours]);

                                                    return response()->json([

                                                        'status' => 200,
                                                        'msg' => 'Overtime Recorded Successfully',

                                                    ]);
                                                }
                                                //HOURS NOW IS GREATER THAN EMPLOYEE BREAKTIME  END
                                                else if(Carbon::now('GMT+8')->format('H:i:s') > $data -> employee -> breaktime_end ) {

                                                    if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){

                                           
                                                        // NIGHT DIFFERENTIAL HOURS
                                                        $start = Carbon::parse($data->night_shift_date);
                                                        $out =  Carbon::now('GMT+8')->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                        $diff = $start->diffInSeconds($out);
                                                        $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                                    }
                                                    else
                                                    {
                                                        //NIGHT DIFFERENTIAL HOURS
                                                        $start = Carbon::createFromFormat('H:i:s', '22:00:00'); //10pm
                                                        $out =  Carbon::now('GMT+8')->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                        $diff = $start ->diffInSeconds($out);
                                                        $night_diff_total_hours = gmdate('H:i:s', $diff);
                                                    }


                                                    //WORK HOURS
                                                    $timeout = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                    $timein = Carbon::parse($data -> night_shift_date);
                                                    $interval = $timein->diffInSeconds($timeout);
                                                    $totalDuration = gmdate('H:i:s', $interval);

                                                    //UNDERTIME
                                                    $emp_sched_out = Carbon::parse($data ->employee-> sched_end)->addDay(1);
                                                    $out_today =Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $sum = $emp_sched_out -> diffInSeconds($out_today);
                                                    $undertime = gmdate('H:i:s', $sum);

                                                    switch($data){

                                                            //Rest day Night diff
                                                            case $data -> RDND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDOT' => true]);
                                                            break;

                                                            //rest day special holiday Night diff
                                                            case $data -> SHND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'SHOT' => true]);
                                                            break;

                                                                //rest day regular holiday
                                                                case $data -> RHND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RHOT' => true]);
                                                            break;

                                                            //rest day special holiday Night diff
                                                            case $data -> RDSHND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDSHOT' => true]);
                                                            break;
                                                            
                                                            //rest day regular holiday Night diff
                                                            case $data -> RDRHND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                 'RDRHOT' => true]);
                                                            break;
                                                            
                                                            default:
                                                                 Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00']);

                                                            break;
                                                    }


                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                              ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                              ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                        'work_hours' => $totalDuration,
                                                                        'undertime_hours' => $undertime,
                                                                        'night_diff_hours' => $night_diff_total_hours]);

                                                    return response()->json([

                                                        'status' => 200,
                                                        'msg' => 'Overtime Recorded Successfully',

                                                    ]);
                                                }

                                                //TIME OUT BEFORE BREAK TIME START
                                                else
                                                {   
                                                   
                                                    if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){
                                                        
                                                        // NIGHT DIFFERENTIAL HOURS
                                                        $start = Carbon::parse($data->night_shift_date);
                                                        $out =  Carbon::now('GMT+8')->addDay(1)->format('Y-m-d H:i:s');
                                                        $diff = $start->diffInSeconds($out);
                                                        $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                                    }
                                                    else
                                                    {
                                                        //NIGHT DIFFERENTIAL HOURS
                                                        $start = Carbon::createFromFormat('H:i:s', '22:00:00'); //10pm
                                                        $out =  Carbon::now('GMT+8')->addDay(1)->format('Y-m-d H:i:s');
                                                        $diff = $start ->diffInSeconds($out);
                                                        $night_diff_total_hours = gmdate('H:i:s', $diff);
                                                    }
                                                   
                                               
                                                    //WORK HOURS
                                                    $timeout = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $timein = Carbon::parse($data -> night_shift_date);
                                                    $interval = $timein->diffInSeconds($timeout);
                                                    $totalDuration = gmdate('H:i:s', $interval);

                                                    //Minus 1 HR Under time
                                                    $emp_sched_out = Carbon::parse($data ->employee-> sched_end)->addDay(1)->subHour(1);
                                                    $out_today =Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $sum = $emp_sched_out -> diffInSeconds($out_today);
                                                    $undertime = gmdate('H:i:s', $sum);

                                                    switch($data){

                                                            //Rest day Night diff
                                                            case $data -> RDND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDOT' => true]);
                                                            break;

                                                            //rest day special holiday Night diff
                                                            case $data -> SHND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'SHOT' => true]);
                                                            break;

                                                                //rest day regular holiday
                                                                case $data -> RHND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RHOT' => true]);
                                                            break;

                                                            //rest day special holiday Night diff
                                                            case $data -> RDSHND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDSHOT' => true]);
                                                            break;
                                                            
                                                            //rest day regular holiday Night diff
                                                            case $data -> RDRHND == true:

                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                 'RDRHOT' => true]);
                                                            break;
                                                            
                                                            default:
                                                                 Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00']);

                                                            break;
                                                    }

                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                              ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                              ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                        'work_hours' => $totalDuration,
                                                                        'undertime_hours' => $undertime,
                                                                        'night_diff_hours' => $night_diff_total_hours]);

                                                    return response()->json([

                                                        'status' => 200,
                                                        'msg' => 'Overtime Recorded Successfully',

                                                    ]);
                                                }
                                               
                                                
                                            }
                                                  
                                        }

                                    }
                                    else
                                    {   
                                        if($data -> time_in != null && $data -> time_out != null ){

                                            return response()->json([
                                                'status'=>0,
                                                'error' => 'Time in and Time out is already completed'
                                            ]);

                                        }
                                        else
                                        {

                                            if($data -> overtime -> isApproved_HR == '0')
                                            {

                                                //IF THE EMPLOYEE TIME OUT GREATER THAN THE SCHEDULE END AND THE OVERTIME IS NOT APPROVED BY HR
                                                if(Carbon::now('GMT+8')->format('H:i:s') >= $data->employee->sched_end)
                                                {

                                                    //WORK HOURS
                                                    $timeout = Carbon::parse($data->employee->sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                    $timein = Carbon::parse($data -> night_shift_date);
                                                    $interval = $timein->diffInSeconds($timeout);
                                                    $totalDuration = gmdate('H:i:s', $interval);

                                                    //NIGHT DIFFERENTIAL
                                                    if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s'))
                                                    {

                                                        // NIGHT DIFFERENTIAL HOURS
                                                        $start = Carbon::parse($data->night_shift_date);
                                                        $out = Carbon::createFromFormat('H:i:s', '06:00:00')->addDays(1)->subHour(1); 
                                                        $diff = $start->diffInSeconds($out);
                                                        $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                                    }
                                                    else
                                                    {
                                                        //NIGHT DIFFERENTIAL HOURS
                                                        $start = Carbon::createFromFormat('H:i:s', '22:00:00'); //10pm
                                                        $out = Carbon::createFromFormat('H:i:s', '06:00:00')->addDays(1)->subHour(1); //6am
                                                        $diff = $out ->diffInSeconds($start);
                                                        $night_diff_total_hours = gmdate('H:i:s', $diff);
                                                    }

                                                    switch($data){

                                                        //Rest day Night diff
                                                        case $data -> RDND == true:

                                                            Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => '00:00:00',
                                                                              'RDOT' => true]);
                                                        break;

                                                        //rest day special holiday Night diff
                                                        case $data -> SHND == true:

                                                            Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => '00:00:00',
                                                                              'SHOT' => true]);
                                                        break;

                                                            //rest day regular holiday
                                                            case $data -> RHND == true:

                                                            Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => '00:00:00',
                                                                              'RHOT' => true]);
                                                        break;

                                                        //rest day special holiday Night diff
                                                        case $data -> RDSHND == true:

                                                            Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => '00:00:00',
                                                                              'RDSHOT' => true]);
                                                        break;
                                                        
                                                        //rest day regular holiday Night diff
                                                        case $data -> RDRHND == true:

                                                            Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => '00:00:00',
                                                                             'RDRHOT' => true]);
                                                        break;
                                                        
                                                        default:
                                                             Overtime::where('emp_number', '=', $request -> scanned)
                                                                    ->where('attendance_id', '=', $data -> id)
                                                                    ->update(['hours_OT' => '00:00:00']);

                                                        break;
                                                    }

                                                    foreach($holidays as $holiday){
                                                                 
                                                        $todays_Date = Carbon::today('GMT+8')->format('m-d');
                                                        $office_holiday = Carbon::parse($holiday->holiday_date)->format('m-d');


                                                        if($todays_Date === $office_holiday){

                                                            if($data->RDND == true && $holiday->holiday_type == 'Regular'){
                                            
                                                                //REST DAY NIGHT SHIFT
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                        
                                                                //REGULAR HOLIDAY
                                                                $out = Carbon::parse($data -> employee -> sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                               
                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                'work_hours' => $totalDuration,
                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                'RHND' => true,
                                                                                'RDND_hours' => $restday_total_holiday_hours,
                                                                                'RHND_hours' => $regular_holiday_hours
                                                                            ]);

                                                                return response()->json([

                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',

                                                                ]);
                                        
                                        
                                                            }
                                                            elseif($data->RHND == true && $holiday->holiday_type == 'Regular'){
                                                               
                                                                //FOR THE RHND 
                                                                //ADD the previous holiday which is Regular holiday to toddays holiday which Regular holiday again
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $time_out = Carbon::parse($data -> employee -> sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($time_out);
                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                               
                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                        ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                            'work_hours' => $totalDuration,
                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                            'RHND_hours' => $regular_holiday_hours,
                                                                        ]);

                                                                return response()->json([
                                
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                        
                                                                ]);
        
                                        
                                                            }
                                                            elseif($data->RDRHND == true && $holiday->holiday_type == 'Regular'){
                                                           
                                                                //REST DAY REGULAR HOLIDAY NIGHT SHIFT
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);
                                        
                                                                //REGULAR HOLIDAY
                                                                $out = Carbon::parse($data -> employee -> sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                              
                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                    'work_hours' => $totalDuration,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'RHND' => true,
                                                                                    'RDRHND_hours' => $restday_regular_total_holiday_hours,
                                                                                    'RHND_hours' => $regular_holiday_hours,
                                                                            ]);

                                                                return response()->json([
                                        
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                        
                                                                ]);
                                        
                                                            }  
                                                            elseif($data->RHND == true && $holiday->holiday_type == 'Special'){
                                                               
                                                                //REGULAR HOLIDAY YESTERDAY
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                //Special Holiday nextday
                                                                $out = Carbon::parse($data -> employee -> sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                'work_hours' => $totalDuration,
                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                'SHND' => true,
                                                                                'SHND_hours' => $special_holiday_hours,
                                                                                'RHND_hours' => $regular_holiday_hours,
                                                                            ]);

                                                                return response()->json([
                                        
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                        
                                                                ]);


                                                            }
                                                            elseif($data->RDRHND == true && $holiday->holiday_type == 'Special'){
                                                             
                                                                //REST DAY REGULAR HOLIDAY NIGHT SHIFT YESTERDAY
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);

                                                                 //Special Holiday nextday
                                                                $out = Carbon::parse($data -> employee -> sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                        ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                            'work_hours' => $totalDuration,
                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                            'SHND' => true,
                                                                            'SHND_hours' => $special_holiday_hours,
                                                                            'RDRHND_hours' => $restday_regular_total_holiday_hours
                                                                        ]);
                                                                return response()->json([
                                        
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                        
                                                                ]);


                                                            }
                                                            elseif($data->RDND == true && $holiday->holiday_type == 'Special'){
    
                                                                //REST DAY NIGHT SHIFT
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                    
                                                                //SPECIAL HOLIDAY
                                                                $out = Carbon::parse($data->employee->sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);
                                                            
                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                'work_hours' => $totalDuration,
                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                'SHND' => true,
                                                                                'RDND_hours' => $restday_total_holiday_hours,
                                                                                'SHND_hours' => $special_holiday_hours,
                                                                            
                                                                            ]);
                                                
                                                                return response()->json([
                                    
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                    
                                                                ]);
                                    
                                    
                                                            }
                                                            elseif($data->SHND == true && $holiday->holiday_type == 'Special'){
                                                                //FOR THE SHND 
                                                                //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $time_out = Carbon::parse($data -> employee -> sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($time_out);
                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);
                                                            
                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                'work_hours' => $totalDuration,
                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                'SHND_hours' => $special_holiday_hours,
                                                                            ]);
            
                                                                return response()->json([
                                    
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                    
                                                                ]);
                                                            }
                                                            elseif($data->RDSHND == true && $holiday->holiday_type == 'Special'){
                                            
                                                                //REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $restday_special_total_holiday_hours = gmdate('H:i:s', $subtract);
                                    
                                                                //SPECIAL HOLIDAY
                                                                $out = Carbon::parse($data->employee->sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);
                                    
                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                'work_hours' => $totalDuration,
                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                'SHND' => true,
                                                                                'RDSHND_hours' => $restday_special_total_holiday_hours,
                                                                                'SHND_hours' => $special_holiday_hours
                                                                            ]);
                                                                
                                                                return response()->json([
                                    
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                    
                                                                ]);
                                                            }
                                                            elseif($data->SHND == true && $holiday->holiday_type == 'Regular'){

                                                                //FOR THE SHND 
                                                                //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again
                                                             
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                //Regular HOLIDAY
                                                                $out = Carbon::parse($data->employee->sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                'work_hours' => $totalDuration,
                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                'RHND' => true,
                                                                                'SHND_hours' => $special_holiday_hours,
                                                                                'RHND_hours' => $regular_holiday_hours,
                                                                            
                                                                            ]);
                                                                return response()->json([
                                
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                    
                                                                ]);

                                                            }
                                                            else{
                                                                
                                                               
                                                                 //FOR THE RDSHND 
                                                                //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again   
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $restday_special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                //Regular HOLIDAY
                                                                $out = Carbon::parse($data->employee->sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                'work_hours' => $totalDuration,
                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                'RHND' => true,
                                                                                'RDSHND_hours' => $restday_special_holiday_hours,
                                                                                'RHND_hours' => $regular_holiday_hours,
                                                                            
                                                                            ]);
                                                                return response()->json([
                                
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                    
                                                                ]);
                                                            }
                                    
                                                        }   
                                                    
                                                        else{   
                                                          
                                                            #need to use switch case
                                                            switch($data){
                                                               
                                                                //Rest day Night shift
                                                                case $data -> RDND == true:
                                                                   
                                                                    //REST DAY NIGHT SHIFT
                                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                                    $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                    $subtract = $time_in->diffInSeconds($holiday_date);
                                                                    $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                            
                                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                                                ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                    'work_hours' => $totalDuration,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'RDND_hours' => $restday_total_holiday_hours,
                                                                                ]);
                                                                    return response()->json([
                                            
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance Recorded Successfully',
                                            
                                                                    ]);

                                                                break;
        
                                                                //rest day special holiday Night shift
                                                                case $data -> SHND == true:

                                                                    //Special Holiday
                                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                                    $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                    $subtract = $time_in->diffInSeconds($holiday_date);
                                                                    $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                                                ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                    'work_hours' => $totalDuration,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'SHND_hours' => $special_holiday_hours
                                                                                ]);

                                                                    return response()->json([
                                    
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance Recorded Successfully',
                                        
                                                                    ]);

                                                                break;
        
                                                                //rest day regular holiday Night shift
                                                                case $data -> RHND == true:

                                                                    //REGULAR HOLIDAY YESTERDAY
                                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                                    $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                    $subtract = $time_in->diffInSeconds($holiday_date);
                                                                    $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                                                ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                    'work_hours' => $totalDuration,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'RHND_hours' => $regular_holiday_hours
                                                                                ]);

                                                                    return response()->json([
                                            
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance Recorded Successfully',
                                            
                                                                    ]);
                                                                    
                                                                break;
        
                                                                //rest day special holiday Night shift
                                                                case $data -> RDSHND == true:
        
                                                                    //REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                                    $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                    $subtract = $time_in->diffInSeconds($holiday_date);
                                                                    $restday_special_total_holiday_hours = gmdate('H:i:s', $subtract);
                                        
                                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                                                ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                    'work_hours' => $totalDuration,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'RDSHND_hours' => $restday_special_total_holiday_hours
                                                                                ]);

                                                                    return response()->json([
                                        
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance Recorded Successfully',
                                        
                                                                    ]);
                                                                break;
                                                                
                                                                //rest day regular holiday Night shift
                                                                case $data -> RDRHND == true:
                                                                    
                                                                    //REST DAY REGULAR HOLIDAY NIGHT SHIFT YESTERDAY
                                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                                    $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                    $subtract = $time_in->diffInSeconds($holiday_date);
                                                                    $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);

                                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                                                ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                    'work_hours' => $totalDuration,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'RDRHND_hours' => $restday_regular_total_holiday_hours
                                                                                ]);

                                                                    return response()->json([
                                            
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance Recorded Successfully',
                                            
                                                                    ]);

                                                                   
                                                                break;
                                                                                                                       
                                                                default:
                                                                   
                                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                                                ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                ->update(['time_out' => Carbon::parse($data->employee->sched_end),
                                                                                    'work_hours' => $totalDuration,
                                                                                    'night_diff_hours' => $night_diff_total_hours
                                                                                
                                                                                ]);
                                                            

                                                                    return response()->json([
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance updated Successfully',
                                                                    ]);
                                                                   
                                                                break;

                                                            }
                                                          
                                                        }
                                                    
                                                    }

                                                }
                                                else
                                                {

                                                    //HOURS NOW IS GREATER THAN EQUAL TO EMPLOYEE BREAKTIME  START AND LESS THAN OR EQUAL TO   EMPLOYEE BREAKTIME  END
                                                    if(Carbon::now('GMT+8')->format('H:i:s') >= Carbon::parse($data->employee->breaktime_start)->format('H:i:s') && Carbon::now('GMT+8')->format('H:i:s') <= Carbon::parse($data->employee->breaktime_end)->format('H:i:s') ){

                                                    
                                                        if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){

                                                
                                                            // NIGHT DIFFERENTIAL HOURS
                                                    
                                                            $start = Carbon::parse($data->night_shift_date);
                                                            $out = Carbon::parse($data->employee->breaktime_start)->addDay(1)->format('Y-m-d H:i:s');
                                                            $diff = $start->diffInSeconds($out);
                                                            $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                                        }
                                                        else
                                                        {
                                                            //NIGHT DIFFERENTIAL HOURS
                                                            $start = Carbon::createFromFormat('H:i:s', '22:00:00'); //10pm
                                                            $out = Carbon::parse($data->employee->breaktime_start)->addDay(1)->format('Y-m-d H:i:s');
                                                            $diff = $start ->diffInSeconds($out);
                                                            $night_diff_total_hours = gmdate('H:i:s', $diff);
                                                        }
                                                        

                                                        //CALCULATION OF UNDERTIME
                                                        $emp_sched_out = Carbon::parse($data ->employee-> sched_end)->addDay(1);
                                                        $out_today = Carbon::parse($data ->employee-> breaktime_end)->addDay(1);
                                                        $sum = $emp_sched_out -> diffInSeconds($out_today);
                                                        $undertime = gmdate('H:00:00', $sum);


                                                        //WORK HOURS
                                                        $timeout = Carbon::parse($data->employee->breaktime_start)->addDay(1)->format('Y-m-d H:i:s');
                                                        $timein = Carbon::parse($data -> night_shift_date);
                                                        $interval = $timein->diffInSeconds($timeout);
                                                        $totalDuration = gmdate('H:i:s', $interval);

                                                        //OVERTIME UPDATE QUERY
                                                        switch($data){

                                                            //Rest day Night diff
                                                            case $data -> RDND == true:
    
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDOT' => true]);
                                                            break;
    
                                                            //rest day special holiday Night diff
                                                            case $data -> SHND == true:
    
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'SHOT' => true]);
                                                            break;
    
                                                                //rest day regular holiday
                                                                case $data -> RHND == true:
    
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RHOT' => true]);
                                                            break;
    
                                                            //rest day special holiday Night diff
                                                            case $data -> RDSHND == true:
    
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDSHOT' => true]);
                                                            break;
                                                            
                                                            //rest day regular holiday Night diff
                                                            case $data -> RDRHND == true:
    
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                 'RDRHOT' => true]);
                                                            break;
                                                            
                                                            default:
                                                                 Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00']);
    
                                                            break;
                                                        }

                                                        Attendance::where('emp_no', '=', $request -> scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'work_hours' => $totalDuration,
                                                                            'undertime_hours' => $undertime,
                                                                            'night_diff_hours' => $night_diff_total_hours]);

                                                        return response()->json([

                                                            'status' => 200,
                                                            'msg' => 'Overtime Recorded Successfully',

                                                        ]);

                                                      
                                                    }
                                                    //HOURS NOW IS GREATER THAN EMPLOYEE BREAKTIME  END
                                                    else if(Carbon::now('GMT+8')->format('H:i:s') > Carbon::parse($data -> employee -> breaktime_end )->format('H:i:s') ){

                                                        if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){

                                           
                                                            // NIGHT DIFFERENTIAL HOURS
                                                            $start = Carbon::parse($data->night_shift_date);
                                                            $out =  Carbon::now('GMT+8')->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                            $diff = $start->diffInSeconds($out);
                                                            $night_diff_total_hours = gmdate('H:i:s', $diff);   
    
                                                        }
                                                        else
                                                        {
                                                            //NIGHT DIFFERENTIAL HOURS
                                                            $start = Carbon::createFromFormat('H:i:s', '22:00:00'); //10pm
                                                            $out =  Carbon::now('GMT+8')->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                            $diff = $start ->diffInSeconds($out);
                                                            $night_diff_total_hours = gmdate('H:i:s', $diff);
                                                        }

                                                            //WORK HOURS
                                                            $timeout = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                            $timein = Carbon::parse($data -> night_shift_date);
                                                            $interval = $timein->diffInSeconds($timeout);
                                                            $totalDuration = gmdate('H:i:s', $interval);

                                                            //UNDERTIME
                                                            $emp_sched_out = Carbon::parse($data ->employee-> sched_end)->addDay(1);
                                                            $out_today =Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                            $sum = $emp_sched_out -> diffInSeconds($out_today);
                                                            $undertime = gmdate('H:i:s', $sum);


                                                            //OVERTIME UPDATE QUERY
                                                            switch($data){

                                                                //Rest day Night diff
                                                                case $data -> RDND == true:
        
                                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                                            ->where('attendance_id', '=', $data -> id)
                                                                            ->update(['hours_OT' => '00:00:00',
                                                                                      'RDOT' => true]);
                                                                break;
        
                                                                //rest day special holiday Night diff
                                                                case $data -> SHND == true:
        
                                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                                            ->where('attendance_id', '=', $data -> id)
                                                                            ->update(['hours_OT' => '00:00:00',
                                                                                      'SHOT' => true]);
                                                                break;
        
                                                                    //rest day regular holiday
                                                                    case $data -> RHND == true:
        
                                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                                            ->where('attendance_id', '=', $data -> id)
                                                                            ->update(['hours_OT' => '00:00:00',
                                                                                      'RHOT' => true]);
                                                                break;
        
                                                                //rest day special holiday Night diff
                                                                case $data -> RDSHND == true:
        
                                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                                            ->where('attendance_id', '=', $data -> id)
                                                                            ->update(['hours_OT' => '00:00:00',
                                                                                      'RDSHOT' => true]);
                                                                break;
                                                                
                                                                //rest day regular holiday Night diff
                                                                case $data -> RDRHND == true:
        
                                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                                            ->where('attendance_id', '=', $data -> id)
                                                                            ->update(['hours_OT' => '00:00:00',
                                                                                     'RDRHOT' => true]);
                                                                break;
                                                                
                                                                default:
                                                                     Overtime::where('emp_number', '=', $request -> scanned)
                                                                            ->where('attendance_id', '=', $data -> id)
                                                                            ->update(['hours_OT' => '00:00:00']);
        
                                                                break;
                                                            }
    
                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                'work_hours' => $totalDuration,
                                                                                'undertime_hours' => $undertime,
                                                                                'night_diff_hours' => $night_diff_total_hours]);
    
                                                            return response()->json([
    
                                                                'status' => 200,
                                                                'msg' => 'Overtime Recorded Successfully',
    
                                                            ]);
                                                    }
                                                    //TIME OUT BEFORE BREAK TIME START
                                                    else{

                                                        if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){
                                                        
                                                            // NIGHT DIFFERENTIAL HOURS
                                                            $start = Carbon::parse($data->night_shift_date);
                                                            $out =  Carbon::now('GMT+8')->addDay(1)->format('Y-m-d H:i:s');
                                                            $diff = $start->diffInSeconds($out);
                                                            $night_diff_total_hours = gmdate('H:i:s', $diff);   
    
                                                        }
                                                        else
                                                        {
                                                            //NIGHT DIFFERENTIAL HOURS
                                                            $start = Carbon::createFromFormat('H:i:s', '22:00:00'); //10pm
                                                            $out =  Carbon::now('GMT+8')->addDay(1)->format('Y-m-d H:i:s');
                                                            $diff = $start ->diffInSeconds($out);
                                                            $night_diff_total_hours = gmdate('H:i:s', $diff);
                                                        }

                                                        //WORK HOURS
                                                        $timeout = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                        $timein = Carbon::parse($data -> night_shift_date);
                                                        $interval = $timein->diffInSeconds($timeout);
                                                        $totalDuration = gmdate('H:i:s', $interval);

                                                        //Minus 1 HR UNDERTIME
                                                        $emp_sched_out = Carbon::parse($data ->employee-> sched_end)->addDay(1)->subHour(1);
                                                        $out_today =Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                        $sum = $emp_sched_out -> diffInSeconds($out_today);
                                                        $undertime = gmdate('H:i:s', $sum);
                                                        
                                                        //OVERTIME UPDATE QUERY
                                                        switch($data){

                                                            //Rest day Night diff
                                                            case $data -> RDND == true:
    
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDOT' => true]);
                                                            break;
    
                                                            //rest day special holiday Night diff
                                                            case $data -> SHND == true:
    
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'SHOT' => true]);
                                                            break;
    
                                                                //rest day regular holiday
                                                                case $data -> RHND == true:
    
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RHOT' => true]);
                                                            break;
    
                                                            //rest day special holiday Night diff
                                                            case $data -> RDSHND == true:
    
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDSHOT' => true]);
                                                            break;
                                                            
                                                            //rest day regular holiday Night diff
                                                            case $data -> RDRHND == true:
    
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                 'RDRHOT' => true]);
                                                            break;
                                                            
                                                            default:
                                                                 Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->where('attendance_id', '=', $data -> id)
                                                                        ->update(['hours_OT' => '00:00:00']);
    
                                                            break;
                                                        }

                                                        Attendance::where('emp_no', '=', $request -> scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'work_hours' => $totalDuration,
                                                                            'undertime_hours' => $undertime,
                                                                            'night_diff_hours' => $night_diff_total_hours]);

                                                        return response()->json([

                                                            'status' => 200,
                                                            'msg' => 'Overtime Recorded Successfully',

                                                        ]);

                                                    }
           
                                                }
                                                

                                            }
                                            else
                                            {
                                               
                                                //Time out is Greater than equal to  schedule of employee
                                                if(Carbon::now('GMT+8')->format('H:i:s') >= $data -> employee -> sched_end)
                                                {

                                                    $schedule_end = Carbon::parse($data -> employee -> sched_end)->addDay(1); //out sched
                                                    $employee_in = Carbon::parse($data -> night_shift_date); // in
                                                    $interval = $employee_in->diffInSeconds($schedule_end);
                                                  
                                                     $interval -= 3600; // 3600 seconds = 1 hour

                                                    // Check if the interval is negative and adjust it if necessary
                                                    if ($interval < 0) {
                                                        $interval = 0; // Set to zero to avoid negative durations
                                                    }

                                                    // Format the interval as H:i:s
                                                    $employee_total_work_hours = gmdate('H:i:s', $interval);

                                                    if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){

                                                   
                                                            // NIGHT DIFFERENTIAL HOURS
                                                            $start = Carbon::parse($data->night_shift_date);
                                                            $out =  Carbon::now('GMT+8')->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                            $diff = $start->diffInSeconds($out);
                                                            $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                                    }
                                                    
                                                    else
                                                    {
                                                        //NIGHT DIFFERENTIAL HOURS
                                                        $start = Carbon::createFromFormat('H:i:s', '22:00:00'); //10pm
                                                        $out =  Carbon::createFromFormat('H:i:s', '06:00:00')->addDay(1)->subHour(1);
                                                        $diff = $start ->diffInSeconds($out);
                                                        $night_diff_total_hours = gmdate('H:i:s', $diff);
                                                    }

                                                    foreach($holidays as $holiday){
                                                                 
                                                        $todays_Date = Carbon::today('GMT+8')->format('m-d');
                                                        $office_holiday = Carbon::parse($holiday->holiday_date)->format('m-d');


                                                        if($todays_Date === $office_holiday){

                                                            if($data->RDND == true && $holiday->holiday_type == 'Regular'){
                                            
                                                                //REST DAY NIGHT SHIFT
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                        
                                                                //REGULAR HOLIDAY
                                                                $out = Carbon::parse($data -> employee -> sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                                
                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                        ->update(['time_out' => $data -> employee -> sched_end,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'work_hours' => $employee_total_work_hours,
                                                                                    'RHND' => true,
                                                                                    'RDND_hours' => $restday_total_holiday_hours,
                                                                                    'RHND_hours' => $regular_holiday_hours,
                                                                                ]);
                                        
                                                                return response()->json([
                                        
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                        
                                                                ]);
                                        
                                        
                                                            }
                                                            elseif($data->RHND == true && $holiday->holiday_type == 'Regular'){
                                                               
                                                                //FOR THE RHND 
                                                                //ADD the previous holiday which is Regular holiday to toddays holiday which Regular holiday again
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $time_out = Carbon::parse($data -> employee -> sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($time_out);
                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                                
                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                               ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                              ->update(['time_out' => $data -> employee -> sched_end,
                                                                                        'night_diff_hours' => $night_diff_total_hours,
                                                                                        'work_hours' => $employee_total_work_hours,
                                                                                        'night_diff_hours' => $night_diff_total_hours,
                                                                                        'RHND_hours' => $regular_holiday_hours,
                                                                                    
                                                                                    ]);
                                        
                                                                return response()->json([
                                        
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                        
                                                                ]);
                                        
                                                            }
                                                            elseif($data->RDRHND == true && $holiday->holiday_type == 'Regular'){
                                                           
                                                                //REST DAY REGULAR HOLIDAY NIGHT SHIFT
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);
                                        
                                                                //REGULAR HOLIDAY
                                                                $out = Carbon::parse($data -> employee -> sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                              
                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                          ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                         ->update(['time_out' => $data -> employee -> sched_end,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'work_hours' => $employee_total_work_hours,
                                                                                    'RHND' => true,
                                                                                    'RDRHND_hours' => $restday_regular_total_holiday_hours,
                                                                                    'RHND_hours' => $regular_holiday_hours,
                                                                                ]);
                                        
                                                                return response()->json([
                                        
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                        
                                                                ]);
                                        
                                                            }  
                                                            elseif($data->RHND == true && $holiday->holiday_type == 'Special'){
                                                               
                                                                //REGULAR HOLIDAY YESTERDAY
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                //Special Holiday nextday
                                                                $out = Carbon::parse($data -> employee -> sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                          ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                         ->update(['time_out' => $data -> employee -> sched_end,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'work_hours' => $employee_total_work_hours,
                                                                                    'SHND' => true,
                                                                                    'SHND_hours' => $special_holiday_hours,
                                                                                    'RHND_hours' => $regular_holiday_hours,
                                                                                ]);
                                               
                                        
                                                                return response()->json([
                                        
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                        
                                                                ]);


                                                            }
                                                            elseif($data->RDRHND == true && $holiday->holiday_type == 'Special'){
                                                             
                                                                //REST DAY REGULAR HOLIDAY NIGHT SHIFT YESTERDAY
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);

                                                                 //Special Holiday nextday
                                                                $out = Carbon::parse($data -> employee -> sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                          ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                          ->update(['time_out' => $data -> employee -> sched_end,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'work_hours' => $employee_total_work_hours,
                                                                                    'SHND' => true,
                                                                                    'SHND_hours' => $special_holiday_hours,
                                                                                    'RDRHND_hours' => $restday_regular_total_holiday_hours,
                                                                                ]);
                                                                return response()->json([
                                        
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                        
                                                                ]);


                                                            }
                                                            elseif($data->RDND == true && $holiday->holiday_type == 'Special'){
    
                                                                //REST DAY NIGHT SHIFT
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                    
                                                                //SPECIAL HOLIDAY
                                                                $out = Carbon::parse($data->employee->sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);
                                                            
                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => $data -> employee -> sched_end,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'work_hours' => $employee_total_work_hours,
                                                                                    'SHND' => true,
                                                                                    'RDND_hours' => $restday_total_holiday_hours,
                                                                                    'SHND_hours' => $special_holiday_hours,
                                                                                
                                                                                ]);
                                    
                                                                return response()->json([
                                    
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                    
                                                                ]);
                                    
                                    
                                                            }
                                                            elseif($data->SHND == true && $holiday->holiday_type == 'Special'){
                                                                //FOR THE SHND 
                                                                //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $time_out = Carbon::parse($data -> employee -> sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($time_out);
                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);
                                                            
                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => $data -> employee -> sched_end,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'work_hours' => $employee_total_work_hours,
                                                                                    'SHND_hours' => $special_holiday_hours,
                                                                                ]);
                                    
                                                                return response()->json([
                                    
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                    
                                                                ]);
                                                            }
                                                            elseif($data->RDSHND == true && $holiday->holiday_type == 'Special'){
                                            
                                                                //REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $restday_special_total_holiday_hours = gmdate('H:i:s', $subtract);
                                    
                                                                //SPECIAL HOLIDAY
                                                                $out = Carbon::parse($data->employee->sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);
                                    
                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                        ->update(['time_out' => $data -> employee -> sched_end,
                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                'work_hours' => $employee_total_work_hours,
                                                                                'SHND' => true,
                                                                                'RDSHND_hours' => $restday_special_total_holiday_hours,
                                                                                'SHND_hours' => $special_holiday_hours,
                                                                            ]);
                                                                
                                                                return response()->json([
                                    
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                    
                                                                ]);
                                                            }
                                                            elseif($data->SHND == true && $holiday->holiday_type == 'Regular'){

                                                                //FOR THE SHND 
                                                                //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again
                                                             
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                //Regular HOLIDAY
                                                                $out = Carbon::parse($data->employee->sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                
                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => $data -> employee -> sched_end,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'work_hours' => $employee_total_work_hours,
                                                                                    'RHND' => true,
                                                                                    'SHND_hours' => $special_holiday_hours,
                                                                                    'RHND_hours' => $regular_holiday_hours,
                                                                                ]);
                                                                return response()->json([
                                
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                    
                                                                ]);

                                                            }
                                                            else{
                                                                
                                                                // $data->RDSHND == true && $holiday->holiday_type == 'Regular'
                                                                 //FOR THE SHND 
                                                                //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again   
                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                $restday_special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                //Regular HOLIDAY
                                                                $out = Carbon::parse($data->employee->sched_end)->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                $timeout = Carbon::parse($out);
                                                                $subtract = $timeout->diffInSeconds($start);
                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => $data -> employee -> sched_end,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'work_hours' => $employee_total_work_hours,
                                                                                    'RHND' => true,
                                                                                    'RDSHND_hours' => $restday_special_holiday_hours,
                                                                                    'RHND_hours' => $regular_holiday_hours,
                                                                                ]);

                                                                return response()->json([
                                
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                    
                                                                ]);
                                                            }
                                    
                                                        }   
                                                        //NEED TO TEST TOMMOROW
                                                        else{   
                                                          
                                                            #need to use switch case
                                                            switch($data){
                                                               
                                                                //Rest day Night shift
                                                                case $data -> RDND == true:
                                                                   
                                                                    //REST DAY NIGHT SHIFT
                                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                                    $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                    $subtract = $time_in->diffInSeconds($holiday_date);
                                                                    $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                            
                                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => $data -> employee -> sched_end,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'work_hours' => $employee_total_work_hours,
                                                                                    'RDND_hours' => $restday_total_holiday_hours,
                                                                                ]);
                                            
                                                                    return response()->json([
                                            
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance Recorded Successfully',
                                            
                                                                    ]);

                                                                break;
        
                                                                //rest day special holiday Night shift
                                                                case $data -> SHND == true:

                                                                    //Special Holiday
                                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                                    $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                    $subtract = $time_in->diffInSeconds($holiday_date);
                                                                    $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                                                ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                                ->update(['time_out' => $data -> employee -> sched_end,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'work_hours' => $employee_total_work_hours,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                        
                                                                                        ]);
                                                                
                                                                    return response()->json([
                                    
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance Recorded Successfully',
                                        
                                                                    ]);

                                                                break;
        
                                                                //rest day regular holiday Night shift
                                                                case $data -> RHND == true:

                                                                    //REGULAR HOLIDAY YESTERDAY
                                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                                    $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                    $subtract = $time_in->diffInSeconds($holiday_date);
                                                                    $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                                                ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                                ->update(['time_out' => $data -> employee -> sched_end,
                                                                                        'night_diff_hours' => $night_diff_total_hours,
                                                                                        'work_hours' => $employee_total_work_hours,
                                                                                        'RHND_hours' => $regular_holiday_hours,
                                                                                    ]);

                                                                    return response()->json([
                                            
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance Recorded Successfully',
                                            
                                                                    ]);
                                                                    
                                                                break;
        
                                                                //rest day special holiday Night shift
                                                                case $data -> RDSHND == true:
        
                                                                    //REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                                    $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                    $subtract = $time_in->diffInSeconds($holiday_date);
                                                                    $restday_special_total_holiday_hours = gmdate('H:i:s', $subtract);
                                        
                                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                                                ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                                ->update(['time_out' => $data -> employee -> sched_end,
                                                                                        'night_diff_hours' => $night_diff_total_hours,
                                                                                        'work_hours' => $employee_total_work_hours,
                                                                                        'RDSHND_hours' => $restday_special_total_holiday_hours,
                                                                                    ]);
                                                                    return response()->json([
                                        
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance Recorded Successfully',
                                        
                                                                    ]);
                                                                break;
                                                                
                                                                //rest day regular holiday Night shift
                                                                case $data -> RDRHND == true:
                                                                    
                                                                    //REST DAY REGULAR HOLIDAY NIGHT SHIFT YESTERDAY
                                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                                    $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                    $subtract = $time_in->diffInSeconds($holiday_date);
                                                                    $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);

                                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                                                ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                                ->update(['time_out' => $data -> employee -> sched_end,
                                                                                        'night_diff_hours' => $night_diff_total_hours,
                                                                                        'work_hours' => $employee_total_work_hours,
                                                                                        'RDRHND_hours' => $restday_regular_total_holiday_hours,
                                                                                    ]);
                                                                    return response()->json([
                                            
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance Recorded Successfully',
                                            
                                                                    ]);

                                                                   
                                                                break;
                                                                                                                       
                                                                default:
                                                                   
                                                                     Attendance::where('emp_no', '=', $request -> scanned)
                                                                              ->where('date', '=', Carbon::now('GMT+8')->subDays(1)->format('Y-m-d'))
                                                                              ->update(['time_out' => $data -> employee -> sched_end,
                                                                                        'night_diff_hours' => $night_diff_total_hours,
                                                                                        'work_hours' => $employee_total_work_hours]);
                                                            

                                                                    return response()->json([
                                                                        'status' => 200,
                                                                        'msg' => 'Attendance updated Successfully',
                                                                    ]);
                                                                   
                                                                break;

                                                            }
                                                          
                                                        }
                                                    
                                                    }
                                                    


                                                }
                                                else
                                                {
                                                    
                                                    if($data -> emp_no == $request -> scanned && $data -> date == Carbon::now('GMT+8')->subDay(1)->format('Y-m-d') && $data -> time_out === null){

                                                        if(Carbon::now('GMT+8')->format('H:i:s') < $data ->employee->sched_end )
                                                        {
                                                            
                                                            //HOURS NOW IS GREATER THAN EQUAL TO EMPLOYEE BREAKTIME  START AND LESS THAN OR EQUAL TO   EMPLOYEE BREAKTIME  END
                                                            if(Carbon::now('GMT+8')->format('H:i:s') >= Carbon::parse($data->employee->breaktime_start)->format('H:i:s') && Carbon::now('GMT+8')->format('H:i:s') <= Carbon::parse($data->employee->breaktime_end)->format('H:i:s') ){


                                                                if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){

                                                
                                                                    // NIGHT DIFFERENTIAL HOURS
                                                            
                                                                    $start = Carbon::parse($data->night_shift_date);
                                                                    $out = Carbon::parse($data->employee->breaktime_start)->addDay(1)->format('Y-m-d H:i:s');
                                                                    $diff = $start->diffInSeconds($out);
                                                                    $night_diff_total_hours = gmdate('H:i:s', $diff);   
        
                                                                }
                                                                else
                                                                {
                                                                    //NIGHT DIFFERENTIAL HOURS
                                                                    $start = Carbon::createFromFormat('H:i:s', '22:00:00'); //10pm
                                                                    $out = Carbon::parse($data->employee->breaktime_start)->addDay(1)->format('Y-m-d H:i:s');
                                                                    $diff = $start ->diffInSeconds($out);
                                                                    $night_diff_total_hours = gmdate('H:i:s', $diff);
                                                                }

                                                                //CALCULATION OF UNDERTIME
                                                                $emp_sched_out = Carbon::parse($data ->employee-> sched_end)->addDay(1);
                                                                $out_today = Carbon::parse($data ->employee-> breaktime_end)->addDay(1);
                                                                $sum = $emp_sched_out -> diffInSeconds($out_today);
                                                                $undertime = gmdate('H:00:00', $sum);

                                                                //WORK HOURS
                                                                $timeout = Carbon::parse($data->employee->breaktime_start)->addDay(1)->format('Y-m-d H:i:s');
                                                                $timein = Carbon::parse($data -> night_shift_date);
                                                                $interval = $timein->diffInSeconds($timeout);
                                                                $totalDuration = gmdate('H:i:s', $interval);

                                                                
                                                                foreach($holidays as $holiday){
                                                                 
                                                                    $todays_Date = Carbon::today('GMT+8')->format('m-d');
                                                                    $office_holiday = Carbon::parse($holiday->holiday_date)->format('m-d');


                                                                    if($todays_Date === $office_holiday){

                                                                        if($data->RDND == true && $holiday->holiday_type == 'Regular'){
                                                                            // dd('1');
                                                                            //REST DAY NIGHT SHIFT
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                                            //REGULAR HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'RHND' => true,
                                                                                            'RDND_hours' => $restday_total_holiday_hours,
                                                                                            'RHND_hours' => $regular_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);
                                                    
                                                    
                                                                        }
                                                                        elseif($data->RHND == true && $holiday->holiday_type == 'Regular'){
                                                                            // dd('2');
                                                                            //FOR THE RHND 
                                                                            //ADD the previous holiday which is Regular holiday to toddays holiday which Regular holiday again
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                                            
                                                                            //dd('RHND at Holiday ngayon');
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                      ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                      ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'RHND_hours' => $regular_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);
                                                    
                                                                        }
                                                                        elseif($data->RDRHND == true && $holiday->holiday_type == 'Regular'){
                                                                            // dd('3');
                                                                            //REST DAY REGULAR HOLIDAY NIGHT SHIFT
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                                            //REGULAR HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'RHND' => true,
                                                                                            'RDRHND_hours' => $restday_regular_total_holiday_hours,
                                                                                            'RHND_hours' => $regular_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);
                                                    
                                                                        }  
                                                                        elseif($data->RHND == true && $holiday->holiday_type == 'Special'){
                                                                           
                                                                            //REGULAR HOLIDAY YESTERDAY
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            //Special Holiday nextday
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND' => true,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                            'RHND_hours' => $regular_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);


                                                                        }
                                                                        elseif($data->RDRHND == true && $holiday->holiday_type == 'Special'){
                                                                             //  dd('ops');
                                                                             //REST DAY REGULAR HOLIDAY NIGHT SHIFT YESTERDAY
                                                                             $time_in = Carbon::parse($data->night_shift_date);
                                                                             $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                             $subtract = $time_in->diffInSeconds($holiday_date);
                                                                             $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);

                                                                             //Special Holiday nextday
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND' => true,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                            'RDRHND_hours' => $restday_regular_total_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);


                                                                        }
                                                                        elseif($data->RDND == true && $holiday->holiday_type == 'Special'){
                                                                            // dd('4');
                                                                            //REST DAY NIGHT SHIFT
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                
                                                                                //REGULAR HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);
                                                
                                                                        
                                                
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND' => true,
                                                                                            'RDND_hours' => $restday_total_holiday_hours,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                        
                                                                                        ]);
                                                
                                                                            return response()->json([
                                                
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);
                                                
                                                
                                                                        }
                                                                        elseif($data->SHND == true && $holiday->holiday_type == 'Special'){
                                                                        
                                                                             //FOR THE SHND 
                                                                            //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);
                                                                        
                                                                        
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                        
                                                                                        ]);
                                                
                                                                            return response()->json([
                                                
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);
                                                                        }
                                                                        elseif($data->RDSHND == true && $holiday->holiday_type == 'Special'){
                                                                            // dd('6');
                                                                            //REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_special_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                
                                                                            //SPECIAL HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);
                                                
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND' => true,
                                                                                            'RDSHND_hours' => $restday_special_total_holiday_hours,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                        
                                                                                        ]);
                                                
                                                                            return response()->json([
                                                
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);
                                                                        }
                                                                        elseif($data->SHND == true && $holiday->holiday_type == 'Regular'){

                                                                            // dd('1');
                                                                            //FOR THE SHND 
                                                                            //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again
                                                                         
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            //Regular HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                    'work_hours' => $totalDuration,
                                                                                    'undertime_hours' => $undertime,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'RHND' => true,
                                                                                    'SHND_hours' => $special_holiday_hours,
                                                                                    'RHND_hours' => $regular_holiday_hours,
                                                                                
                                                                                ]);
                                                                        
                                                                            return response()->json([
                                            
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);

                                                                        }
                                                                        else{
                                                                            
                                                                            // $data->RDSHND == true && $holiday->holiday_type == 'Regular'
                                                                            // dd('2');

                                                                             //FOR THE SHND 
                                                                            //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again   
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_special_holiday_hours = gmdate('H:i:s', $subtract);


                                                                            //Regular HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                    'work_hours' => $totalDuration,
                                                                                    'undertime_hours' => $undertime,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'RHND' => true,
                                                                                    'RDSHND_hours' => $restday_special_holiday_hours,
                                                                                    'RHND_hours' => $regular_holiday_hours,
                                                                                
                                                                                ]);
                                                                            return response()->json([
                                            
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);
                                                                        }
                                                
                                                                    }   
                                                                    else{   
                                                                      
                                                                        
                                                                        #need to use switch case
                                                                        switch($data){
                                                                           
                                                                            //Rest day Night shift
                                                                            case $data -> RDND == true:
                                                                            
                                                                                //REST DAY NIGHT SHIFT
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                        
                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                'work_hours' => $totalDuration,
                                                                                                'undertime_hours' => $undertime,
                                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                                'RDND_hours' => $restday_total_holiday_hours,
                                                                                            ]);
                                                        
                                                                                return response()->json([
                                                        
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                        
                                                                                ]);

                                                                                break;
                    
                                                                            //rest day special holiday Night shift
                                                                            case $data -> SHND == true:

                                                                                //Special Holiday
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                        'work_hours' => $totalDuration,
                                                                                        'undertime_hours' => $undertime,
                                                                                        'night_diff_hours' => $night_diff_total_hours,
                                                                                        'SHND_hours' => $special_holiday_hours,
                                                                                    
                                                                                    ]);
                                                                            
                                                                                return response()->json([
                                                
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                                ]);

                                                                                 break;
                    
                                                                            //rest day regular holiday Night shift
                                                                            case $data -> RHND == true:

                                                                                //REGULAR HOLIDAY YESTERDAY
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                'work_hours' => $totalDuration,
                                                                                                'undertime_hours' => $undertime,
                                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                                'RHND_hours' => $regular_holiday_hours,
                                                                                            
                                                                                            ]);
                                                    
                                                                                return response()->json([
                                                        
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                        
                                                                                ]);
                                                                                
                                                                                 break;
                    
                                                                            //rest day special holiday Night shift
                                                                            case $data -> RDSHND == true:
                    
                                                                                //REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $restday_special_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                    
                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                'work_hours' => $totalDuration,
                                                                                                'undertime_hours' => $undertime,
                                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                                'RDSHND_hours' => $restday_special_total_holiday_hours,
                                                                                            
                                                                                            ]);
                                                    
                                                                                return response()->json([
                                                    
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                                ]);
                                                                                 break;
                                                                            
                                                                            //rest day regular holiday Night shift
                                                                            case $data -> RDRHND == true:
                                                                                
                                                                                //REST DAY REGULAR HOLIDAY NIGHT SHIFT YESTERDAY
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);

                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                'work_hours' => $totalDuration,
                                                                                                'undertime_hours' => $undertime,
                                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                                'RDRHND_hours' => $restday_regular_total_holiday_hours,
                                                                                            
                                                                                            ]);
                                                        
                                                                                return response()->json([
                                                        
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                        
                                                                                ]);

                                                                               
                                                                                break;
                                                                            
                                                                           
                                                                    
                                                                            default:
                                                                               
                                                                                    //Update attendance if yesterday is not holiday 
                                                                                    Attendance::where('emp_no', '=', $request -> scanned)
                                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                            ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                    'work_hours' => $totalDuration,
                                                                                                    'undertime_hours' => $undertime,
                                                                                                    'night_diff_hours' => $night_diff_total_hours]);
                        
                                                                                    return response()->json([
                            
                                                                                        'status' => 200,
                                                                                        'msg' => 'Attendance Recorded Successfully',
                            
                                                                                    ]);
                        
                                                                             break;


                                                                        }

                                                                     
                                                                        
                                                                    }
                                                                   


                                                                }


                                                            }
                                                            //HOURS NOW IS GREATER THAN EMPLOYEE BREAKTIME  END
                                                            else if(Carbon::now('GMT+8')->format('H:i:s') > Carbon::parse($data -> employee -> breaktime_end )->format('H:i:s') ){

                                                                if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){

                                           
                                                                    // NIGHT DIFFERENTIAL HOURS
                                                                    $start = Carbon::parse($data->night_shift_date);
                                                                    $out =  Carbon::now('GMT+8')->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                    $diff = $start->diffInSeconds($out);
                                                                    $night_diff_total_hours = gmdate('H:i:s', $diff);   
            
                                                                }
                                                                else
                                                                {
                                                                    //NIGHT DIFFERENTIAL HOURS
                                                                    $start = Carbon::createFromFormat('H:i:s', '22:00:00'); //10pm
                                                                    $out =  Carbon::now('GMT+8')->addDay(1)->subHour(1)->format('Y-m-d H:i:s');
                                                                    $diff = $start ->diffInSeconds($out);
                                                                    $night_diff_total_hours = gmdate('H:i:s', $diff);
                                                                }

                                                                //WORK HOURS
                                                                $timeout = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                                $timein = Carbon::parse($data -> night_shift_date);
                                                                $interval = $timein->diffInSeconds($timeout);
                                                                $totalDuration = gmdate('H:i:s', $interval);

                                                                //UNDERTIME
                                                                $emp_sched_out = Carbon::parse($data ->employee-> sched_end)->addDay(1);
                                                                $out_today =Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                $sum = $emp_sched_out -> diffInSeconds($out_today);
                                                                $undertime = gmdate('H:i:s', $sum);

                                                                
                                                                foreach($holidays as $holiday){
                                                                 
                                                                    $todays_Date = Carbon::today('GMT+8')->format('m-d');
                                                                    $office_holiday = Carbon::parse($holiday->holiday_date)->format('m-d');


                                                                    if($todays_Date === $office_holiday){

                                                                        if($data->RDND == true && $holiday->holiday_type == 'Regular'){
                                                                            // dd('1');
                                                                            //REST DAY NIGHT SHIFT
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                                            //REGULAR HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                                        
                                                    
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'RHND' => true,
                                                                                            'RDND_hours' => $restday_total_holiday_hours,
                                                                                            'RHND_hours' => $regular_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);
                                                    
                                                    
                                                                        }
                                                                        elseif($data->RHND == true && $holiday->holiday_type == 'Regular'){
                                                                            // dd('2');
                                                                            //FOR THE RHND 
                                                                            //ADD the previous holiday which is Regular holiday to toddays holiday which Regular holiday again
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                                            
                                                                            //dd('RHND at Holiday ngayon');
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                      ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                      ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'RHND_hours' => $regular_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);
                                                    
                                                                        }
                                                                        elseif($data->RDRHND == true && $holiday->holiday_type == 'Regular'){
                                                                            // dd('3');
                                                                            //REST DAY REGULAR HOLIDAY NIGHT SHIFT
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                                            //REGULAR HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'RHND' => true,
                                                                                            'RDRHND_hours' => $restday_regular_total_holiday_hours,
                                                                                            'RHND_hours' => $regular_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);
                                                    
                                                                        }  
                                                                        elseif($data->RHND == true && $holiday->holiday_type == 'Special'){
                                                                           
                                                                            //REGULAR HOLIDAY YESTERDAY
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            //Special Holiday nextday
                                                                            $out = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND' => true,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                            'RHND_hours' => $regular_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);


                                                                        }
                                                                        elseif($data->RDRHND == true && $holiday->holiday_type == 'Special'){
                                                                          //  dd('ops');
                                                                             //REST DAY REGULAR HOLIDAY NIGHT SHIFT YESTERDAY
                                                                             $time_in = Carbon::parse($data->night_shift_date);
                                                                             $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                             $subtract = $time_in->diffInSeconds($holiday_date);
                                                                             $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);

                                                                             //Special Holiday nextday
                                                                            $out = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND' => true,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                            'RDRHND_hours' => $restday_regular_total_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);


                                                                        }
                                                                        elseif($data->RDND == true && $holiday->holiday_type == 'Special'){
                                                                            // dd('4');
                                                                            //REST DAY NIGHT SHIFT
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                
                                                                                //REGULAR HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);
                                                
                                                                        
                                                
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND' => true,
                                                                                            'RDND_hours' => $restday_total_holiday_hours,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                        
                                                                                        ]);
                                                
                                                                            return response()->json([
                                                
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);
                                                
                                                
                                                                        }
                                                                        elseif($data->SHND == true && $holiday->holiday_type == 'Special'){
                                                                            // dd('5');
                                                                             //FOR THE SHND 
                                                                            //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);
                                                                        
                                                                        
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                        
                                                                                        ]);
                                                
                                                                            return response()->json([
                                                
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);
                                                                        }
                                                                        elseif($data->RDSHND == true && $holiday->holiday_type == 'Special'){
                                                                            // dd('6');
                                                                            //REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_special_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                
                                                                            //SPECIAL HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);
                                                
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND' => true,
                                                                                            'RDSHND_hours' => $restday_special_total_holiday_hours,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                        
                                                                                        ]);
                                                
                                                                            return response()->json([
                                                
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);
                                                                        }
                                                                        elseif($data->SHND == true && $holiday->holiday_type == 'Regular'){

                                                                            // dd('1');
                                                                            //FOR THE SHND 
                                                                            //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again
                                                                         
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            //Regular HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                    'work_hours' => $totalDuration,
                                                                                    'undertime_hours' => $undertime,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'RHND' => true,
                                                                                    'SHND_hours' => $special_holiday_hours,
                                                                                    'RHND_hours' => $regular_holiday_hours,
                                                                                
                                                                                ]);
                                                                        
                                                                            return response()->json([
                                            
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);

                                                                        }
                                                                        else{
                                                                            
                                                                            // $data->RDSHND == true && $holiday->holiday_type == 'Regular'
                                                                            // dd('2');

                                                                             //FOR THE SHND 
                                                                            //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again   
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_special_holiday_hours = gmdate('H:i:s', $subtract);


                                                                            //Regular HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->subHour(1)->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                    'work_hours' => $totalDuration,
                                                                                    'undertime_hours' => $undertime,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'RHND' => true,
                                                                                    'RDSHND_hours' => $restday_special_holiday_hours,
                                                                                    'RHND_hours' => $regular_holiday_hours,
                                                                                
                                                                                ]);
                                                                            return response()->json([
                                            
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);
                                                                        }
                                                
                                                                    }   
                                                                    else{   
                                                                      
                                                                        
                                                                        #need to use switch case
                                                                        switch($data){
                                                                           
                                                                            //Rest day Night shift
                                                                            case $data -> RDND == true:
                                                                            
                                                                                //REST DAY NIGHT SHIFT
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                        
                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                'work_hours' => $totalDuration,
                                                                                                'undertime_hours' => $undertime,
                                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                                'RDND_hours' => $restday_total_holiday_hours,
                                                                                            ]);
                                                        
                                                                                return response()->json([
                                                        
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                        
                                                                                ]);

                                                                                break;
                    
                                                                            //rest day special holiday Night shift
                                                                            case $data -> SHND == true:

                                                                                //Special Holiday
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                        'work_hours' => $totalDuration,
                                                                                        'undertime_hours' => $undertime,
                                                                                        'night_diff_hours' => $night_diff_total_hours,
                                                                                        'SHND_hours' => $special_holiday_hours,
                                                                                    
                                                                                    ]);
                                                                            
                                                                                return response()->json([
                                                
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                                ]);

                                                                                 break;
                    
                                                                            //rest day regular holiday Night shift
                                                                            case $data -> RHND == true:

                                                                                //REGULAR HOLIDAY YESTERDAY
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                'work_hours' => $totalDuration,
                                                                                                'undertime_hours' => $undertime,
                                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                                'RHND_hours' => $regular_holiday_hours,
                                                                                            
                                                                                            ]);
                                                    
                                                                                return response()->json([
                                                        
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                        
                                                                                ]);
                                                                                
                                                                                 break;
                    
                                                                            //rest day special holiday Night shift
                                                                            case $data -> RDSHND == true:
                    
                                                                                //REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $restday_special_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                    
                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                'work_hours' => $totalDuration,
                                                                                                'undertime_hours' => $undertime,
                                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                                'RDSHND_hours' => $restday_special_total_holiday_hours,
                                                                                            
                                                                                            ]);
                                                    
                                                                                return response()->json([
                                                    
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                                ]);
                                                                                 break;
                                                                            
                                                                            //rest day regular holiday Night shift
                                                                            case $data -> RDRHND == true:
                                                                                
                                                                                //REST DAY REGULAR HOLIDAY NIGHT SHIFT YESTERDAY
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);

                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                'work_hours' => $totalDuration,
                                                                                                'undertime_hours' => $undertime,
                                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                                'RDRHND_hours' => $restday_regular_total_holiday_hours,
                                                                                            
                                                                                            ]);
                                                        
                                                                                return response()->json([
                                                        
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                        
                                                                                ]);

                                                                               
                                                                                 break;
                                                                            
                                                                           
                                                                    
                                                                                 default:
                                                                               
                                                                              
                                                                                //Update attendance if yesterday is not holiday 
                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                'work_hours' => $totalDuration,
                                                                                                'undertime_hours' => $undertime,
                                                                                                'night_diff_hours' => $night_diff_total_hours]);
                    
                                                                                return response()->json([
                        
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                        
                                                                                ]);
                    
                                                                                 break;
                                                                        }

                                                                     
                                                                        
                                                                    }
                                                                   


                                                                }
                                                                
                                                            }
                                                            //TIME OUT BEFORE BREAK TIME START
                                                            else{

                                                                if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){
                                                        
                                                                    // NIGHT DIFFERENTIAL HOURS
                                                                    $start = Carbon::parse($data->night_shift_date);
                                                                    $out =  Carbon::now('GMT+8')->addDay(1)->format('Y-m-d H:i:s');
                                                                    $diff = $start->diffInSeconds($out);
                                                                    $night_diff_total_hours = gmdate('H:i:s', $diff);   
            
                                                                }
                                                                else
                                                                {
                                                                    //NIGHT DIFFERENTIAL HOURS
                                                                    $start = Carbon::createFromFormat('H:i:s', '22:00:00'); //10pm
                                                                    $out =  Carbon::now('GMT+8')->addDay(1)->format('Y-m-d H:i:s');
                                                                    $diff = $start ->diffInSeconds($out);
                                                                    $night_diff_total_hours = gmdate('H:i:s', $diff);
                                                                }

                                                                //WORK HOURS
                                                                $timeout = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                $timein = Carbon::parse($data -> night_shift_date);
                                                                $interval = $timein->diffInSeconds($timeout);
                                                                $totalDuration = gmdate('H:i:s', $interval);

                                                                //Minus 1 HR UNDERTIME
                                                                $emp_sched_out = Carbon::parse($data ->employee-> sched_end)->addDay(1)->subHour(1);
                                                                $out_today =Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                $sum = $emp_sched_out -> diffInSeconds($out_today);
                                                                $undertime = gmdate('H:i:s', $sum);
                                                                
                                                                foreach($holidays as $holiday){
                                                                 
                                                                    $todays_Date = Carbon::today('GMT+8')->format('m-d');
                                                                    $office_holiday = Carbon::parse($holiday->holiday_date)->format('m-d');


                                                                    if($todays_Date === $office_holiday){

                                                                        if($data->RDND == true && $holiday->holiday_type == 'Regular'){
                                                                            // dd('1');
                                                                            //REST DAY NIGHT SHIFT
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                                            //REGULAR HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'RHND' => true,
                                                                                            'RDND_hours' => $restday_total_holiday_hours,
                                                                                            'RHND_hours' => $regular_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);
                                                    
                                                    
                                                                        }
                                                                        elseif($data->RHND == true && $holiday->holiday_type == 'Regular'){
                                                                            // dd('2');
                                                                            //FOR THE RHND 
                                                                            //ADD the previous holiday which is Regular holiday to toddays holiday which Regular holiday again
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                                            
                                                                            //dd('RHND at Holiday ngayon');
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                      ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                      ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'RHND_hours' => $regular_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);
                                                    
                                                                        }
                                                                        elseif($data->RDRHND == true && $holiday->holiday_type == 'Regular'){
                                                                            // dd('3');
                                                                            //REST DAY REGULAR HOLIDAY NIGHT SHIFT
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                                            //REGULAR HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'RHND' => true,
                                                                                            'RDRHND_hours' => $restday_regular_total_holiday_hours,
                                                                                            'RHND_hours' => $regular_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);
                                                    
                                                                        }  
                                                                        elseif($data->RHND == true && $holiday->holiday_type == 'Special'){
                                                                           
                                                                            //REGULAR HOLIDAY YESTERDAY
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            //Special Holiday nextday
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND' => true,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                            'RHND_hours' => $regular_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);


                                                                        }
                                                                        elseif($data->RDRHND == true && $holiday->holiday_type == 'Special'){
                                                                             //  dd('ops');
                                                                             //REST DAY REGULAR HOLIDAY NIGHT SHIFT YESTERDAY
                                                                             $time_in = Carbon::parse($data->night_shift_date);
                                                                             $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                             $subtract = $time_in->diffInSeconds($holiday_date);
                                                                             $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);

                                                                             //Special Holiday nextday
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND' => true,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                            'RDRHND_hours' => $restday_regular_total_holiday_hours,
                                                                                        
                                                                                        ]);
                                                    
                                                                            return response()->json([
                                                    
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                            ]);


                                                                        }
                                                                        elseif($data->RDND == true && $holiday->holiday_type == 'Special'){
                                                                            // dd('4');
                                                                            //REST DAY NIGHT SHIFT
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                
                                                                                //REGULAR HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);
                                                
                                                                        
                                                
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND' => true,
                                                                                            'RDND_hours' => $restday_total_holiday_hours,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                        
                                                                                        ]);
                                                
                                                                            return response()->json([
                                                
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);
                                                
                                                
                                                                        }
                                                                        elseif($data->SHND == true && $holiday->holiday_type == 'Special'){
                                                                        
                                                                             //FOR THE SHND 
                                                                            //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);
                                                                        
                                                                        
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                        
                                                                                        ]);
                                                
                                                                            return response()->json([
                                                
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);
                                                                        }
                                                                        elseif($data->RDSHND == true && $holiday->holiday_type == 'Special'){
                                                                            // dd('6');
                                                                            //REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_special_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                
                                                                            //SPECIAL HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);
                                                
                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                                    ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                    ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                            'work_hours' => $totalDuration,
                                                                                            'undertime_hours' => $undertime,
                                                                                            'night_diff_hours' => $night_diff_total_hours,
                                                                                            'SHND' => true,
                                                                                            'RDSHND_hours' => $restday_special_total_holiday_hours,
                                                                                            'SHND_hours' => $special_holiday_hours,
                                                                                        
                                                                                        ]);
                                                
                                                                            return response()->json([
                                                
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);
                                                                        }
                                                                        elseif($data->SHND == true && $holiday->holiday_type == 'Regular'){

                                                                            // dd('1');
                                                                            //FOR THE SHND 
                                                                            //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again
                                                                         
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            //Regular HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                    'work_hours' => $totalDuration,
                                                                                    'undertime_hours' => $undertime,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'RHND' => true,
                                                                                    'SHND_hours' => $special_holiday_hours,
                                                                                    'RHND_hours' => $regular_holiday_hours,
                                                                                
                                                                                ]);
                                                                        
                                                                            return response()->json([
                                            
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);

                                                                        }
                                                                        else{
                                                                            
                                                                            // $data->RDSHND == true && $holiday->holiday_type == 'Regular'
                                                                            // dd('2');

                                                                             //FOR THE SHND 
                                                                            //ADD the previous holiday which is Special holiday to todays holiday which Special holiday again   
                                                                            $time_in = Carbon::parse($data->night_shift_date);
                                                                            $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $subtract = $time_in->diffInSeconds($holiday_date);
                                                                            $restday_special_holiday_hours = gmdate('H:i:s', $subtract);


                                                                            //Regular HOLIDAY
                                                                            $out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                            $start = Carbon::now('GMT+8')->setTime(24, 00, 0)->subDay(1)->format('Y-m-d H:i:s');
                                                                            $timeout = Carbon::parse($out);
                                                                            $subtract = $timeout->diffInSeconds($start);
                                                                            $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                            Attendance::where('emp_no', '=', $request -> scanned)
                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                            ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                    'work_hours' => $totalDuration,
                                                                                    'undertime_hours' => $undertime,
                                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                                    'RHND' => true,
                                                                                    'RDSHND_hours' => $restday_special_holiday_hours,
                                                                                    'RHND_hours' => $regular_holiday_hours,
                                                                                
                                                                                ]);
                                                                            return response()->json([
                                            
                                                                                'status' => 200,
                                                                                'msg' => 'Attendance Recorded Successfully',
                                                
                                                                            ]);
                                                                        }
                                                
                                                                    }   
                                                                    else{   
                                                                      
                                                                        
                                                                        #need to use switch case
                                                                        switch($data){
                                                                           
                                                                            //Rest day Night shift
                                                                            case $data -> RDND == true:
                                                                            
                                                                                //REST DAY NIGHT SHIFT
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $restday_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                        
                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                'work_hours' => $totalDuration,
                                                                                                'undertime_hours' => $undertime,
                                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                                'RDND_hours' => $restday_total_holiday_hours,
                                                                                            ]);
                                                        
                                                                                return response()->json([
                                                        
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                        
                                                                                ]);

                                                                                break;
                    
                                                                            //rest day special holiday Night shift
                                                                            case $data -> SHND == true:

                                                                                //Special Holiday
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $special_holiday_hours = gmdate('H:i:s', $subtract);

                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                        'work_hours' => $totalDuration,
                                                                                        'undertime_hours' => $undertime,
                                                                                        'night_diff_hours' => $night_diff_total_hours,
                                                                                        'SHND_hours' => $special_holiday_hours,
                                                                                    
                                                                                    ]);
                                                                            
                                                                                return response()->json([
                                                
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                                ]);

                                                                                 break;
                    
                                                                            //rest day regular holiday Night shift
                                                                            case $data -> RHND == true:

                                                                                //REGULAR HOLIDAY YESTERDAY
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $regular_holiday_hours = gmdate('H:i:s', $subtract);

                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                'work_hours' => $totalDuration,
                                                                                                'undertime_hours' => $undertime,
                                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                                'RHND_hours' => $regular_holiday_hours,
                                                                                            
                                                                                            ]);
                                                    
                                                                                return response()->json([
                                                        
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                        
                                                                                ]);
                                                                                
                                                                                 break;
                    
                                                                            //rest day special holiday Night shift
                                                                            case $data -> RDSHND == true:
                    
                                                                                //REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $restday_special_total_holiday_hours = gmdate('H:i:s', $subtract);
                                                    
                                                    
                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                'work_hours' => $totalDuration,
                                                                                                'undertime_hours' => $undertime,
                                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                                'RDSHND_hours' => $restday_special_total_holiday_hours,
                                                                                            
                                                                                            ]);
                                                    
                                                                                return response()->json([
                                                    
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                    
                                                                                ]);
                                                                                 break;
                                                                            
                                                                            //rest day regular holiday Night shift
                                                                            case $data -> RDRHND == true:
                                                                                
                                                                                //REST DAY REGULAR HOLIDAY NIGHT SHIFT YESTERDAY
                                                                                $time_in = Carbon::parse($data->night_shift_date);
                                                                                $holiday_date = Carbon::now('GMT+8')->setTime(23, 59, 59)->subDay(1)->format('Y-m-d H:i:s');
                                                                                $subtract = $time_in->diffInSeconds($holiday_date);
                                                                                $restday_regular_total_holiday_hours = gmdate('H:i:s', $subtract);

                                                                                Attendance::where('emp_no', '=', $request -> scanned)
                                                                                        ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                        ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                'work_hours' => $totalDuration,
                                                                                                'undertime_hours' => $undertime,
                                                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                                                'RDRHND_hours' => $restday_regular_total_holiday_hours,
                                                                                            
                                                                                            ]);
                                                        
                                                                                return response()->json([
                                                        
                                                                                    'status' => 200,
                                                                                    'msg' => 'Attendance Recorded Successfully',
                                                        
                                                                                ]);

                                                                               
                                                                                break;
                                                                            
                                                                           
                                                                    
                                                                            default:
                                                                               
                                                                              
                                                                                      //Update attendance if yesterday is not holiday 
                                                                                     Attendance::where('emp_no', '=', $request -> scanned)
                                                                                            ->where('date', '=', Carbon::now('GMT+8')->subDay(1)->format('Y-m-d'))
                                                                                            ->update(['time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                                    'work_hours' => $totalDuration,
                                                                                                    'undertime_hours' => $undertime,
                                                                                                    'night_diff_hours' => $night_diff_total_hours]);
                        
                                                                                    return response()->json([
                            
                                                                                        'status' => 200,
                                                                                        'msg' => 'Attendance Recorded Successfully',
                            
                                                                                    ]);
                        
                                                                             break;


                                                                        }

                                                                     
                                                                        
                                                                    }
                                                                   


                                                                }

        
                                                            }


                                                            
                                                        }

                                                    }
                                              
                                                }
                                            }
                                        }

                                    }

                                }
                                else if($data -> emp_no == $request -> scanned && $data -> date == Carbon::now('GMT+8')->format('Y-m-d') && $data -> time_out === null){

                           
                                    if( Carbon::now('GMT+8')->format('H:i:s') < $data->employee->sched_start){

                                        $sched_start =  Carbon::parse($data->employee->sched_start);
                                        $sched_end = Carbon::parse($data->employee->sched_end)->subHour(1)->addDay(1);
                                        $sum = $sched_start->diffInSeconds($sched_end);
                                        $undertime = gmdate('H:i:s', $sum);

                                        Attendance::where('emp_no', '=', $request->scanned)
                                            ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                            ->update([
                                                    'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                    'night_diff_hours' => '00:00:00',
                                                    'undertime_hours' => $undertime,
                                                    'work_hours' => '00:00:00',
                                                ]);
                                        return response()->json([
                                            'status' => 200,
                                            'msg' => 'Attendance updated Successfully',
                                        ]);
                                       
                                    }
                                    else{
                                       
                                 
                                        if ($data->overtime->isApproved_HR == '1') {

                                         
                                             // IF THE EMPLOYEE TIME OUT / UNDERTIME WITH THE SAME DATE
                                            $emp_sched_out = Carbon::parse($data->employee->sched_end)->subHour(1)->addDay(1);
                                            $out_today = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $sum = $emp_sched_out->diffInSeconds($out_today);
                                            $undertime = gmdate('H:i:s', $sum);

                                            // FOR THE WORK HOURS
                                            $endTime = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $startTime = Carbon::parse($data->night_shift_date);
                                            $interval = $startTime->diffInSeconds($endTime);
                                            $totalDuration = gmdate('H:i:s', $interval);

                                            
                                            if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){

                                                //dd('1');
                                                // NIGHT DIFFERENTIAL HOURS
                                                $start = Carbon::parse($data->night_shift_date);
                                                $end = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                $diff = $start->diffInSeconds($end);
                                                $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                            }
                                            else if(Carbon::parse($data -> night_shift_date)->format('H:i:s') < Carbon::parse('22:00:00')->format('H:i:s') && Carbon::now('GMT+8')->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){

                                                //dd('1');
                                                // NIGHT DIFFERENTIAL HOURS
                                                $start = Carbon::parse($data->night_shift_date);
                                                $end = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                $diff = $start->diffInSeconds($end);
                                                $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                            }
                                            else if(Carbon::now('GMT+8')->format('H:i:s') < Carbon::parse('22:00:00')->format('H:i:s')){

                                                //dd('1');
                                                // NIGHT DIFFERENTIAL HOURS 

                                                $start = Carbon::createFromFormat('H:i:s', '00:00:00');
                                                $end = Carbon::createFromFormat('H:i:s', '00:00:00');
                                                $diff = $start->diffInSeconds($end);
                                                $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                            }
                                            else
                                            {
                                                 // NIGHT DIFFERENTIAL HOURS
                                                $start = Carbon::createFromFormat('H:i:s', '22:00:00'); // 10pm
                                                $end = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                $diff = $start->diffInSeconds($end);
                                                $night_diff_total_hours = gmdate('H:i:s', $diff);
                                            }


                                            //FOR THE OVERTIME
                                            switch($data){

                                                //Rest day Night diff
                                                case $data -> RDND == true:

                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                            ->where('attendance_id', '=', $data -> id)
                                                            ->update(['hours_OT' => '00:00:00',
                                                                      'RDOT' => true]);
                                                break;

                                                //rest day special holiday Night diff
                                                case $data -> SHND == true:

                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                            ->where('attendance_id', '=', $data -> id)
                                                            ->update(['hours_OT' => '00:00:00',
                                                                      'SHOT' => true]);
                                                break;

                                                    //rest day regular holiday
                                                    case $data -> RHND == true:

                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                            ->where('attendance_id', '=', $data -> id)
                                                            ->update(['hours_OT' => '00:00:00',
                                                                      'RHOT' => true]);
                                                break;

                                                //rest day special holiday Night diff
                                                case $data -> RDSHND == true:

                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                            ->where('attendance_id', '=', $data -> id)
                                                            ->update(['hours_OT' => '00:00:00',
                                                                      'RDSHOT' => true]);
                                                break;
                                                
                                                //rest day regular holiday Night diff
                                                case $data -> RDRHND == true:

                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                            ->where('attendance_id', '=', $data -> id)
                                                            ->update(['hours_OT' => '00:00:00',
                                                                     'RDRHOT' => true]);
                                                break;
                                                
                                                default:
                                                     Overtime::where('emp_number', '=', $request -> scanned)
                                                            ->where('attendance_id', '=', $data -> id)
                                                            ->update(['hours_OT' => '00:00:00']);

                                                break;
                                            }


                                            //COMPUTE HOURS OF HOLIDAY IN NIGHT SHIFT BUT IF NOT HOLIDAY OR RESTDAY THEY WILL UPDATE IN THE DEFAULT OF SWITCH
                                            switch ($data) {

                                                #REST DAY NIGHT SHIFT
                                                case $data->RDND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'RDND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                   break;

                                                #SPECIAL HOLIDAY NIGHT SHIFT
                                                case $data->SHND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'SHND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                   break;
                                                
                                                #REGULAR HOLIDAY NIGHT SHIFT
                                                case $data->RHND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'RHND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                    break;

                                                
                                                #REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                case $data->RDSHND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'RDSHND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                    break;
                                                
                                                #REST DAY REGULAR HOLIDAY NIGHT SHIFT
                                                case $data->RDRHND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'RDRHND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                    break;

                                               
                                                #IN DEFAULT IT WILL UPDATE THE ATTENDANCE
                                                default:

                                                    
                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                            ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                            ->update([
                                                                    'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                    'undertime_hours' => $undertime,
                                                                    'work_hours' => $totalDuration
                                                                ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);


                                                break;
                                            }

                                            

                                        }
                                        elseif ($data->overtime->isApproved_HR == '0')
                                        {

                                        
                                            // IF THE EMPLOYEE TIME OUT / UNDERTIME WITH THE SAME DATE
                                            $emp_sched_out = Carbon::parse($data->employee->sched_end)->subHour(1)->addDay(1);
                                            $out_today = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $sum = $emp_sched_out->diffInSeconds($out_today);
                                            $undertime = gmdate('H:i:s', $sum);

                                            // FOR THE WORK HOURS
                                            $endTime = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $startTime = Carbon::parse($data->night_shift_date);
                                            $interval = $startTime->diffInSeconds($endTime);
                                            $totalDuration = gmdate('H:i:s', $interval);

                                            

                                            if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){

                                                //dd('1');
                                                // NIGHT DIFFERENTIAL HOURS
                                                $start = Carbon::parse($data->night_shift_date);
                                                $end = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                $diff = $start->diffInSeconds($end);
                                                $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                            }
                                            else if(Carbon::parse($data -> night_shift_date)->format('H:i:s') < Carbon::parse('22:00:00')->format('H:i:s') && Carbon::now('GMT+8')->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){

                                                //dd('1');
                                                // NIGHT DIFFERENTIAL HOURS
                                                $start = Carbon::parse($data->night_shift_date);
                                                $end = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                $diff = $start->diffInSeconds($end);
                                                $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                            }
                                            else if(Carbon::now('GMT+8')->format('H:i:s') < Carbon::parse('22:00:00')->format('H:i:s')){

                                                //dd('1');
                                                // NIGHT DIFFERENTIAL HOURS 

                                                $start = Carbon::createFromFormat('H:i:s', '00:00:00');
                                                $end = Carbon::createFromFormat('H:i:s', '00:00:00');
                                                $diff = $start->diffInSeconds($end);
                                                $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                            }
                                            else
                                            {


                                                // NIGHT DIFFERENTIAL HOURS
                                                $start = Carbon::createFromFormat('H:i:s', '22:00:00'); // 10pm
                                                $end = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                $diff = $start->diffInSeconds($end);
                                                $night_diff_total_hours = gmdate('H:i:s', $diff);
                                            }

                                           
                                            //THIS IS FOR THE OVERTIME
                                            switch($data){

                                                //Rest day Night diff
                                                case $data -> RDND == true:

                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                            ->where('attendance_id', '=', $data -> id)
                                                            ->update(['hours_OT' => '00:00:00',
                                                                      'RDOT' => true]);
                                                break;

                                                //rest day special holiday Night diff
                                                case $data -> SHND == true:

                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                            ->where('attendance_id', '=', $data -> id)
                                                            ->update(['hours_OT' => '00:00:00',
                                                                      'SHOT' => true]);
                                                break;

                                                //rest day regular holiday
                                                case $data -> RHND == true:

                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                            ->where('attendance_id', '=', $data -> id)
                                                            ->update(['hours_OT' => '00:00:00',
                                                                      'RHOT' => true]);
                                                break;

                                                //rest day special holiday Night diff
                                                case $data -> RDSHND == true:

                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                            ->where('attendance_id', '=', $data -> id)
                                                            ->update(['hours_OT' => '00:00:00',
                                                                      'RDSHOT' => true]);
                                                break;
                                                
                                                //rest day regular holiday Night diff
                                                case $data -> RDRHND == true:

                                                    Overtime::where('emp_number', '=', $request -> scanned)
                                                            ->where('attendance_id', '=', $data -> id)
                                                            ->update(['hours_OT' => '00:00:00',
                                                                     'RDRHOT' => true]);
                                                break;
                                                
                                                default:
                                                     Overtime::where('emp_number', '=', $request -> scanned)
                                                            ->where('attendance_id', '=', $data -> id)
                                                            ->update(['hours_OT' => '00:00:00']);

                                                break;
                                            }

                                            //COMPUTE HOURS OF HOLIDAY IN NIGHT SHIFT BUT IF NOT HOLIDAY OR RESTDAY THEY WILL UPDATE IN THE DEFAULT OF SWITCH
                                            switch ($data) {

                                                #REST DAY NIGHT SHIFT
                                                case $data->RDND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'RDND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                   break;

                                                #SPECIAL HOLIDAY NIGHT SHIFT
                                                case $data->SHND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'SHND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                   break;
                                                
                                                #REGULAR HOLIDAY NIGHT SHIFT
                                                case $data->RHND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'RHND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                    break;

                                                
                                                #REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                case $data->RDSHND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'RDSHND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                    break;
                                                
                                                #REST DAY REGULAR HOLIDAY NIGHT SHIFT
                                                case $data->RDRHND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'RDRHND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                    break;

                                               
                                                #IN DEFAULT IT WILL UPDATE THE ATTENDANCE
                                                default:

                                                    
                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                            ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                            ->update([
                                                                    'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                    'undertime_hours' => $undertime,
                                                                    'work_hours' => $totalDuration
                                                                ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);


                                                break;
                                            }

                                        }
                                        else
                                        {
                                            

                                            // IF THE EMPLOYEE TIME OUT / UNDERTIME WITH THE SAME DATE
                                            $emp_sched_out = Carbon::parse($data->employee->sched_end)->subHour(1)->addDay(1);
                                            $out_today = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $sum = $emp_sched_out->diffInSeconds($out_today);
                                            $undertime = gmdate('H:i:s', $sum);

                                            // FOR THE WORK HOURS
                                            $endTime = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $startTime = Carbon::parse($data->night_shift_date);
                                            $interval = $startTime->diffInSeconds($endTime);
                                            $totalDuration = gmdate('H:i:s', $interval);

                                            if(Carbon::parse($data -> night_shift_date)->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){

                                                //dd('1');
                                                // NIGHT DIFFERENTIAL HOURS
                                                $start = Carbon::parse($data->night_shift_date);
                                                $end = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                $diff = $start->diffInSeconds($end);
                                                $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                            }
                                            else if(Carbon::parse($data -> night_shift_date)->format('H:i:s') < Carbon::parse('22:00:00')->format('H:i:s') && Carbon::now('GMT+8')->format('H:i:s') > Carbon::parse('22:00:00')->format('H:i:s')){

                                                //dd('1');
                                                // NIGHT DIFFERENTIAL HOURS
                                                $start = Carbon::parse($data->night_shift_date);
                                                $end = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                $diff = $start->diffInSeconds($end);
                                                $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                            }
                                            else if(Carbon::now('GMT+8')->format('H:i:s') < Carbon::parse('22:00:00')->format('H:i:s')){

                                                //dd('1');
                                                // NIGHT DIFFERENTIAL HOURS 

                                                $start = Carbon::createFromFormat('H:i:s', '00:00:00');
                                                $end = Carbon::createFromFormat('H:i:s', '00:00:00');
                                                $diff = $start->diffInSeconds($end);
                                                $night_diff_total_hours = gmdate('H:i:s', $diff);   

                                            }
                                            else
                                            {

                                                // NIGHT DIFFERENTIAL HOURS
                                                $start = Carbon::createFromFormat('H:i:s', '22:00:00'); // 10pm
                                                $end = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                $diff = $start->diffInSeconds($end);
                                                $night_diff_total_hours = gmdate('H:i:s', $diff);
                                            }

                                            //COMPUTE HOURS OF HOLIDAY IN NIGHT SHIFT BUT IF NOT HOLIDAY OR RESTDAY 
                                            //THEY WILL UPDATE IN THE DEFAULT OF SWITCH
                                            switch ($data) {

                                                #REST DAY NIGHT SHIFT
                                                case $data->RDND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'RDND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                   break;

                                                #SPECIAL HOLIDAY NIGHT SHIFT
                                                case $data->SHND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'SHND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                   break;
                                                
                                                #REGULAR HOLIDAY NIGHT SHIFT
                                                case $data->RHND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'RHND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                    break;

                                                
                                                #REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                case $data->RDSHND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'RDSHND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                    break;
                                                
                                                #REST DAY REGULAR HOLIDAY NIGHT SHIFT
                                                case $data->RDRHND == true:
                                                    
                                                    $time_in = Carbon::parse($data->night_shift_date);
                                                    $time_out = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                    $subtract = $time_in->diffInSeconds($time_out);
                                                    $total_holiday_hours = gmdate('H:i:s', $subtract);

                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                          ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                          ->update([
                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                'night_diff_hours' => $night_diff_total_hours,
                                                                'undertime_hours' => $undertime,
                                                                'work_hours' => $totalDuration,
                                                                'RDRHND_hours' => $total_holiday_hours,
                                                            ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);

                                                    break;

                                               
                                                #IN DEFAULT IT WILL UPDATE THE ATTENDANCE
                                                default:

                                                    
                                                    Attendance::where('emp_no', '=', $request->scanned)
                                                            ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                            ->update([
                                                                    'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                    'night_diff_hours' => $night_diff_total_hours,
                                                                    'undertime_hours' => $undertime,
                                                                    'work_hours' => $totalDuration
                                                                ]);
                                                    return response()->json([
                                                        'status' => 200,
                                                        'msg' => 'Attendance updated Successfully',
                                                    ]);


                                                break;
                                            }
                                           
                                        }
                                       
                                    }
                                   
                                }
                                else
                                {
                                    $Check_Attendance = Attendance::with('overtime')->where('emp_no', $request -> scanned)
                                                                   ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                  ->first();

                                       
                                    //CHECK IF THE EMPLOYEE HAS ATTENDANCE IN TODAYS DATE
                                    if (!$Check_Attendance) {

                                       
                                        //IF THE EMPLOYEE COMPLETE TIME IN AND OUT 
                                        //ATTENDANCE AGAIN IN THE SAME DAY WHICH IS FOR NIGHT SHIFT ONLY
                                        if($data -> emp_no == $request -> scanned && $data -> date == Carbon::now('GMT+8')->subDay(1)->format('Y-m-d') && $data -> time_out != null){

                                            
                                            foreach($schedules as $sched){

                                                foreach ($holidays as $holiday) {
                                                    
                                                    $currentDate = Carbon::today('GMT+8')->format('m-d');
                                                    $holidayDate = Carbon::parse($holiday->holiday_date)->format('m-d');

                                                    if ($currentDate === $holidayDate && $holiday->holiday_type === 'Regular') {


                                                        //if rest day nila then  regular holiday pa then night shift
                                                        //RDRH
                                                        if( Carbon::now('GMT+8')->isSaturday() || Carbon::now('GMT+8')->isSunday()){

                                                            if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){

                                                                $emp_sched = Carbon::parse($sched -> sched_start);
                                                                $nightshift_date_less_than_sched_start = Carbon::now('GMT+8')->format('Y-m-d') . ' ' . $emp_sched->format('H:i:s');

                                                                $employee_attendance = new Attendance();
                                                                $employee_attendance -> emp_no = $request -> scanned;
                                                                $employee_attendance -> time_in = $sched -> sched_start;
                                                                $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                                $employee_attendance -> night_shift_date = $nightshift_date_less_than_sched_start;
                                                                $employee_attendance -> RDRHND = true;
                                                                $employee_attendance -> save();

                                                                return response()->json([

                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                                                ]);

                                                            }
                                                            else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){


                                                                $timein = Carbon::now('GMT+8')->format('H:i:s');
                                                                $sched_in = Carbon::parse($sched -> sched_start);
                                                                $interval = $sched_in->diffInSeconds($timein);
                                                                $total_late = gmdate('H:i:s', $interval);

                                                                $employee_attendance = new Attendance();
                                                                $employee_attendance -> emp_no = $request -> scanned;
                                                                $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                                                $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                                $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                $employee_attendance -> late_hours = $total_late;
                                                                $employee_attendance -> RDRHND = true;
                                                                $employee_attendance -> save();

                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                                                ]);
                                                            }
                                                            else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                                                $employee_attendance = new Attendance();
                                                                $employee_attendance -> emp_no = $request -> scanned;
                                                                $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                                                $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                                $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                $employee_attendance -> RDRHND = true;

                                                                $employee_attendance -> save();

                                                                return response()->json([

                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                                                ]);
                                                            }

                                                        }
                                                        else
                                                        {

                                                            if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){


                                                                
                                                                    $emp_sched = Carbon::parse($sched -> sched_start);
                                                                    $nightshift_date_less_than_sched_start = Carbon::now('GMT+8')->format('Y-m-d') . ' ' . $emp_sched->format('H:i:s');
                                                                    

                                                                    $employee_attendance = new Attendance();
                                                                    $employee_attendance -> emp_no = $request -> scanned;
                                                                    $employee_attendance -> time_in = $sched -> sched_start;
                                                                    $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                                    $employee_attendance -> night_shift_date = $nightshift_date_less_than_sched_start;
                                                                    $employee_attendance -> RHND = true;
                                                                    $employee_attendance -> save();
                                                                    return response()->json([

                                                                        'status' => 200,
                                                                        'msg' => 'Attendance Recorded Successfully',
                                                                    ]);
                                                            }
                                                            else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){


                                                                $timein = Carbon::now('GMT+8')->format('H:i:s');
                                                                $sched_in = Carbon::parse($sched -> sched_start);
                                                                $interval = $sched_in->diffInSeconds($timein);
                                                                $total_late = gmdate('H:i:s', $interval);


                                                                $employee_attendance = new Attendance();
                                                                $employee_attendance -> emp_no = $request -> scanned;
                                                                $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                                                $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                                $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                $employee_attendance -> late_hours = $total_late;
                                                                $employee_attendance -> RHND = true;
                                                                $employee_attendance -> save();

                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                                                ]);
                                                            }
                                                            else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                                                $employee_attendance = new Attendance();
                                                                $employee_attendance -> emp_no = $request -> scanned;
                                                                $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                                                $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                                $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                $employee_attendance -> RHND = true;
                                                                $employee_attendance -> save();

                                                                return response()->json([

                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                                                ]);
                                                            }

                                                        }


                                                    }

                                                    #Rest Day then Special holiday
                                                    else if ($currentDate === $holidayDate && $holiday->holiday_type === 'Special') {

                                                        # dd("regular special niyan");

                                                        if( Carbon::now('GMT+8')->isSaturday() || Carbon::now('GMT+8')->isSunday()){

                                                            if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){


                                                                // $datetimetoday = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                // $emp_sched = Carbon::parse($sched -> sched_start);
                                                                // $interval = $emp_sched->diffInSeconds($datetimetoday);
                                                                // $total = gmdate('H:i:s', $interval); //minus

                                                                // $time2 = Carbon::parse($total);
                                                                // $datetimenow = Carbon::now('GMT+8')->format('Y-m-d H:i:s'); //carbon now
                                                                // $convert = Carbon::parse($datetimenow);

                                                                // //add the subtrcated hour or minus to the current time and date
                                                                // $sum = $convert->add($time2->diffInHours('00:00:00'), 'hours')
                                                                //                 ->add($time2->diffInMinutes('00:00:00') % 60, 'minutes')
                                                                //                 ->add($time2->diffInSeconds('00:00:00') % 60, 'seconds');

                                                                // $sum->format('Y-m-d H:i:s');
                                                                $emp_sched = Carbon::parse($sched -> sched_start);
                                                                $nightshift_date_less_than_sched_start = Carbon::now('GMT+8')->format('Y-m-d') . ' ' . $emp_sched->format('H:i:s');


                                                                $employee_attendance = new Attendance();
                                                                $employee_attendance -> emp_no = $request -> scanned;
                                                                $employee_attendance -> time_in = $sched -> sched_start;
                                                                $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                                $employee_attendance -> night_shift_date = $nightshift_date_less_than_sched_start;
                                                                $employee_attendance -> RDSHND = true;
                                                                $employee_attendance -> save();



                                                                return response()->json([

                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                                                ]);
                                                            }
                                                            else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){


                                                                $timein = Carbon::now('GMT+8')->format('H:i:s');
                                                                $sched_in = Carbon::parse($sched -> sched_start);
                                                                $interval = $sched_in->diffInSeconds($timein);
                                                                $total_late = gmdate('H:i:s', $interval);

                                                                $employee_attendance = new Attendance();
                                                                $employee_attendance -> emp_no = $request -> scanned;
                                                                $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                                                $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                                $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                $employee_attendance -> late_hours = $total_late;
                                                                $employee_attendance -> RDSHND = true;
                                                                $employee_attendance -> save();

                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                                                ]);
                                                            }
                                                            else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                                                $employee_attendance = new Attendance();
                                                                $employee_attendance -> emp_no = $request -> scanned;
                                                                $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                                                $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                                $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                $employee_attendance -> RDSHND = true;

                                                                $employee_attendance -> save();

                                                                return response()->json([

                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                                                ]);
                                                            }

                                                        }
                                                        else
                                                        {

                                                            if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){


                                                                    // $datetimetoday = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                    // $emp_sched = Carbon::parse($sched -> sched_start);
                                                                    // $interval = $emp_sched->diffInSeconds($datetimetoday);
                                                                    // $total = gmdate('H:i:s', $interval); //minus

                                                                    // $time2 = Carbon::parse($total);
                                                                    // $datetimenow = Carbon::now('GMT+8')->format('Y-m-d H:i:s'); //carbon now
                                                                    // $convert = Carbon::parse($datetimenow);

                                                                    // //add the subtrcated hour or minus to the current time and date
                                                                    // $sum = $convert->add($time2->diffInHours('00:00:00'), 'hours')
                                                                    //                 ->add($time2->diffInMinutes('00:00:00') % 60, 'minutes')
                                                                    //                 ->add($time2->diffInSeconds('00:00:00') % 60, 'seconds');

                                                                    // $sum->format('Y-m-d H:i:s');
                                                                    $emp_sched = Carbon::parse($sched -> sched_start);
                                                                    $nightshift_date_less_than_sched_start = Carbon::now('GMT+8')->format('Y-m-d') . ' ' . $emp_sched->format('H:i:s');

                                                                    $employee_attendance = new Attendance();
                                                                    $employee_attendance -> emp_no = $request -> scanned;
                                                                    $employee_attendance -> time_in = $sched -> sched_start;
                                                                    $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                                    $employee_attendance -> night_shift_date = $nightshift_date_less_than_sched_start;
                                                                    $employee_attendance -> SHND = true;
                                                                    $employee_attendance -> save();

                                                                    return response()->json([

                                                                        'status' => 200,
                                                                        'msg' => 'Attendance Recorded Successfully',
                                                                    ]);
                                                            }
                                                            else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){


                                                                $timein = Carbon::now('GMT+8')->format('H:i:s');
                                                                $sched_in = Carbon::parse($sched -> sched_start);
                                                                $interval = $sched_in->diffInSeconds($timein);
                                                                $total_late = gmdate('H:i:s', $interval);

                                                                $employee_attendance = new Attendance();
                                                                $employee_attendance -> emp_no = $request -> scanned;
                                                                $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                                                $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                                $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                $employee_attendance -> late_hours = $total_late;
                                                                $employee_attendance -> SHND = true;
                                                                $employee_attendance -> save();

                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                                                ]);
                                                            }
                                                            else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                                                $employee_attendance = new Attendance();
                                                                $employee_attendance -> emp_no = $request -> scanned;
                                                                $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                                                $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                                $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                                $employee_attendance -> SHND = true;
                                                                $employee_attendance -> save();

                                                                return response()->json([

                                                                    'status' => 200,
                                                                    'msg' => 'Attendance Recorded Successfully',
                                                                ]);
                                                            }

                                                        }
                                                    }

                                                }


                                                #REST DAY
                                                if(Carbon::now('GMT+8')->isSaturday() || Carbon::now('GMT+8')->isSunday()){

                                                    if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){

                                                        $emp_sched = Carbon::parse($sched -> sched_start);
                                                        $nightshift_date_less_than_sched_start = Carbon::now('GMT+8')->format('Y-m-d') . ' ' . $emp_sched->format('H:i:s');

                                                        $employee_attendance = new Attendance();
                                                        $employee_attendance -> emp_no = $request -> scanned;
                                                        $employee_attendance -> time_in = $sched -> sched_start;
                                                        $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                        $employee_attendance -> night_shift_date = $nightshift_date_less_than_sched_start;
                                                        $employee_attendance -> RDND = true;
                                                        $employee_attendance -> save();

                                                        return response()->json([

                                                            'status' => 200,
                                                            'msg' => 'Attendance Recorded Successfully',
                                                        ]);
                                                    }
                                                    else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){


                                                        $timein = Carbon::now('GMT+8')->format('H:i:s');
                                                        $sched_in = Carbon::parse($sched -> sched_start);
                                                        $interval = $sched_in->diffInSeconds($timein);
                                                        $total_late = gmdate('H:i:s', $interval);

                                                        $employee_attendance = new Attendance();
                                                        $employee_attendance -> emp_no = $request -> scanned;
                                                        $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                                        $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                        $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                        $employee_attendance -> late_hours = $total_late;
                                                            $employee_attendance -> RDND = true;
                                                        $employee_attendance -> save();

                                                        return response()->json([
                                                            'status' => 200,
                                                            'msg' => 'Attendance Recorded Successfully',
                                                        ]);
                                                    }
                                                    else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                                        $employee_attendance = new Attendance();
                                                        $employee_attendance -> emp_no = $request -> scanned;
                                                        $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                                        $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                        $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                        $employee_attendance -> RDND = true;
                                                        $employee_attendance -> save();

                                                        return response()->json([

                                                            'status' => 200,
                                                            'msg' => 'Attendance Recorded Successfully',
                                                        ]);
                                                    }

                                                }
                                                else
                                                {

                                                    
                                                    if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){


                                                        $emp_sched = Carbon::parse($sched -> sched_start);
                                                        $nightshift_date_less_than_sched_start = Carbon::now('GMT+8')->format('Y-m-d') . ' ' . $emp_sched->format('H:i:s');

                                                        $employee_attendance = new Attendance();
                                                        $employee_attendance -> emp_no = $request -> scanned;
                                                        $employee_attendance -> time_in = $sched -> sched_start;
                                                        $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                        $employee_attendance -> night_shift_date = $nightshift_date_less_than_sched_start;
                                                        $employee_attendance -> save();

                                                        return response()->json([

                                                            'status' => 200,
                                                            'msg' => 'Attendance Recorded Successfully',
                                                        ]);
                                                    }
                                                    else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){


                                                        $timein = Carbon::now('GMT+8')->format('H:i:s');
                                                        $sched_in = Carbon::parse($sched -> sched_start);
                                                        $interval = $sched_in->diffInSeconds($timein);
                                                        $total_late = gmdate('H:i:s', $interval);

                                                        $employee_attendance = new Attendance();
                                                        $employee_attendance -> emp_no = $request -> scanned;
                                                        $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                                        $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                        $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                        $employee_attendance -> late_hours = $total_late;
                                                        $employee_attendance -> save();

                                                        return response()->json([
                                                            'status' => 200,
                                                            'msg' => 'Attendance Recorded Successfully',
                                                        ]);
                                                    }
                                                    else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                                        $employee_attendance = new Attendance();
                                                        $employee_attendance -> emp_no = $request -> scanned;
                                                        $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                                        $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                        $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');

                                                        $employee_attendance -> save();

                                                        return response()->json([

                                                            'status' => 200,
                                                            'msg' => 'Attendance Recorded Successfully',
                                                        ]);
                                                    }

                                                }      
                                                
                                            }

                                        }


                                    }
                                    else{

                                            $timeout_again = Attendance::where('emp_no', $request -> scanned)
                                                                        ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->where('time_in', '!=', Null)
                                                                        ->where('time_out', '!=', Null)
                                                                        ->first();

                                                                   
                                            if($timeout_again){

                                                //CHECK IF THE TIME IN AND OUT ARE COMPLETED FOR TODAYS DATE        

                                                return response()->json([
                                                    'status' => 0,
                                                    'msg' => 'Already Clocked Out',
                                                ]);
                                            }
                                            else{

                                                 //RECORD TIME OUT OF THE EMPLOYEE IN TODAYS DATE
                                                if (Carbon::now('GMT+8')->format('H:i:s') <= $data->employee->sched_start) {

                                                    // IF THE EMPLOYEE TIME OUT / UNDERTIME WITH THE SAME DATE
                                                    

                                                    $overtime_approved_HR = Overtime::where('emp_number', $request -> scanned)
                                                                                ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                                ->where('isApproved_HR', '1')
                                                                                ->first();

                                                    $overtime_Not_approved_HR = Overtime::where('emp_number', $request -> scanned)
                                                                                ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                                ->where('isApproved_HR', '0')
                                                                                ->first();
    
                                                    

                                                    if($overtime_approved_HR){

                                                        //dd('1');
                                                        $sched_start =  Carbon::parse($data->employee->sched_start);
                                                        $sched_end = Carbon::parse($data->employee->sched_end)->subHour(1)->addDay(1);
                                                        $sum = $sched_start->diffInSeconds($sched_end);
                                                        $undertime = gmdate('H:i:s', $sum);
                                                        
                                                        //FOR THE OVERTIME
                                                        switch($data){

                                                            //Rest day Night diff
                                                            case $data -> RDND == true:
            
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                         ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDOT' => true]);
                                                            break;
            
                                                            //rest day special holiday Night diff
                                                            case $data -> SHND == true:
            
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                           ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'SHOT' => true]);
                                                            break;
            
                                                                //rest day regular holiday
                                                                case $data -> RHND == true:
            
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                         ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RHOT' => true]);
                                                            break;
            
                                                            //rest day special holiday Night diff
                                                            case $data -> RDSHND == true:
            
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                         ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDSHOT' => true]);
                                                            break;
                                                            
                                                            //rest day regular holiday Night diff
                                                            case $data -> RDRHND == true:
            
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                 'RDRHOT' => true]);
                                                            break;
                                                            
                                                            default:
                                                          
                                                                 Overtime::where('emp_number', '=', $request -> scanned)
                                                                          ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                         ->update(['hours_OT' => '00:00:00']);
                                                                      
                                                            break;
                                                        }  
                                                        

                                                        //THEY WILL UPDATE IN THE DEFAULT OF SWITCH
                                                        switch ($data) {

                                                            #REST DAY NIGHT SHIFT
                                                            case $data->RDND == true:

                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'RDND_hours' => '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                            break;

                                                            #SPECIAL HOLIDAY NIGHT SHIFT
                                                            case $data->SHND == true:
                                                                
                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'SHND_hours' =>  '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                            break;
                                                            
                                                            #REGULAR HOLIDAY NIGHT SHIFT
                                                            case $data->RHND == true:
                                                                
                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'RHND_hours' => '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                                break;

                                                            
                                                            #REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                            case $data->RDSHND == true:

                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'RDSHND_hours' => '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                                break;
                                                            
                                                            #REST DAY REGULAR HOLIDAY NIGHT SHIFT
                                                            case $data->RDRHND == true:
                                                                
                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'RDRHND_hours' => '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                                break;

                                                        
                                                            #IN DEFAULT IT WILL UPDATE THE ATTENDANCE
                                                            default:

                                                                
                                                               Attendance::where('emp_no', '=', $request->scanned)
                                                                        ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update([
                                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                'night_diff_hours' => '00:00:00',
                                                                                'undertime_hours' => $undertime,
                                                                                'work_hours' => '00:00:00',
                                                                            ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);


                                                            break;
                                                        }

                                                    }
                                                    else if($overtime_Not_approved_HR){

                                                     
                                                        $sched_start =  Carbon::parse($data->employee->sched_start);
                                                        $sched_end = Carbon::parse($data->employee->sched_end)->subHour(1)->addDay(1);
                                                        $sum = $sched_start->diffInSeconds($sched_end);
                                                        $undertime = gmdate('H:i:s', $sum);
                                                        
                                                        //OVERTIME
                                                        switch($data){

                                                            //Rest day Night diff
                                                            case $data -> RDND == true:
            
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                         ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDOT' => true]);
                                                            break;
            
                                                            //rest day special holiday Night diff
                                                            case $data -> SHND == true:
            
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                           ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'SHOT' => true]);
                                                            break;
            
                                                                //rest day regular holiday
                                                                case $data -> RHND == true:
            
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                         ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RHOT' => true]);
                                                            break;
            
                                                            //rest day special holiday Night diff
                                                            case $data -> RDSHND == true:
            
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                         ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                  'RDSHOT' => true]);
                                                            break;
                                                            
                                                            //rest day regular holiday Night diff
                                                            case $data -> RDRHND == true:
            
                                                                Overtime::where('emp_number', '=', $request -> scanned)
                                                                        ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update(['hours_OT' => '00:00:00',
                                                                                 'RDRHOT' => true]);
                                                            break;
                                                            
                                                            default:
                                                          
                                                                 Overtime::where('emp_number', '=', $request -> scanned)
                                                                          ->whereDate('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                         ->update(['hours_OT' => '00:00:00']);
                                                                      
                                                            break;
                                                        }  
                                                     
                                                        //THEY WILL UPDATE IN THE DEFAULT OF SWITCH
                                                        switch ($data) {

                                                            #REST DAY NIGHT SHIFT
                                                            case $data->RDND == true:

                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'RDND_hours' => '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                            break;

                                                            #SPECIAL HOLIDAY NIGHT SHIFT
                                                            case $data->SHND == true:
                                                                
                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'SHND_hours' =>  '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                            break;
                                                            
                                                            #REGULAR HOLIDAY NIGHT SHIFT
                                                            case $data->RHND == true:
                                                                
                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'RHND_hours' => '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                                break;

                                                            
                                                            #REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                            case $data->RDSHND == true:

                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'RDSHND_hours' => '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                                break;
                                                            
                                                            #REST DAY REGULAR HOLIDAY NIGHT SHIFT
                                                            case $data->RDRHND == true:
                                                                
                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'RDRHND_hours' => '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                                break;

                                                        
                                                            #IN DEFAULT IT WILL UPDATE THE ATTENDANCE
                                                            default:

                                                                
                                                               Attendance::where('emp_no', '=', $request->scanned)
                                                                        ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update([
                                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                'night_diff_hours' => '00:00:00',
                                                                                'undertime_hours' => $undertime,
                                                                                'work_hours' => '00:00:00',
                                                                            ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);


                                                            break;
                                                        }

                                                    }
                                                    else{
                                                       
                                                      //  dd('e1se');
                                                        $sched_start =  Carbon::parse($data->employee->sched_start);
                                                        $sched_end = Carbon::parse($data->employee->sched_end)->subHour(1)->addDay(1);
                                                        $sum = $sched_start->diffInSeconds($sched_end);
                                                        $undertime = gmdate('H:i:s', $sum);
            
                                                        //THEY WILL UPDATE IN THE DEFAULT OF SWITCH
                                                        switch ($data) {

                                                            #REST DAY NIGHT SHIFT
                                                            case $data->RDND == true:

                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'RDND_hours' => '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                            break;

                                                            #SPECIAL HOLIDAY NIGHT SHIFT
                                                            case $data->SHND == true:
                                                                
                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'SHND_hours' =>  '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                            break;
                                                            
                                                            #REGULAR HOLIDAY NIGHT SHIFT
                                                            case $data->RHND == true:
                                                                
                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'RHND_hours' => '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                                break;

                                                            
                                                            #REST DAY SPECIAL HOLIDAY NIGHT SHIFT
                                                            case $data->RDSHND == true:

                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'RDSHND_hours' => '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                                break;
                                                            
                                                            #REST DAY REGULAR HOLIDAY NIGHT SHIFT
                                                            case $data->RDRHND == true:
                                                                
                                                                Attendance::where('emp_no', '=', $request->scanned)
                                                                    ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                    ->update([
                                                                            'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                            'night_diff_hours' => '00:00:00',
                                                                            'undertime_hours' => $undertime,
                                                                            'work_hours' => '00:00:00',
                                                                            'RDRHND_hours' => '00:00:00',
                                                                        ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);

                                                                break;

                                                        
                                                            #IN DEFAULT IT WILL UPDATE THE ATTENDANCE
                                                            default:
                                                               Attendance::where('emp_no', '=', $request->scanned)
                                                                        ->where('date', '=', Carbon::now('GMT+8')->format('Y-m-d'))
                                                                        ->update([
                                                                                'time_out' => Carbon::now('GMT+8')->format('H:i:s'),
                                                                                'night_diff_hours' => '00:00:00',
                                                                                'undertime_hours' => $undertime,
                                                                                'work_hours' => '00:00:00',
                                                                            ]);
                                                                return response()->json([
                                                                    'status' => 200,
                                                                    'msg' => 'Attendance updated Successfully',
                                                                ]);


                                                            break;
                                                        }
  
                                                    }
  
                                                }

                                                
                                            }

                                    }
     
                                }
               
                        }
                        
                        //FOR THE INSERT OF THE ATTENDANCE OF EMPLOYEE
                        foreach($schedules as $sched){

                            foreach ($holidays as $holiday) {
                               
                                $currentDate = Carbon::today('GMT+8')->format('m-d');
                                $holidayDate = Carbon::parse($holiday->holiday_date)->format('m-d');

                                if ($currentDate === $holidayDate && $holiday->holiday_type === 'Regular') {


                                    //if rest day nila then  regular holiday pa then night shift
                                    //RDRH
                                    if( Carbon::now('GMT+8')->isSaturday() || Carbon::now('GMT+8')->isSunday()){

                                        if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){

                                            $emp_sched = Carbon::parse($sched -> sched_start);
                                            $nightshift_date_less_than_sched_start = Carbon::now('GMT+8')->format('Y-m-d') . ' ' . $emp_sched->format('H:i:s');


                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = $sched -> sched_start;
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> night_shift_date = $nightshift_date_less_than_sched_start;
                                            $employee_attendance -> RDRHND = true;
                                            $employee_attendance -> save();

                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                            
                                        }
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){


                                            $timein = Carbon::now('GMT+8')->format('H:i:s');
                                            $sched_in = Carbon::parse($sched -> sched_start);
                                            $interval = $sched_in->diffInSeconds($timein);
                                            $total_late = gmdate('H:i:s', $interval);

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $employee_attendance -> late_hours = $total_late;
                                            $employee_attendance -> RDRHND = true;
                                            $employee_attendance -> save();

                                            return response()->json([
                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $employee_attendance -> RDRHND = true;

                                            $employee_attendance -> save();

                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }

                                    }
                                    else
                                    {

                                        if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){

                                            
                                                $emp_sched = Carbon::parse($sched -> sched_start);
                                                $nightshift_date_less_than_sched_start = Carbon::now('GMT+8')->format('Y-m-d') . ' ' . $emp_sched->format('H:i:s');
                                               

                                                $employee_attendance = new Attendance();
                                                $employee_attendance -> emp_no = $request -> scanned;
                                                $employee_attendance -> time_in = $sched -> sched_start;
                                                $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                $employee_attendance -> night_shift_date = $nightshift_date_less_than_sched_start;
                                                $employee_attendance -> RHND = true;
                                                $employee_attendance -> save();

                                                return response()->json([

                                                    'status' => 200,
                                                    'msg' => 'Attendance Recorded Successfully',
                                                ]);
                                        }
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){


                                            $timein = Carbon::now('GMT+8')->format('H:i:s');
                                            $sched_in = Carbon::parse($sched -> sched_start);
                                            $interval = $sched_in->diffInSeconds($timein);
                                            $total_late = gmdate('H:i:s', $interval);


                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $employee_attendance -> late_hours = $total_late;
                                            $employee_attendance -> RHND = true;
                                            $employee_attendance -> save();

                                            return response()->json([
                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $employee_attendance -> RHND = true;
                                            $employee_attendance -> save();

                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }

                                    }


                                }

                                #Rest Day then Special holiday
                                else if ($currentDate === $holidayDate && $holiday->holiday_type === 'Special') {

                                    # dd("regular special niyan");

                                      if( Carbon::now('GMT+8')->isSaturday() || Carbon::now('GMT+8')->isSunday()){

                                        if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){


                                            // $datetimetoday = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            // $emp_sched = Carbon::parse($sched -> sched_start);
                                            // $interval = $emp_sched->diffInSeconds($datetimetoday);
                                            // $total = gmdate('H:i:s', $interval); //minus

                                            // $time2 = Carbon::parse($total);
                                            // $datetimenow = Carbon::now('GMT+8')->format('Y-m-d H:i:s'); //carbon now
                                            // $convert = Carbon::parse($datetimenow);

                                            // //add the subtrcated hour or minus to the current time and date
                                            // $sum = $convert->add($time2->diffInHours('00:00:00'), 'hours')
                                            //                 ->add($time2->diffInMinutes('00:00:00') % 60, 'minutes')
                                            //                 ->add($time2->diffInSeconds('00:00:00') % 60, 'seconds');

                                            // $sum->format('Y-m-d H:i:s');


                                             $emp_sched = Carbon::parse($sched -> sched_start);
                                             $nightshift_date_less_than_sched_start = Carbon::now('GMT+8')->format('Y-m-d') . ' ' . $emp_sched->format('H:i:s');

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = $sched -> sched_start;
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> night_shift_date = $nightshift_date_less_than_sched_start;
                                            $employee_attendance -> RDSHND = true;
                                            $employee_attendance -> save();



                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){


                                            $timein = Carbon::now('GMT+8')->format('H:i:s');
                                            $sched_in = Carbon::parse($sched -> sched_start);
                                            $interval = $sched_in->diffInSeconds($timein);
                                            $total_late = gmdate('H:i:s', $interval);

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $employee_attendance -> late_hours = $total_late;
                                            $employee_attendance -> RDSHND = true;
                                            $employee_attendance -> save();

                                            return response()->json([
                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $employee_attendance -> RDSHND = true;

                                            $employee_attendance -> save();

                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }

                                    }
                                    else
                                    {

                                        if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){


                                                // $datetimetoday = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                                // $emp_sched = Carbon::parse($sched -> sched_start);
                                                // $interval = $emp_sched->diffInSeconds($datetimetoday);
                                                // $total = gmdate('H:i:s', $interval); //minus

                                                // $time2 = Carbon::parse($total);
                                                // $datetimenow = Carbon::now('GMT+8')->format('Y-m-d H:i:s'); //carbon now
                                                // $convert = Carbon::parse($datetimenow);

                                                // //add the subtrcated hour or minus to the current time and date
                                                // $sum = $convert->add($time2->diffInHours('00:00:00'), 'hours')
                                                //                 ->add($time2->diffInMinutes('00:00:00') % 60, 'minutes')
                                                //                 ->add($time2->diffInSeconds('00:00:00') % 60, 'seconds');

                                                // $sum->format('Y-m-d H:i:s');
                                                $emp_sched = Carbon::parse($sched -> sched_start);
                                                $nightshift_date_less_than_sched_start = Carbon::now('GMT+8')->format('Y-m-d') . ' ' . $emp_sched->format('H:i:s');


                                                $employee_attendance = new Attendance();
                                                $employee_attendance -> emp_no = $request -> scanned;
                                                $employee_attendance -> time_in = $sched -> sched_start;
                                                $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                                $employee_attendance -> night_shift_date = $nightshift_date_less_than_sched_start;
                                                $employee_attendance -> SHND = true;
                                                $employee_attendance -> save();

                                                return response()->json([

                                                    'status' => 200,
                                                    'msg' => 'Attendance Recorded Successfully',
                                                ]);
                                        }
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){


                                            $timein = Carbon::now('GMT+8')->format('H:i:s');
                                            $sched_in = Carbon::parse($sched -> sched_start);
                                            $interval = $sched_in->diffInSeconds($timein);
                                            $total_late = gmdate('H:i:s', $interval);

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $employee_attendance -> late_hours = $total_late;
                                            $employee_attendance -> SHND = true;
                                            $employee_attendance -> save();

                                            return response()->json([
                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }
                                        else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                            $employee_attendance = new Attendance();
                                            $employee_attendance -> emp_no = $request -> scanned;
                                            $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                            $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                            $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                            $employee_attendance -> SHND = true;
                                            $employee_attendance -> save();

                                            return response()->json([

                                                'status' => 200,
                                                'msg' => 'Attendance Recorded Successfully',
                                            ]);
                                        }

                                    }
                                }

                            }


                            #REST DAY
                            if(Carbon::now('GMT+8')->isSaturday() || Carbon::now('GMT+8')->isSunday()){

                                if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){

                                    $emp_sched = Carbon::parse($sched -> sched_start);
                                    $nightshift_date_less_than_sched_start = Carbon::now('GMT+8')->format('Y-m-d') . ' ' . $emp_sched->format('H:i:s');


                                    $employee_attendance = new Attendance();
                                    $employee_attendance -> emp_no = $request -> scanned;
                                    $employee_attendance -> time_in = $sched -> sched_start;
                                    $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                    $employee_attendance -> night_shift_date = $nightshift_date_less_than_sched_start;
                                    $employee_attendance -> RDND = true;
                                    $employee_attendance -> save();

                                    return response()->json([

                                        'status' => 200,
                                        'msg' => 'Attendance Recorded Successfully',
                                    ]);
                                }
                                else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){


                                    $timein = Carbon::now('GMT+8')->format('H:i:s');
                                    $sched_in = Carbon::parse($sched -> sched_start);
                                    $interval = $sched_in->diffInSeconds($timein);
                                    $total_late = gmdate('H:i:s', $interval);

                                    $employee_attendance = new Attendance();
                                    $employee_attendance -> emp_no = $request -> scanned;
                                    $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                    $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                    $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                    $employee_attendance -> late_hours = $total_late;
                                     $employee_attendance -> RDND = true;
                                    $employee_attendance -> save();

                                    return response()->json([
                                        'status' => 200,
                                        'msg' => 'Attendance Recorded Successfully',
                                    ]);
                                }
                                else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                    $employee_attendance = new Attendance();
                                    $employee_attendance -> emp_no = $request -> scanned;
                                    $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                    $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                    $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                    $employee_attendance -> RDND = true;
                                    $employee_attendance -> save();

                                    return response()->json([

                                        'status' => 200,
                                        'msg' => 'Attendance Recorded Successfully',
                                    ]);
                                }

                            }
                            else
                            {

                                
                                if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') < $sched -> sched_start){

                                  

                                    $emp_sched = Carbon::parse($sched -> sched_start);
                                    $nightshift_date_less_than_sched_start = Carbon::now('GMT+8')->format('Y-m-d') . ' ' . $emp_sched->format('H:i:s');

                                    $employee_attendance = new Attendance();
                                    $employee_attendance -> emp_no = $request -> scanned;
                                    $employee_attendance -> time_in = $sched -> sched_start;
                                    $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                    $employee_attendance -> night_shift_date = $nightshift_date_less_than_sched_start;

                                    // if(Carbon::now('GMT+8')->isSaturday() || Carbon::now('GMT+8')->isSunday()){

                                    //    $employee_attendance -> RDND = true;
                                    // }

                                    $employee_attendance -> save();



                                    return response()->json([

                                        'status' => 200,
                                        'msg' => 'Attendance Recorded Successfully',
                                    ]);
                                }
                                else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') > $sched -> sched_start){


                                    $timein = Carbon::now('GMT+8')->format('H:i:s');
                                    $sched_in = Carbon::parse($sched -> sched_start);
                                    $interval = $sched_in->diffInSeconds($timein);
                                    $total_late = gmdate('H:i:s', $interval);

                                 
                                    $employee_attendance = new Attendance();
                                    $employee_attendance -> emp_no = $request -> scanned;
                                    $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                    $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                    $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');
                                    $employee_attendance -> late_hours = $total_late;

                                    // if(Carbon::now('GMT+8')->isSaturday() || Carbon::now('GMT+8')->isSunday()){

                                    //    $employee_attendance -> RDND = true;
                                    // }

                                    $employee_attendance -> save();

                                    return response()->json([
                                        'status' => 200,
                                        'msg' => 'Attendance Recorded Successfully',
                                    ]);
                                }
                                else if($request -> scanned == $sched -> employee_no && Carbon::now('GMT+8')->format('H:i') == $sched -> sched_start){

                                    $employee_attendance = new Attendance();
                                    $employee_attendance -> emp_no = $request -> scanned;
                                    $employee_attendance -> time_in = Carbon::now('GMT+8')->format('H:i:s');
                                    $employee_attendance -> date = Carbon::now('GMT+8')->format('Y-m-d');
                                    $employee_attendance -> night_shift_date = Carbon::now('GMT+8')->format('Y-m-d H:i:s');

                                    $employee_attendance -> save();

                                    return response()->json([

                                        'status' => 200,
                                        'msg' => 'Attendance Recorded Successfully',
                                    ]);
                                }

                            }      
                         
                        }

                    }
                    

                }
            }

        }



    //if the icon edit click the value of that id will pop up
    public function edit_mod(Request $request) {
		$id = $request->id;
	    $empz = Attendance::with('employee')->find($id);
		return response()->json($empz);
	}

    // handle update an employee ajax request
	public function update_mod(Request $request)
    {
        //To validate the employee
        $validator = \Validator::make($request -> all(),[
            'time_in' => 'required',
            'time_out' => 'required',
        ]);

        if($validator -> fails())
        {
            // if the validator fails
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }
        else
        {   //modify the time in and out
            $upt_mod = Attendance::find($request->attt_id);
            $upt_mod -> time_in = $request -> time_in;
            $upt_mod -> time_out = $request -> time_out;
            $upt_mod -> update();

            return response()->json([

                'status' => 200,
                'msg' => 'Modify Successfully',
		    ]);
        }
	}

    public function absent_onleave(Request $request){

        $validator = \Validator::make($request -> all(),[

            'employee_number' => 'required|exists:employees,employee_no',
            'status' => 'required',

        ]);

        if($validator -> fails())
        {
            // if the validator fails
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }
        else{


            $employee_absent_onleave = new Attendance();

            if($request -> RH === '1'){

                 $employee_absent_onleave -> RH = $request -> RH;

            }
            else if($request -> SH === '0'){

                 $employee_absent_onleave -> SH = $request -> SH;

            }
            else{

                $employee_absent_onleave -> emp_no = $request -> employee_number;
                $employee_absent_onleave -> status = $request -> status;
                $employee_absent_onleave -> date = Carbon::now('GMT+8')->format('Y-m-d');
                $employee_absent_onleave -> save();

              //  dd($employee_absent_onleave);
                return response()->json([

                    'status' => 200,
                    'msg' => 'Attendance Recorded Successfully',
                ]);

            }

            $employee_absent_onleave -> emp_no = $request -> employee_number;
            $employee_absent_onleave -> status = $request -> status;
            $employee_absent_onleave -> date = Carbon::now('GMT+8')->format('Y-m-d');
            $employee_absent_onleave -> save();

           // dd($employee_absent_onleave);
            return response()->json([

                'status' => 200,
                'msg' => 'Attendance Recorded Successfully',
            ]);
        }
    }

    public function absent_onleave_attendance(){
        
        $absent_onleave_data = Attendance::with('employee')->where('status', 'absent')->orWhere('status', 'onleave')->get();
        
        $output = '';

		if ($absent_onleave_data ->count() > 0) {

			$output .= '<table class="tracking-tbl table" style="width: 100%" id="onleave_absent">
            <thead>
              <tr>
                <th hidden> </th>
              </tr>
            </thead>
            <tbody>';
			foreach ($absent_onleave_data as $data) {

                $output .=
                ' <tr>
                    <td>
                        <div class="card time-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-evenly">
                                    <div class="col-xl-2 d-flex">
                                        <div class="d-flex ">
                                            <p class="" hidden >'.$data -> updated_at.'</p>
                                            <p class="" hidden>'.$data -> id.'</p>';

                                            if($data -> employee -> image != null){

                                                $output .=  '<img src="storage/employee/images/' . $data -> employee -> image . '" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:50px; height: 50px;">';

                                            }
                                            else{

                                                $output .= '<i class="bx bx-user icon profile-picture-time"> </i>';
                                            }

                                        $output .= '  </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <h5 class="emp-name">'.$data -> employee -> employee_name.'</h5>
                                        <p class="emp-no">'.$data->emp_no.'</p>
                                    </div>
                                    <div class="col-xl-3">
                                        <h5 class="emp-name">'.Carbon::parse($data -> date)->format('M d Y').'</h5>
                                        <p class="emp-no"> Date</p>
                                    </div>
                                    <div class="col-xl-2 td-div">';
                                        $output .=  '<h5 class="time">'.$data->status.'</h5>';
                                        $output .= '<p class="type">Status</p>
                                       </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>';

			}
			$output .= '</tbody></table>';
			echo $output;

		}
         else {
			echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
		}
    }
}






