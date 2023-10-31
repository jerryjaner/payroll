<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\LeaveReq;
use App\Models\Overtime;
use App\Models\Attendance;
use Carbon\Carbon;
use Auth;

class LeaveReqController extends Controller
{
    public function leaveRequest()
    {
        // $employees = Employee::latest()->get();
        // $leave_reqs = LeaveReq::latest()->get();
        // return view('attendance.leaveRequest', compact('employees','leave_reqs'));


        $leave_approval = LeaveReq::get(['is_HR_Approved']);
        $employees = Employee::get(['id','employee_name']);
        return view('attendance.leaveRequest', compact('employees','leave_approval'));
    }

    public function add_leave(Request $request)
    {
        $validator = \Validator::make($request -> all(),[

            // 'leave_day'         => 'required',
            'start_date'        => 'required|date|after:today',
            'end_date'          => 'required|date|after:start_date',
            'leave_type'        => 'required',
            'department'        => 'required',
            'reason'            => 'required',
            'address'           => 'required',
            'contact'           => 'required|min:11',
        ]);

        if($validator -> fails()){

            // if the validator fails
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }
        else{

            $formatted_dt1=Carbon::parse($request -> start_date);
            $formatted_dt2=Carbon::parse($request -> end_date);
            // Excluded specific day in a week
            $excluded_day = [Carbon::SUNDAY, Carbon::MONDAY]; 

            // Add the dates, skipping the excluded day of the week
            $date_diff = $formatted_dt1->diffInDaysFiltered(function(Carbon $date) use ($excluded_day) {
            return !in_array($date->dayOfWeek, $excluded_day);
            }, $formatted_dt2)+1;

            LeaveReq::create([
                'leave_day'         => $date_diff,
                'user_id'           => Auth::user()->id,
                'name'              => Auth::user()->name,
                'start_date'        => $request->start_date,
                'end_date'          => $request->end_date,
                'leave_type'        => $request->leave_type,
                'department'        => $request->department,
                'reason'            => $request->reason,
                'address'           => $request->address,
                'contact'           => $request->contact,
                'person1'           => $request->person1,
                'person2'           => $request->person2,
            ]);
            return response()->json([
                'code' => 200,
                'msg' => 'Leave Request Added Successfully',
            ]);

        }
    }

