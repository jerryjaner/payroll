<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\LeaveReq;
use App\Models\Overtime;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    public function overtimeRequest(){

        return view('attendance.overtime');
    }

    public function employee_all_attendance(){

        $employee_all_attendances = Attendance::with('employee','overtime')->get();
                        

        $Employee_time_in_AM = "08:00:00";
        $Employee_time_out_PM = "17:00:00";

        $output = '';
        if ($employee_all_attendances->count() > 0) {
    
	 		$output .= '<table class="atendance-tbl table" style="width: 100%" id="attendance_table">
            <thead>
            <tr>
                <th hidden> </th>
            </tr>
            </thead>
            <tbody>';
            foreach ($employee_all_attendances as $employee_all_attendance) {

                if($employee_all_attendance -> employee -> user_id == Auth::User()->id){

                    $output .=' <tr>
                                    <td>
                                        <div class="card time-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-xl-1">
                                                        <div class="d-flex">
                                                        <p class="attendance_id" hidden>'.$employee_all_attendance -> id.'</p>';
                                                        
                                                        if($employee_all_attendance -> employee -> image != null){

                                                            $output .=  '<img src="storage/employee/images/' . $employee_all_attendance -> employee -> image . '" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:50px; height: 50px;">';

                                                        }
                                                        else{

                                                            $output .= '<i class="bx bx-user icon profile-picture-time"> </i>';
                                                        }
                                                    
                                                        $output .= '  </div>
                                                    </div>
                                                    <div class="col-xl-2">
                                                        <h5 class="emp-name">'.$employee_all_attendance -> employee -> employee_name.'</h5>
                                                        <p class="emp-no">'.$employee_all_attendance->emp_no.'</p>
                                                        
                                                    </div>
                                                    <div class="col-xl-2">
                                                        <h5 class="emp-name">'.Carbon::parse($employee_all_attendance -> date)->toFormattedDateString().'</h5>
                                                        <p class="emp-no"> Date</p>
                                                    </div>
                                                    <div class="col-xl-1 td-div">';
                                                            if($employee_all_attendance -> status == "absent"){
                                                                $output .=  '<h5 class="time">00:00</h5>
                                                                            <p class="type">Time In</p>';
                                                            }
                                                            else if($employee_all_attendance -> status == "onleave"){
                                                                $output .=  '<h5 class="time">00:00:00</h5>
                                                                            <p class="type">>Time In</p>';
                                                            }else{
                                                                $output .=  '<h5 class="time">'.Carbon::parse($employee_all_attendance->time_in)->format('H:i').'</h5>';
                                                                $output .= '<p class="type">Time In</p>';
                                                                
                                                            }
                                                    $output .= '</div>';

                                                    $output .='<div class="col-xl-1 td-div">';

                                                        if($employee_all_attendance -> status == "absent"){
                                                            $output .=  '<h5 class="time">00:00</h5>
                                                                        <p class="type">Time Out</p>';
                                                        }
                                                        else if($employee_all_attendance -> status == "onleave"){
                                                            $output .=  '<h5 class="time">00:00:00</h5>
                                                                        <p class="type">>Time Out</p>';
                                                        }else{
                                                            if($employee_all_attendance->time_out == null){
                                    
                                                                $output .=  '<h5 class="time">--:--</h5>';
                                                                $output .= '<p class="type">Time Out</p>';
                                                            }
                                                            else
                                                            {
                                                                $output .=  '<h5 class="time" >'.Carbon::parse($employee_all_attendance->time_out)->format('H:i').'</h5>';
                                                                $output .= '<p class="type">Time Out</p>';
                                                            }
                                                        }
                                                                
                                                    $output .='</div>';

                                                        if($employee_all_attendance -> status == "absent"){
                                                            $output .=  '<div class="col-xl-1">
                                                                            <h5 class="time">00:00</h5>
                                                                            <p class="type">Total Overtime</p>
                                                                        </div>';
                                                        }
                                                        else if($employee_all_attendance -> status == "onleave"){
                                                            $output .=  '<div class="col-xl-1">
                                                                            <h5 class="time">00:00</h5>
                                                                            <p class="type">Total Overtime</p>
                                                                        </div>';
                                                        }
                                                        else{
                                                            if($employee_all_attendance -> overtime -> hours_OT != null){

                                                                $output.='<div class="col-xl-1">
                                                                            <h5 class="emp-name">'.$employee_all_attendance -> overtime -> hours_OT.' Hour</h5>
                                                                            <p class="emp-no"> Total Overtime</p>
                                                                        </div>';
                                                            } 
                                                            else{

                                                                $output.='<div class="col-xl-1">
                                                                            <h5 class="emp-name">--:--</h5>
                                                                            <p class="emp-no"> Total Overtime</p>
                                                                        </div>';
                                                            }
                                                        }

                                                    $output .='<div class="col-xl-4 d-flex justify-content-end align-items-center gap-2">';

                                                        if($employee_all_attendance -> overtime -> isDecline_HR == '1'){
                                                            $output .=' <span class="status late d-flex align-items-center">                                       
                                                                            <i class="bx bx-x"></i>
                                                                            Declined request
                                                                        </span>';
                                                        }
                                                        else if($employee_all_attendance -> overtime -> isApproved_HR == '1'){           
                                                            $output .=' <span class="status on-time d-flex align-items-center">                                       
                                                                            <i class="bx bx-check"></i>
                                                                            Approve Overtime
                                                                        </span>';
                                                        }
                                                        else{
                                                            if($employee_all_attendance -> overtime -> isApproved_HR == '0'){
                                                                $output .=' <span class="status late d-flex align-items-center">                                       
                                                                                <i class="bx bx-revision"></i>
                                                                                On process request
                                                                            </span>';
                                                            }else{

                                                                if($employee_all_attendance -> status == "absent"){
                                                                    $output .=' <span class="status bg-warning d-flex align-items-center">                                       
                                                                                    Absent
                                                                                </span>';
                                                                }
                                                                else if($employee_all_attendance -> status == "onleave"){
                                                                    $output .=' <span class="status bg-warning d-flex align-items-center">                                       
                                                                                    OnLeave
                                                                                </span>';
                                                                }
                                                                else{
                                                                    if($employee_all_attendance->time_out != null){
                                                                    
                                                                        $output .=' <span class="status on-time d-flex align-items-center">                                       
                                                                                        <i class="bx bx-time"></i>
                                                                                        You already Clocked Out
                                                                                    </span>';
                                                                    }
                                                                    else{

                                                                        if($employee_all_attendance -> employee -> monthly_rate == "Fixed Rate"){

                                                                            $output .=' <span class="status bg-warning d-flex align-items-center">  
                                                                                        <i class="bx bx-error-circle"></i>                                     
                                                                                            Only Daily Rate Can Request Overtime
                                                                                        </span>';
                                                                        }
                                                                        else{
                                                                            $output .=' <a href="#" id="' . $employee_all_attendance -> id . '" type="button" class="btn btn-warning btn-sm mx-1 request_ot" data-bs-toggle="modal" data-bs-target="#request_overtime">
                                                                                            Request OT
                                                                                        </a>';
                                                                        }
                                                                    
                                                                    }
                                                                }
                                                            
                                                                
                                                            }
                                                        }                                                       
                                                    $output .='</div>
                            
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>';
                   
                }
                
               
            }
            $output .= '</tbody></table>';
            echo $output;

        } 
        else 
        {
            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }      
    }

    public function overtime_request(Request $request) {
		$id = $request->id;
		$attendance = Attendance::with('employee')->find($id);
		return response()->json($attendance);
	}

    public function submit_overtime_request(Request $request){

        $validator = \Validator::make($request -> all(),[
   
            'reason'      => 'required',
            'date'          => 'required|date',
 
        ]);

        if($validator -> fails()){

            // if the validator fails
            return response()->json([
                'code' => 0, 
                'error' => $validator->errors()->toArray()
            ]);
        }
        else{

            Overtime::create([
                
                'attendance_id'     => $request->atten_id,
                'emp_number'        => $request->emp_no,
                'reason'            => $request->reason,
                'date'              => $request->date,

            ]);

            //Attendance::where('emp_no', '=', $request -> emp_no)->where('date', '=', $request -> date)->update(['overtime_approval' => 'On Process']);  

            return response()->json([
                'code' => 200, 
                'msg' => 'OT Request Added Successfully',
            ]);

        }
    }


    public function all_overtime_request(){
      
       // $all_overtime_requests = Attendance::with('employee','overtime')->get();
        $all_overtime_requests = Overtime::with('employee','attendance')->get();
                    
        $output = '';
        if ($all_overtime_requests -> count() > 0) {
    
	 		$output .= '<table class="atendance-tbl table " style="width: 100%" id="overtime_table">
            <thead>
            <tr>
                <th hidden> </th>
            </tr>
            </thead>
            <tbody>';
            foreach ($all_overtime_requests as $all_overtime_request) {

                if(Auth::user()->hasRole(['HR','assistantHR'])){

                    $output .= '<tr>
                                    <td>
                                        <div class="card time-card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-xl-1">
                                                        <div class="d-flex">
                
                                                        <p class="attendance_id" hidden>'.$all_overtime_request -> id.'</p>';
                                                        
                                                        if($all_overtime_request -> employee -> image != null){
                
                                                            $output .=  '<img src="storage/employee/images/' . $all_overtime_request -> employee -> image . '" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:50px; height: 50px;">';
                
                                                        }
                                                        else{
                
                                                            $output .= '<i class="bx bx-user icon profile-picture-time"> </i>';
                                                        }
                                                    
                                                    $output .= '  </div>
                                                    </div>
                                                    <div class="col-xl-2">
                                                        <h5 class="emp-name">'.$all_overtime_request -> employee -> employee_name.'</h5>
                                                        <p class="emp-no">'.$all_overtime_request->emp_no.'</p>
                                                        
                                                    </div>
                                                    <div class="col-xl-1">
                                                            <h5 class="emp-name">'.$all_overtime_request -> employee -> employee_department.'</h5>
                                                            <p class="emp-no">Department</p>
                                                    
                                                    </div>
                                                    <div class="col-xl-1">
                                                        <h5 class="emp-name">'.Carbon::parse($all_overtime_request -> date)->toFormattedDateString().'</h5>
                                                        <p class="emp-no"> Date</p>
                                                    </div>
                
                                                    <div class="col-xl-1 td-div">';
                                                    
                                                        $output .=  '<h5 class="time">'.Carbon::parse($all_overtime_request -> attendance ->time_in)->format('H:i').'</h5>';
                                                        $output .= '<p class="type">Time In</p>
                                                    </div>';
                
                                                    $output .='<div class="col-xl-1 td-div">';
                                                        if($all_overtime_request-> attendance ->time_out == null){
                                                            $output .=  '<h5 class="time">--:--</h5>';
                                                            $output .= '<p class="type">Time Out</p>';
                                                        }
                                                        else
                                                        {
                                                            $output .=  '<h5 class="time" >'.Carbon::parse($all_overtime_request -> attendance ->time_out)->format('H:i').'</h5>';
                                                            $output .= '<p class="type">Time Out</p>';
                                                        }                                                           
                                                    $output .='</div>';
                                                        if($all_overtime_request -> hours_OT != null){
                                                            $output.='<div class="col-xl-1">
                                                                        <h5 class="emp-name">'.$all_overtime_request ->hours_OT.'</h5>
                                                                        <p class="emp-no"> Total Overtime</p>
                                                                    </div>';
                                                        } 
                                                        else{
                                                            $output.='<div class="col-xl-1">
                                                                        <h5 class="emp-name">--:--</h5>
                                                                        <p class="emp-no"> Total Overtime</p>
                                                                    </div>';
                                                        }
                                                            $output .=' <div class="col-xl-2">
                                                                            <h6 class="emp-name">'.$all_overtime_request -> reason.'</h6>
                                                                            <p class="emp-no"> Reason</p>
                                                                        </div>';
                                                    $output .='<div class="col-xl-2 d-flex justify-content-end align-items-center gap-2">';
                                                        if($all_overtime_request  -> isApproved_HR == '1'){
                                                            $output .=' <span class="status on-time d-flex align-items-center">                                       
                                                                            <i class="bx bx-check"></i>
                                                                                Approve request
                                                                        </span>';
                                                        }else if($all_overtime_request -> isDecline_HR == '1'){
                                                            $output .=' <span class="status late d-flex align-items-center">                                       
                                                                            <i class="bx bx-x"></i>
                                                                            Declined request
                                                                        </span>';
                                                        }else{
                                                            $output .='<a href="#" id="' . $all_overtime_request -> id . '" type="button" class="btn btn-danger btn-sm mx-1 hr_decline" data-bs-toggle="modal" data-bs-target="#decline_ot_HR">
                                                                            Decline Request
                                                                        </a>
                                                                        <a href="#" id="' . $all_overtime_request -> id . '" type="button" class="btn btn-success btn-sm mx-1 approve_hr" data-bs-toggle="modal" data-bs-target="#approve_ot_HR">
                                                                            HR Approval
                                                                        </a> ';
                                                        }    
                                                    $output .='</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>';
                }
                else if(Auth::user()->hasRole(['administrator','SVPT'])){
                    if($all_overtime_request -> employee -> employee_department == 'IT'){
                        $output .= '<tr>
                                        <td>
                                            <div class="card time-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-xl-1">
                                                            <div class="d-flex">
                    
                                                            <p class="attendance_id" hidden>'.$all_overtime_request -> id.'</p>';
                                                            
                                                            if($all_overtime_request -> employee -> image != null){
                    
                                                                $output .=  '<img src="storage/employee/images/' . $all_overtime_request -> employee -> image . '" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:50px; height: 50px;">';
                    
                                                            }
                                                            else{
                    
                                                                $output .= '<i class="bx bx-user icon profile-picture-time"> </i>';
                                                            }
                                                        
                                                        $output .= '  </div>
                                                        </div>
                                                        <div class="col-xl-2">
                                                            <h5 class="emp-name">'.$all_overtime_request -> employee -> employee_name.'</h5>
                                                            <p class="emp-no">'.$all_overtime_request->emp_no.'</p>
                                                            
                                                        </div>
                                                        <div class="col-xl-1">
                                                                <h5 class="emp-name">'.$all_overtime_request -> employee -> employee_department.'</h5>
                                                                <p class="emp-no">Department</p>
                                                        
                                                        </div>
                                                        <div class="col-xl-1">
                                                            <h5 class="emp-name">'.Carbon::parse($all_overtime_request -> date)->toFormattedDateString().'</h5>
                                                            <p class="emp-no"> Date</p>
                                                        </div>
                    
                                                        <div class="col-xl-1 td-div">';
                                                        
                                                            $output .=  '<h5 class="time">'.Carbon::parse($all_overtime_request -> attendance ->time_in)->format('H:i').'</h5>';
                                                            $output .= '<p class="type">Time In</p>
                                                        </div>';
                    
                                                        $output .='<div class="col-xl-1 td-div">';

                                                            if($all_overtime_request-> attendance ->time_out == null){
                        
                                                                $output .=  '<h5 class="time">--:--</h5>';
                                                                $output .= '<p class="type">Time Out</p>';
                                                            }
                                                            else
                                                            {
                                                                $output .=  '<h5 class="time" >'.Carbon::parse($all_overtime_request -> attendance ->time_out)->format('H:i').'</h5>';
                                                                $output .= '<p class="type">Time Out</p>';
                                                            }
                                                                
                                                                
                                                        $output .='</div>';
                                                                    
                                                            if($all_overtime_request -> hours_OT != null){
                                                                $output.='<div class="col-xl-1">
                                                                            <h5 class="emp-name">'.$all_overtime_request ->hours_OT.'</h5>
                                                                            <p class="emp-no"> Total Overtime</p>
                                                                        </div>';
                                                            } 
                                                            else{

                                                                $output.='<div class="col-xl-1">
                                                                            <h5 class="emp-name">--:--</h5>
                                                                            <p class="emp-no"> Total Overtime</p>
                                                                        </div>';
                                                            }
                                                        
                                                                $output.=' <div class="col-xl-2">
                                                                                <h6 class="emp-name">'.$all_overtime_request -> reason.'</h6>
                                                                                <p class="emp-no"> Reason</p>
                                                                            </div>';

                                                        $output .='<div class="col-xl-2 d-flex justify-content-end align-items-center gap-2">';
                                                        
                                                            if($all_overtime_request  -> isApproved_SVP == '1'){

                                                                $output .=' <span class="status on-time d-flex align-items-center">                                       
                                                                                <i class="bx bx-check"></i>
                                                                                    Approve request
                                                                            </span>';
                                                            }
                                                            else if($all_overtime_request -> isDecline_SVP == '1'){

                                                                $output .=' <span class="status late d-flex align-items-center">                                       
                                                                                <i class="bx bx-x"></i>
                                                                                Declined request
                                                                            </span>';
                                                            }
                                                            else{    
                                                                $output .=' <a href="#" id="' . $all_overtime_request -> id . '" type="button" class="btn btn-danger btn-sm mx-1 svp_decline" data-bs-toggle="modal" data-bs-target="#decline_ot_SVP">
                                                                                Decline Request
                                                                            </a>
                                                                            <a href="#" id="' . $all_overtime_request -> id . '" type="button" class="btn btn-success btn-sm mx-1 approve_svp" data-bs-toggle="modal" data-bs-target="#approve_ot_SVP">
                                                                                SVP Approval
                                                                            </a>';
                                                            }
                                                        
                                                        $output .='</div>
                                
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>';   
                    }
                    
                }
                else if(Auth::user()->hasRole('teamleader')){
                    
                    $depart = $all_overtime_request -> employee -> employee_department;

                    if(Auth::user()->department == $depart){
                        $output .= '<tr>
                                        <td>
                                            <div class="card time-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-xl-1">
                                                            <div class="d-flex">
                                                            
                                                            <p class="attendance_id" hidden>'.$all_overtime_request -> id.'</p>';
                                                            
                                                            if($all_overtime_request -> employee -> image != null){
                    
                                                                $output .=  '<img src="storage/employee/images/' . $all_overtime_request -> employee -> image . '" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:50px; height: 50px;">';
                                                            }
                                                            else{
                    
                                                                $output .= '<i class="bx bx-user icon profile-picture-time"> </i>';
                                                            }
                                                        
                                                        $output.=' </div>
                                                        </div>
                                                        <div class="col-xl-2">
                                                            <h5 class="emp-name">'.$all_overtime_request -> employee -> employee_name.'</h5>
                                                            <p class="emp-no">'.$all_overtime_request->emp_no.'</p>
                                                            
                                                        </div>
                                                        <div class="col-xl-1">
                                                                <h5 class="emp-name">'.$all_overtime_request -> employee -> employee_department.'</h5>
                                                                <p class="emp-no">Department</p>
                                                        
                                                        </div>
                                                        <div class="col-xl-1">
                                                            <h5 class="emp-name">'.Carbon::parse($all_overtime_request -> date)->toFormattedDateString().'</h5>
                                                            <p class="emp-no"> Date</p>
                                                        </div>
                    
                                                        <div class="col-xl-1 td-div">';
                                                        
                                                            $output .=  '<h5 class="time">'.Carbon::parse($all_overtime_request -> attendance ->time_in)->format('H:i').'</h5>';
                                                            $output .= '<p class="type">Time In</p>
                                                        </div>';
                    
                                                        $output .='<div class="col-xl-1 td-div">';

                                                            if($all_overtime_request-> attendance ->time_out == null){
                        
                                                                $output .=  '<h5 class="time">--:--</h5>';
                                                                $output .= '<p class="type">Time Out</p>';
                                                            }
                                                            else
                                                            {
                                                                $output .=  '<h5 class="time" >'.Carbon::parse($all_overtime_request -> attendance ->time_out)->format('H:i').'</h5>';
                                                                $output .= '<p class="type">Time Out</p>';
                                                            }    
                                                                
                                                        $output .='</div>';
                                                                
                                                            if($all_overtime_request -> hours_OT != null){

                                                                $output.='<div class="col-xl-1">
                                                                                <h5 class="emp-name">'.$all_overtime_request ->hours_OT.'</h5>
                                                                                <p class="emp-no"> Total Overtime</p>
                                                                            </div>';
                                                            }else{

                                                                $output.='<div class="col-xl-1">
                                                                            <h5 class="emp-name">--:--</h5>
                                                                            <p class="emp-no"> Total Overtime</p>
                                                                        </div>';
                                                            }
                                                        
                                                                $output .=' <div class="col-xl-2">
                                                                                <h6 class="emp-name">'.$all_overtime_request -> reason.'</h6>
                                                                                <p class="emp-no"> Reason</p>
                                                                            </div>';

                                                        $output .='<div class="col-xl-2 d-flex justify-content-end align-items-center gap-2">';
                                                    
                                                            if($all_overtime_request  -> isApproved_TL == '1'){
                
                                                                $output .=' <span class="status on-time d-flex align-items-center">                                       
                                                                                <i class="bx bx-check"></i>
                                                                                Approve request
                                                                            </span>';
                                                            }
                                                            else if($all_overtime_request  -> isDecline_TL == '1'){

                                                                $output .=' <span class="status late d-flex align-items-center">                                       
                                                                                <i class="bx bx-x"></i>
                                                                                Declined request
                                                                            </span>';
                                                            }
                                                            else{
                                                                $output .= '<a href="#" id="' . $all_overtime_request -> id . '" type="button" class="btn btn-danger btn-sm mx-1 tl_decline" data-bs-toggle="modal" data-bs-target="#decline_ot_TL">
                                                                                Decline Request
                                                                            </a>
                                                                            <a href="#" id="' . $all_overtime_request -> id . '" type="button" class="btn btn-success btn-sm mx-1 approve_tl" data-bs-toggle="modal" data-bs-target="#approve_ot_TL">
                                                                                Team Leader Approval
                                                                            </a>';
                                                            }
                                                            
                                                        $output .='</div>
                                
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>';
                    }
                }
                else if(Auth::user()->hasRole('VPO')){
                    if($all_overtime_request -> employee -> employee_department == 'Administration' || $all_overtime_request -> employee -> employee_department == 'App Intake' || $all_overtime_request -> employee -> employee_department == 'Audit' || $all_overtime_request -> employee -> employee_department == 'Verification' || $all_overtime_request -> employee -> employee_department == 'Returns'){
                        $output .= '<tr>
                                        <td>
                                            <div class="card time-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-xl-1">
                                                            <div class="d-flex">

                                                            <p class="attendance_id" hidden>'.$all_overtime_request -> id.'</p>';
                                                            
                                                            if($all_overtime_request -> employee -> image != null){

                                                                $output .=  '<img src="storage/employee/images/' . $all_overtime_request -> employee -> image . '" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:50px; height: 50px;">';

                                                            }
                                                            else{

                                                                $output .= '<i class="bx bx-user icon profile-picture-time"> </i>';
                                                            }
                                                        
                                                        $output .= '  </div>
                                                        </div>
                                                        <div class="col-xl-2">
                                                            <h5 class="emp-name">'.$all_overtime_request -> employee -> employee_name.'</h5>
                                                            <p class="emp-no">'.$all_overtime_request->emp_no.'</p>
                                                            
                                                        </div>
                                                        <div class="col-xl-1">
                                                                <h5 class="emp-name">'.$all_overtime_request -> employee -> employee_department.'</h5>
                                                                <p class="emp-no">Department</p>
                                                        
                                                        </div>
                                                        <div class="col-xl-1">
                                                            <h5 class="emp-name">'.Carbon::parse($all_overtime_request -> date)->toFormattedDateString().'</h5>
                                                            <p class="emp-no"> Date</p>
                                                        </div>

                                                        <div class="col-xl-1 td-div">';
                                                            $output .=  '<h5 class="time">'.Carbon::parse($all_overtime_request -> attendance ->time_in)->format('H:i').'</h5>';
                                                            $output .= '<p class="type">Time In</p>
                                                        </div>';

                                                        $output .='<div class="col-xl-1 td-div">';

                                                            if($all_overtime_request-> attendance ->time_out == null){
                        
                                                                $output .=  '<h5 class="time">--:--</h5>';
                                                                $output .= '<p class="type">Time Out</p>';
                                                            }
                                                            else
                                                            {
                                                                $output .=  '<h5 class="time" >'.Carbon::parse($all_overtime_request -> attendance ->time_out)->format('H:i').'</h5>';
                                                                $output .= '<p class="type">Time Out</p>';
                                                            }
                                                                
                                                        $output .='</div>';
                                                                
                                                            if($all_overtime_request -> hours_OT != null){

                                                                $output.='<div class="col-xl-1">
                                                                                <h5 class="emp-name">'.$all_overtime_request ->hours_OT.'</h5>
                                                                                <p class="emp-no"> Total Overtime</p>
                                                                            </div>';
                                                            } 
                                                            else{

                                                                $output.='<div class="col-xl-1">
                                                                            <h5 class="emp-name">--:--</h5>
                                                                            <p class="emp-no"> Total Overtime</p>
                                                                        </div>';
                                                            }
                                                        
                                                                $output .=' <div class="col-xl-2">
                                                                                <h6 class="emp-name">'.$all_overtime_request -> reason.'</h6>
                                                                                <p class="emp-no"> Reason</p>
                                                                            </div>';

                                                        $output .='<div class="col-xl-2 d-flex justify-content-end align-items-center gap-2">';
                                                                    
                                                            if($all_overtime_request  -> isApproved_VPO == '1'){

                                                                $output .=' <span class="status on-time d-flex align-items-center">                                       
                                                                                <i class="bx bx-check"></i>
                                                                                Approve request
                                                                            </span>';
                                                            }
                                                            else if($all_overtime_request  -> isDecline_VPO == '1'){

                                                                $output .=' <span class="status late d-flex align-items-center">                                       
                                                                                <i class="bx bx-x"></i>
                                                                                Declined request
                                                                            </span>';
                                                            }
                                                            else{

                                                                $output .='<a href="#" id="' . $all_overtime_request -> id . '" type="button" class="btn btn-danger btn-sm mx-1 vpo_decline" data-bs-toggle="modal" data-bs-target="#decline_ot_VPO">
                                                                                Decline Request
                                                                            </a>
                                                                            <a href="#" id="' . $all_overtime_request -> id . '" type="button" class="btn btn-success btn-sm mx-1 approve_vpo" data-bs-toggle="modal" data-bs-target="#approve_ot_VPO">
                                                                                VPO Approval
                                                                            </a> ';
                                                            }
                                                            
                                                        $output .='</div>
                                
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>';
                    }
                }
                else if(Auth::user()->hasRole('COO')){
                    if($all_overtime_request->employee->employee_department == 'Project Management' || $all_overtime_request -> employee -> employee_department == 'Provider Relation' || $all_overtime_request -> employee -> employee_department == 'Provider Enrollment'){
                        $output .= '<tr>
                                        <td>
                                            <div class="card time-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-xl-1">
                                                            <div class="d-flex">

                                                            <p class="attendance_id" hidden>'.$all_overtime_request -> id.'</p>';
                                                            
                                                            if($all_overtime_request -> employee -> image != null){

                                                                $output .=  '<img src="storage/employee/images/' . $all_overtime_request -> employee -> image . '" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:50px; height: 50px;">';

                                                            }
                                                            else{

                                                                $output .= '<i class="bx bx-user icon profile-picture-time"> </i>';
                                                            }
                                                        
                                                        $output .= '  </div>
                                                        </div>
                                                        <div class="col-xl-2">
                                                            <h5 class="emp-name">'.$all_overtime_request -> employee -> employee_name.'</h5>
                                                            <p class="emp-no">'.$all_overtime_request->emp_no.'</p>
                                                            
                                                        </div>
                                                        <div class="col-xl-1">
                                                                <h5 class="emp-name">'.$all_overtime_request -> employee -> employee_department.'</h5>
                                                                <p class="emp-no">Department</p>
                                                        
                                                        </div>
                                                        <div class="col-xl-1">
                                                            <h5 class="emp-name">'.Carbon::parse($all_overtime_request -> date)->toFormattedDateString().'</h5>
                                                            <p class="emp-no"> Date</p>
                                                        </div>

                                                        <div class="col-xl-1 td-div">';
                                                        
                                                            $output .=  '<h5 class="time">'.Carbon::parse($all_overtime_request -> attendance ->time_in)->format('H:i').'</h5>';
                                                            $output .= '<p class="type">Time In</p>
                                                        </div>';

                                                        $output .='<div class="col-xl-1 td-div">';

                                                            if($all_overtime_request-> attendance ->time_out == null){
                        
                                                                $output .=  '<h5 class="time">--:--</h5>';
                                                                $output .= '<p class="type">Time Out</p>';
                                                            }
                                                            else
                                                            {
                                                                $output .=  '<h5 class="time" >'.Carbon::parse($all_overtime_request -> attendance ->time_out)->format('H:i').'</h5>';
                                                                $output .= '<p class="type">Time Out</p>';
                                                            }
            
                                                        $output .='</div>';
                                                                    
                                                            if($all_overtime_request -> hours_OT != null){

                                                                $output.='<div class="col-xl-1">
                                                                                <h5 class="emp-name">'.$all_overtime_request ->hours_OT.'</h5>
                                                                                <p class="emp-no"> Total Overtime</p>
                                                                            </div>';
                                                            } 
                                                            else{

                                                                $output.='<div class="col-xl-1">
                                                                            <h5 class="emp-name">--:--</h5>
                                                                            <p class="emp-no"> Total Overtime</p>
                                                                        </div>';
                                                            }
                                                        
                                                                $output .=' <div class="col-xl-2">
                                                                                <h6 class="emp-name">'.$all_overtime_request -> reason.'</h6>
                                                                                <p class="emp-no"> Reason</p>
                                                                            </div>';

                                                        $output .='<div class="col-xl-2 d-flex justify-content-end align-items-center gap-2">';
        
                                                                if($all_overtime_request->isApproved_COO == '1'){

                                                                    $output .=' <span class="status on-time d-flex align-items-center">                                       
                                                                                    <i class="bx bx-check"></i>
                                                                                    Approve request
                                                                                </span>';
                                                                }
                                                                else if($all_overtime_request -> isDecline_COO == '1'){

                                                                    $output .=' <span class="status late d-flex align-items-center">                                       
                                                                                    <i class="bx bx-x"></i>
                                                                                    Declined request
                                                                                </span>';
                                                                }
                                                                else{

                                                                    $output .='<a href="#" id="' . $all_overtime_request -> id . '" type="button" class="btn btn-danger btn-sm mx-1 coo_decline" data-bs-toggle="modal" data-bs-target="#decline_ot_COO">
                                                                                    Decline Request
                                                                                </a>
                                                                                <a href="#" id="' . $all_overtime_request -> id . '" type="button" class="btn btn-success btn-sm mx-1 approve_coo" data-bs-toggle="modal" data-bs-target="#approve_ot_COO">
                                                                                    COO Approval
                                                                                </a> ';
                                                                }                                    
                                                            
                                                                                        
                                                        $output .='</div>
                                
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>';
                    }
                }
                
            }
            $output .= '</tbody></table>';
            echo $output;

        } 
        else 
        {
            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }      
      
    }


    
     // Approve HR 
     public function approve_HR(Request $request) {

		$attendance = Attendance::find($request->id);
		return response()->json($attendance);
	}

    public function submit_approve_HR(Request $request){


       $approve_HR = Overtime::find($request->hr_id);
       $approve_HR -> isApproved_HR = '1';
       $approve_HR -> update();

            return response()->json([
                'code' => 200, 
                'msg' => 'OT Approve',
            ]);
        
        
    }

    // Approve SVP
    public function approve_SVP(Request $request) {

		$attendance = Attendance::find($request->id);
		return response()->json($attendance);
	}

    public function submit_approve_SVP(Request $request){
        
        // Overtime::where('attendance_id', '=', $request -> svp_id)->update(['isApproved_SVP' => '1']);
        $approve_SVP = Overtime::find($request->svp_id);
        $approve_SVP -> isApproved_SVP = 1;
        $approve_SVP -> update();

            return response()->json([
                'code' => 200, 
                'msg' => 'OT Approve',
            ]);
        
        
    }

    // Approve TL
    public function approve_TL(Request $request) {

		$attendance = Overtime::find($request->id);
		return response()->json($attendance);
	}

    public function submit_approve_TL(Request $request){
    

        $approve_TL = Overtime::find($request->tl_id);
        $approve_TL -> isApproved_TL = 1;
        $approve_TL -> update();

        return response()->json([
            'code' => 200, 
            'msg' => 'OT Approve',
        ]);
        
    }


    // Approve VPO
    public function approve_VPO(Request $request) {
	
		$attendance = Overtime::find($request->id);
		return response()->json($attendance);
	}

    public function submit_approve_VPO(Request $request){
    
        $approve_VPO = Overtime::find($request->VPO_id);
        $approve_VPO -> isApproved_VPO = 1;
        $approve_VPO -> update();


        return response()->json([
            'code' => 200, 
            'msg' => 'OT Approve',
        ]);
  
    }

    //COO VIEW THE ID NUMBER
    public function approve_COO(Request $request) {
	
		$attendance = Overtime::find($request->id);
		return response()->json($attendance);
	}

    //Approve request 
    public function submit_approve_COO(Request $request){
    
        $approve_COO = Overtime::find($request->COO_id);
        $approve_COO -> isApproved_COO = 1;
        $approve_COO -> update();


        return response()->json([
            'code' => 200, 
            'msg' => 'OT Approve',
        ]);
  
    }








    //FOR DECLINE OF OVERTIME 
    public function decline_HR(Request $request){

        $HR_decline_request = Overtime::find($request -> id);

		return response()->json($HR_decline_request);
    }

    public function submit_HR_decline(Request $request){

        $decline_HR = Overtime::find($request->hr_decline_id);
        $decline_HR -> isDecline_HR = 1;
        $decline_HR -> update();

        return response()->json([
            'code' => 200, 
            'msg' => 'HR decline the overtime request',
        ]);

    }


    public function decline_TL(Request $request){

        $TL_decline_request = Overtime::find($request -> id);

		return response()->json($TL_decline_request);
    }

    public function submit_TL_decline(Request $request){

        $decline_TL = Overtime::find($request->tl_decline_id);
        $decline_TL -> isDecline_TL = 1;
        $decline_TL -> update();

        return response()->json([
            'code' => 200, 
            'msg' => 'Team Leader decline the overtime request',
        ]);
    }


    public function decline_SVP(Request $request){

        $SVP_decline_request = Overtime::find($request -> id);

		return response()->json($SVP_decline_request);
    }

    public function submit_SVP_decline(Request $request){

        $decline_SVP = Overtime::find($request->svp_decline_id);
        $decline_SVP -> isDecline_SVP = 1;
        $decline_SVP -> update();

        return response()->json([
            'code' => 200, 
            'msg' => 'SVP decline the overtime request',
        ]);

    }


    public function decline_VPO(Request $request){

        $VPO_decline_request = Overtime::find($request -> id);

		return response()->json($VPO_decline_request);
    }

    public function submit_VPO_decline(Request $request){

        $decline_VPO = Overtime::find($request->VPO_decline_id);
        $decline_VPO -> isDecline_VPO = 1;
        $decline_VPO -> update();

        return response()->json([
            'code' => 200, 
            'msg' => 'VPO decline the overtime request',
        ]);

    }


    public function decline_COO(Request $request){

        $COO_decline_request = Overtime::find($request -> id);

		return response()->json($COO_decline_request);
    }

    public function submit_COO_decline(Request $request){

        $decline_COO = Overtime::find($request->COO_decline_id);
        $decline_COO -> isDecline_COO = 1;
        $decline_COO -> update();

        return response()->json([
            'code' => 200, 
            'msg' => 'COO decline the overtime request',
        ]);

    }
}