    public function AllLeaveRequest()
    {

        $all_leave = LeaveReq::all();

        $output = '';
        if ($all_leave->count() > 0) {

	 		$output .= '<table class="leave-tbl table" style="width: 100%" id="all_leave">
            <thead>
            <tr>
                <th hidden> </th>
            </tr>
            </thead>
            <tbody>';
            foreach ($all_leave as $leave) {
                if ($leave -> user_id == Auth::user()->id){
                    $output .=
                    ' <tr>
                        <td>
                            <div class="card time-card">
                                <div class="card-body">
                                    <div class="row">
                                            <div>
                                                <div class="d-flex">
                                                    <p class="attt_id" hidden>'.$leave->id.'</p>
                                                </div>
                                            </div>
                                            <div class="col-xl-2">
                                                <h5 class="emp-name">'.$leave->name.'</h5>
                                                <p class="emp-no">Employee Name</p>
                                            </div>
                                            <div class="col-xl-2">
                                                <h5 class="emp-name">'.$leave-> leave_type.'</h5>
                                                <p class="emp-no">Leave Type</p>
                                            </div>
                                            <div class="col-xl-2 td-div">
                                                <h5 class="time">'.Carbon::parse($leave->start_date)->toFormattedDateString('F j, Y').'</h5>
                                                <p class="type">Start Date</p>
                                                </div>
                                            <div class="col-xl-2 td-div">
                                                    <h5 class="time">'.Carbon::parse($leave->end_date)->toFormattedDateString('F j, Y').'</h5>
                                                    <p class="type">End Date</p>
                                            </div>
                                            <div class="col-xl-1 td-div">
                                                <h5 class="time">'.$leave -> leave_day.'</h5>
                                                <p class="type">Total Days</p>
                                            </div>';

                                                if($leave -> is_HR_Decline == '1'){
                                                    $output  .='<div class="col-xl-3 td-div">
                                                                    <span class="status late d-flex align-items-center">
                                                                        <i class="bx bx-x"></i>
                                                                        Request Declined
                                                                    </span>
                                                                </div>';
                                                }
                                                else if($leave -> is_HR_Approved == '1'){
                                                    $output  .='<div class="col-xl-3 td-div">
                                                                    <span class="status on-time d-flex align-items-center">
                                                                        <i class="bx bx-check"></i>
                                                                        Approved Leave
                                                                    </span>
                                                                </div>';
                                                }
                                                else{
                                                    $output  .='<div class="col-xl-3 td-div">
                                                                    <span class="status late d-flex align-items-center">
                                                                        <i class="bx bx-revision"></i>
                                                                        On Process
                                                                    </span>
                                                                </div>';
                                                }

                        $output  .='    </div>
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

    public function AllLeaveEmp()
    {

        $all_leave = LeaveReq::all();

        $output = '';
        if ($all_leave->count() > 0) {

	 		$output .= '<table class="leaveEmp-tbl table" style="width: 100%" id="all_leave_Emp">
            <thead>
            <tr>
                <th hidden> </th>
            </tr>
            </thead>
            <tbody>';
            foreach ($all_leave as $leave) {
                if(Auth::user()->hasRole(['HR','assistantHR'])){

                        $output .= '<tr>
                                        <td>
                                            <div class="card time-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <p class="attt_id" hidden>'.$leave->id.'</p>
                                                        <div class="col-xl-2">
                                                            <h5 class="emp-name">'.$leave->name.'</h5>
                                                            <p class="emp-no">Employee Name</p>
                                                        </div>
                                                        <div class="col-xl-2">
                                                            <h5 class="emp-name">'.$leave->leave_type.'</h5>
                                                            <p class="emp-no">Leave Type</p>
                                                        </div>
                                                    <div class="col-xl-2 td-div">
                                                            <h5 class="time">'.Carbon::parse($leave->start_date)->toFormattedDateString('F j, Y').'</h5>
                                                            <p class="type">Start Date</p>
                                                        </div>
                                                        <div class="col-xl-2 td-div">
                                                            <h5 class="time">'.Carbon::parse($leave->end_date)->toFormattedDateString('F j, Y').'</h5>
                                                            <p class="type">End Date</p>
                                                        </div>
                                                        <div class="col-xl-2 td-div">
                                                            <h5 class="time">'.$leave -> leave_day.'</h5>
                                                            <p class="type">Total Days</p>
                                                        </div>


                                                    <div class="col-xl-2 d-flex justify-content-end align-items-center">';

                                                            if($leave -> is_HR_Approved == '1'){
                                                                $output .=' <span class="status on-time d-flex align-items-center">
                                                                                <i class="bx bx-check"></i>
                                                                                Approved Leave
                                                                            </span>';
                                                            }else if($leave -> is_HR_Decline == '1'){
                                                                $output  .='<span class="status late d-flex align-items-center">
                                                                                <i class="bx bx-x"></i>
                                                                                Request Declined
                                                                            </span>';
                                                            }else{
                                                                $output .=' <a href="#" id="' . $leave->id . '" type="button" class="btn btn-success btn-sm mx-1 hr_approve" data-bs-toggle="modal" data-bs-target="#HR_approve">
                                                                                Approval
                                                                            </a>
                                                                            <a href="#" id="' . $leave->id . '" type="button" class="btn btn-danger btn-sm mx-1 hr_decline" data-bs-toggle="modal" data-bs-target="#HR_decline">
                                                                                Decline
                                                                            </a>';
                                                            }

                                                $output .='</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>';
                }
                else if(Auth::user()->hasRole(['administrator','SVPT'])){
                    if($leave -> department == "IT" ){
                        $output .= '<tr>
                                        <td>
                                            <div class="card time-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <p class="attt_id" hidden>'.$leave->id.'</p>
                                                        <div class="col-xl-2">
                                                            <h5 class="emp-name">'.$leave->name.'</h5>
                                                            <p class="emp-no">Employee Name</p>
                                                        </div>
                                                        <div class="col-xl-2">
                                                            <h5 class="emp-name">'.$leave->leave_type.'</h5>
                                                            <p class="emp-no">Leave Type</p>
                                                        </div>
                                                    <div class="col-xl-2 td-div">
                                                            <h5 class="time">'.Carbon::parse($leave->start_date)->toFormattedDateString('F j, Y').'</h5>
                                                            <p class="type">Start Date</p>
                                                        </div>
                                                        <div class="col-xl-2 td-div">
                                                            <h5 class="time">'.Carbon::parse($leave->end_date)->toFormattedDateString('F j, Y').'</h5>
                                                            <p class="type">End Date</p>
                                                        </div>
                                                        <div class="col-xl-2 td-div">
                                                            <h5 class="time">'.$leave -> leave_day.'</h5>
                                                            <p class="type">Total Days</p>
                                                        </div>


                                                    <div class="col-xl-2 d-flex justify-content-end align-items-center">';

                                                        if($leave -> is_SVP_Approved == '1'){
                                                            $output .=' <span class="status on-time d-flex align-items-center">
                                                                            <i class="bx bx-check"></i>
                                                                            Approved Leave
                                                                        </span>';
                                                        }
                                                        else if($leave -> is_SVP_Decline == '1'){
                                                            $output  .='<span class="status late d-flex align-items-center">
                                                                            <i class="bx bx-x"></i>
                                                                            Request Declined
                                                                        </span>';
                                                        }else{
                                                            $output  .='<a href="#" id="' . $leave->id . '" type="button" class="btn btn-success btn-sm mx-1 svp_approve" data-bs-toggle="modal" data-bs-target="#SVP_approve">
                                                                            Approval
                                                                        </a>
                                                                        <a href="#" id="' . $leave->id . '" type="button" class="btn btn-danger btn-sm mx-1 svp_decline" data-bs-toggle="modal" data-bs-target="#SVP_decline">
                                                                            Decline
                                                                        </a>';
                                                        }

                                                $output .='</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>';
                    }
                }
                else if(Auth::user()->hasRole('teamleader')){
                    if($leave -> department == Auth::user()->department){
  
                      $output .= '<tr>
                                      <td>
                                          <div class="card time-card">
                                              <div class="card-body">
                                                  <div class="row">
                                                      <p class="attt_id" hidden>'.$leave->id.'</p>
                                                      <div class="col-xl-2">
                                                          <h5 class="emp-name">'.$leave->name.'</h5>
                                                          <p class="emp-no">Employee Name</p>
                                                      </div>
                                                      <div class="col-xl-2">
                                                          <h5 class="emp-name">'.$leave->leave_type.'</h5>
                                                          <p class="emp-no">Leave Type</p>
                                                      </div>
                                                  <div class="col-xl-2 td-div">
                                                          <h5 class="time">'.Carbon::parse($leave->start_date)->toFormattedDateString('F j, Y').'</h5>
                                                          <p class="type">Start Date</p>
                                                      </div>
                                                      <div class="col-xl-2 td-div">
                                                          <h5 class="time">'.Carbon::parse($leave->end_date)->toFormattedDateString('F j, Y').'</h5>
                                                          <p class="type">End Date</p>
                                                      </div>
                                                      <div class="col-xl-2 td-div">
                                                          <h5 class="time">'.$leave -> leave_day.'</h5>
                                                          <p class="type">Total Days</p>
                                                      </div>
  
  
                                                  <div class="col-xl-2 d-flex justify-content-end align-items-center">';
  
                                                    if($leave -> is_TL_Approved == '1'){
                                                        $output .=' <span class="status on-time d-flex align-items-center">
                                                                        <i class="bx bx-check"></i>
                                                                        Approved Leave
                                                                    </span>';
                                                    }else if($leave -> is_TL_Decline == '1'){
                                                        $output  .='<span class="status late d-flex align-items-center">
                                                                        <i class="bx bx-x"></i>
                                                                        Request Declined
                                                                    </span>';
                                                    }else{
                                                        $output .='  <a href="#" id="' . $leave->id . '" type="button" class="btn btn-success btn-sm mx-1 tl_approve" data-bs-toggle="modal" data-bs-target="#TL_approve">
                                                                        Approval
                                                                    </a>
                                                                    <a href="#" id="' . $leave->id . '" type="button" class="btn btn-danger btn-sm mx-1 tl_decline" data-bs-toggle="modal" data-bs-target="#TL_decline">
                                                                        Decline
                                                                    </a>';
                                                    }
  
                                              $output .='</div>
                                                  </div>
                                              </div>
                                          </div>
                                      </td>
                                  </tr>';
  
                    }
                }
                else if(Auth::user()->hasRole('VPO')){
                    if($leave->department == "App Intake" || $leave->department == "Audit" || $leave->department == "Verification" || $leave->department == "Returns"){
                      $output .= '<tr>
                                      <td>
                                          <div class="card time-card">
                                              <div class="card-body">
                                                  <div class="row">
                                                      <p class="attt_id" hidden>'.$leave->id.'</p>
                                                      <div class="col-xl-2">
                                                          <h5 class="emp-name">'.$leave->name.'</h5>
                                                          <p class="emp-no">Employee Name</p>
                                                      </div>
                                                      <div class="col-xl-2">
                                                          <h5 class="emp-name">'.$leave->leave_type.'</h5>
                                                          <p class="emp-no">Leave Type</p>
                                                      </div>
                                                  <div class="col-xl-2 td-div">
                                                          <h5 class="time">'.Carbon::parse($leave->start_date)->toFormattedDateString('F j, Y').'</h5>
                                                          <p class="type">Start Date</p>
                                                      </div>
                                                      <div class="col-xl-2 td-div">
                                                          <h5 class="time">'.Carbon::parse($leave->end_date)->toFormattedDateString('F j, Y').'</h5>
                                                          <p class="type">End Date</p>
                                                      </div>
                                                      <div class="col-xl-2 td-div">
                                                          <h5 class="time">'.$leave -> leave_day.'</h5>
                                                          <p class="type">Total Days</p>
                                                      </div>
  
  
                                                  <div class="col-xl-2 d-flex justify-content-end align-items-center">';
  
                                                    if($leave -> is_VPO_Approved == '1'){
                                                        $output .=' <span class="status on-time d-flex align-items-center">
                                                                        <i class="bx bx-check"></i>
                                                                        Approved Leave
                                                                    </span>';
                                                    }else if($leave -> is_VPO_Decline == '1'){
                                                        $output  .='<span class="status late d-flex align-items-center">
                                                                        <i class="bx bx-x"></i>
                                                                        Request Declined
                                                                    </span>';
                                                    }else{
                                                        $output .='  <a href="#" id="' . $leave->id . '" type="button" class="btn btn-success btn-sm mx-1 vpo_approve" data-bs-toggle="modal" data-bs-target="#VPO_approve">
                                                                        Approval
                                                                    </a>
                                                                    <a href="#" id="' . $leave->id . '" type="button" class="btn btn-danger btn-sm mx-1 vpo_decline" data-bs-toggle="modal" data-bs-target="#VPO_decline">
                                                                        Decline
                                                                    </a>';
                                                    }
  
                                              $output .='</div>
                                                  </div>
                                              </div>
                                          </div>
                                      </td>
                                  </tr>';
                    }
                }
                else if(Auth::user()->hasRole('COO')){
                    if($leave->department == "Project Management" || $leave->department == "Provider Relation" || $leave->department == "Provider Enrollment"){
  
                      $output .= '<tr>
                                      <td>
                                          <div class="card time-card">
                                              <div class="card-body">
                                                  <div class="row">
                                                      <p class="attt_id" hidden>'.$leave->id.'</p>
                                                      <div class="col-xl-2">
                                                          <h5 class="emp-name">'.$leave->name.'</h5>
                                                          <p class="emp-no">Employee Name</p>
                                                      </div>
                                                      <div class="col-xl-2">
                                                          <h5 class="emp-name">'.$leave->leave_type.'</h5>
                                                          <p class="emp-no">Leave Type</p>
                                                      </div>
                                                  <div class="col-xl-2 td-div">
                                                          <h5 class="time">'.Carbon::parse($leave->start_date)->toFormattedDateString('F j, Y').'</h5>
                                                          <p class="type">Start Date</p>
                                                      </div>
                                                      <div class="col-xl-2 td-div">
                                                          <h5 class="time">'.Carbon::parse($leave->end_date)->toFormattedDateString('F j, Y').'</h5>
                                                          <p class="type">End Date</p>
                                                      </div>
                                                      <div class="col-xl-2 td-div">
                                                          <h5 class="time">'.$leave -> leave_day.'</h5>
                                                          <p class="type">Total Days</p>
                                                      </div>
  
  
                                                  <div class="col-xl-2 d-flex justify-content-end align-items-center">';
  
                                                    if($leave -> is_COO_Approved == '1'){
                                                        $output .=' <span class="status on-time d-flex align-items-center">
                                                                        <i class="bx bx-check"></i>
                                                                        Approved Leave
                                                                    </span>';
                                                    }else if($leave -> is_COO_Decline == '1'){
                                                        $output  .='<span class="status late d-flex align-items-center">
                                                                        <i class="bx bx-x"></i>
                                                                        Request Declined
                                                                    </span>';
                                                    }else{
                                                        $output .='  <a href="#" id="' . $leave->id . '" type="button" class="btn btn-success btn-sm mx-1 coo_approve" data-bs-toggle="modal" data-bs-target="#COO_approve">
                                                                        Approval
                                                                    </a>
                                                                    <a href="#" id="' . $leave->id . '" type="button" class="btn btn-danger btn-sm mx-1 coo_decline" data-bs-toggle="modal" data-bs-target="#COO_decline">
                                                                        Decline
                                                                    </a>';
                                                    }
  
                                              $output .='</div>
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

    // Approve TL
    public function Approve_leave_TL(Request $request)
    {

		$leave_request = Employee::find($request->id);
		return response()->json($leave_request);
    }

    public function Submit_leave_TL(Request $request)
    {

        $approve_submit_TL = LeaveReq::find($request->id_tl);
        $approve_submit_TL -> is_TL_Approved = 1;
        $approve_submit_TL -> update();

            return response()->json([
                'code' => 200,
                'msg' => 'Leave Request Approve',
            ]);
    }

    // Decline TL
    public function Decline_leave_TL(Request $request)
    {

		$leave_request = Employee::find($request->id);
		return response()->json($leave_request);
    }

    public function Submit_decline_TL(Request $request)
    {

        $decline_submit_TL = LeaveReq::find($request->id_decline_tl);
        $decline_submit_TL -> is_TL_Decline = 1;
        $decline_submit_TL -> update();

            return response()->json([
                'code' => 200,
                'msg' => 'Leave Request Decline',
            ]);
    }

    // Approve HR
    public function Approve_leave_HR(Request $request)
    {

		$leave_request = Employee::find($request->id);
		return response()->json($leave_request);
    }

    public function Submit_leave_HR(Request $request)
    {

        $approve_submit_HR = LeaveReq::find($request->id_hr);
        $approve_submit_HR -> is_HR_Approved = 1;
        $approve_submit_HR -> update();

            return response()->json([
                'code' => 200,
                'msg' => 'Leave Request Approve',
            ]);
    }

    // Decline HR
    public function Decline_leave_HR(Request $request)
    {

		$leave_request = Employee::find($request->id);
		return response()->json($leave_request);
    }

    public function Submit_decline_HR(Request $request)
    {

        $decline_submit_HR = LeaveReq::find($request->id_decline_hr);
        $decline_submit_HR -> is_HR_Decline = 1;
        $decline_submit_HR -> update();

            return response()->json([
                'code' => 200,
                'msg' => 'Leave Request Decline',
            ]);
    }

    // Approve SVP
    public function Approve_leave_SVP(Request $request)
    {

		$leave_request = Employee::find($request->id);
		return response()->json($leave_request);
    }

    public function Submit_leave_SVP(Request $request)
    {

        $approve_submit_SVP = LeaveReq::find($request->id_svp);
        $approve_submit_SVP -> is_SVP_Approved = 1;
        $approve_submit_SVP -> update();

            return response()->json([
                'code' => 200,
                'msg' => 'Leave Request Approve',
            ]);
    }

    // Decline SVP
    public function Decline_leave_SVP(Request $request)
    {

		$leave_request = Employee::find($request->id);
		return response()->json($leave_request);
    }

    public function Submit_decline_SVP(Request $request)
    {

        $decline_submit_SVP = LeaveReq::find($request->id_decline_svp);
        $decline_submit_SVP -> is_SVP_Decline = 1;
        $decline_submit_SVP -> update();

            return response()->json([
                'code' => 200,
                'msg' => 'Leave Request Decline',
            ]);
    }

    // Approve VPO
    public function Approve_leave_VPO(Request $request)
    {
		$leave_request = Employee::find($request->id);
		return response()->json($leave_request);
    }

    public function Submit_leave_VPO(Request $request)
    {

        $approve_submit_SVP = LeaveReq::find($request->id_vpo);
        $approve_submit_SVP -> is_VPO_Approved = 1;
        $approve_submit_SVP -> update();

            return response()->json([
                'code' => 200,
                'msg' => 'Leave Request Approve',
            ]);
    }

    // Decline VPO
    public function Decline_leave_VPO(Request $request)
    {

        $leave_request = Employee::find($request->id);
        return response()->json($leave_request);
    }

    public function Submit_decline_VPO(Request $request)
    {

        $decline_submit_SVP = LeaveReq::find($request->id_decline_vpo);
        $decline_submit_SVP -> is_VPO_Decline = 1;
        $decline_submit_SVP -> update();

            return response()->json([
                'code' => 200,
                'msg' => 'Leave Request Decline',
            ]);
    }

     // Approve COO
     public function Approve_leave_COO(Request $request)
     {

         $leave_request = Employee::find($request->id);
         return response()->json($leave_request);
     }
 
     public function Submit_leave_COO(Request $request)
     {
 
         $approve_submit_SVP = LeaveReq::find($request->id_coo);
         $approve_submit_SVP -> is_COO_Approved = 1;
         $approve_submit_SVP -> update();
 
             return response()->json([
                 'code' => 200,
                 'msg' => 'Leave Request Approve',
             ]);
     }
 
     // Decline COO
     public function Decline_leave_COO(Request $request)
     {
 
         $leave_request = Employee::find($request->id);
         return response()->json($leave_request);
     }
 
     public function Submit_decline_COO(Request $request)
     {
 
         $decline_submit_SVP = LeaveReq::find($request->id_decline_coo);
         $decline_submit_SVP -> is_COO_Decline = 1;
         $decline_submit_SVP -> update();
 
             return response()->json([
                 'code' => 200,
                 'msg' => 'Leave Request Decline',
             ]);
     }



}
