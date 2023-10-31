<?php

namespace App\Http\Controllers;
use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Payroll;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\SSS_deduction;
use Illuminate\Support\Facades\DB;
use App\Models\Philhealth_deduction;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('account.index');
    }

    public function view_payslip1(Request $request){

        $view_payslip1 = Payroll::with('employee')->find($request -> id);
        return response()->json($view_payslip1);
      }
    
      //edit employee
        public function edit_employee1(Request $request)
        {
            $id = $request->id;
            $emp = Employee::find($id);
            return response()->json($emp);
        }
    
      // handle update an employee ajax request
        public function update1(Request $request)
        {
            //To validate the employee
            $validator = \Validator::make($request -> all(),[
                'employee_name' => 'required',
                'employee_position' => 'required',
                'base_salary' => 'required',
                // 'daily_rate' => 'required',
                'employee_department' => 'required',
                'sched_start' => 'required',
                'sched_end' => 'required',
                'breaktime_start' => 'required',
                'breaktime_end' => 'required',
                'employee_shift' => 'required',
                'work_days' => 'required',
                'date_hired' => 'required',
                'qr' => 'qr',
                'employee_birthday' => 'required',
    
                // 'sss' => 'required',
                // 'pagibig' => 'required',
                // 'philhealth' => 'required',
                'employee_address' => 'required',
                'employee_allowance' => 'required',
                'employee_contact_number' => 'required',
                'emergency_contact_name' => 'required',
                'emergency_contact_number' => 'required',
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
            {
                $fileName = '';
                $emp_update = Employee::find($request->emp_id);
                if($request ->hasfile('image'))
                {
                    $file = $request->file('image');
                    $extension = $file -> getClientOriginalExtension();
                    $fileName = time() . '.' .$extension;
                    $file->storeAs('public/employee/images', $fileName);
                    if ($emp_update->image)
                    {
                        Storage::delete('public/employee/images/' . $emp_update->image);
                    }
                    $emp_update -> image = $fileName;
                }
                // Rate
                $base_salary = $request -> base_salary;
                $rate = $base_salary / 22;
    
                $emp_update -> employee_name = $request -> employee_name;
                // $emp_update -> daily_rate = $request -> daily_rate;
                $emp_update -> base_salary = $request -> base_salary;
                $emp_update -> monthly_rate = $request -> monthly_rate; 
                $emp_update -> daily_rate = $rate;
                $emp_update -> employee_position = $request -> employee_position;
                $emp_update -> employee_department = $request -> employee_department;
                $emp_update -> sched_start = $request -> sched_start;
                $emp_update -> sched_end = $request -> sched_end;
    
                $emp_update -> breaktime_start = $request -> breaktime_start;
                $emp_update -> breaktime_end = $request -> breaktime_end;
                $emp_update -> employee_shift = $request -> employee_shift;
                $emp_update -> work_days = implode(',', $request -> work_days);
                $emp_update -> date_hired = $request -> date_hired;
                $emp_update -> employee_birthday = $request -> employee_birthday;
                $emp_update -> qr = $request -> employee_no;
                $emp_update -> employee_address = $request -> employee_address;
                $emp_update -> sss_number = $request -> sss;
                $emp_update -> pagibig_number = $request -> pagibig;
                $emp_update -> philhealth_number = $request -> philhealth;
                $emp_update -> employee_allowance = $request -> employee_allowance;
                $emp_update -> employee_contact_number = $request -> employee_contact_number;
                $emp_update -> emergency_contact_name = $request -> emergency_contact_name;
                $emp_update -> emergency_contact_number = $request -> emergency_contact_number;
                $emp_update -> update();
    
    
                User::where('id', '=', $request -> user_id )
                    ->update([
                                'department' => $request -> employee_department,
                                'name' => $request -> employee_name,
                                'position' => $request -> employee_position,
    
                            ]);
    
                LeaveReq::where('user_id', '=', $request -> user_id )->update([ 'name' => $request -> employee_name, ]);
    
    
    
                return response()->json([
    
                    'status' => 200,
                    'msg' => 'Employee Update Successfully',
                ]);
            }
        }
    
      public function get_all_payroll1(Request $request){
    
        $user = Auth::user(); // Get the currently authenticated user
        
        $show_payrolls = Payroll::with('employee')->where('employee_number', $user->employee->employee_no)->get();
    
       $output = '';
       if ($show_payrolls->count() > 0) {
    
           $output .= '<table class="leave-tbl table" style="width: 100%" id="show_all_payroll">
           <thead>
               <tr>
                   <th hidden></th>
               </tr>
           </thead>
           <tbody>';
           foreach ($show_payrolls as $payroll) {
               $output .=
                           '<tr>
                               <td>
                                   <div class="card time-card">
                                       <div class="card-body">
                                           <div class="row">
                                               <div class="col-xl-0" >
                                                   <div class="d-flex">
                                                       <p class="" hidden>'.$payroll -> created_at.'</p>
                                                   </div>
                                               </div>';
                                   $output .= '<div class="col-xl-3">
                                                   <h5 class="name">'.$payroll -> employee_name .'</h5>
                                                   <p class="emp-no"> Employee Name</p>
                                               </div>
                                               <div class="col-xl-2">
                                                   <h5 class="name">'.Carbon::parse($payroll -> start_date)->toFormattedDateString().'</h5>
                                                   <p class="emp-no"> Start Date</p>
                                               </div>
                                               <div class="col-xl-2">
                                                   <h5 class="name">'.Carbon::parse($payroll -> end_date)->toFormattedDateString().'</h5>
                                                   <p class="emp-no"> End Date</p>
                                               </div>
    
                                               <div class="col-xl-2">
                                                   <h5 class="name">'.Carbon::parse($payroll -> created_at)->toFormattedDateString().'</h5>
                                                   <p class="emp-no"> Payslip Created</p>
                                               </div>
    
                                               <div class="col-xl-3 d-flex justify-content-end align-items-center">
                                                   <a href="#" id="' . $payroll -> id . '" type="button" class="btn btn-success btn-sm mx-1 modalpayslip" data-bs-toggle="modal" data-bs-target="#payslip">
                                                       View Payslip
                                                   </a>
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
    //update password
    public function update_password(Request $request)
    {
        $validator = \Validator::make($request -> all(),[
            'new_password' => 'required|min:8|max:20',
            'confirm_password' => 'required|same:new_password',
        ]);

        if($validator -> fails())
        {
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }
        else
        {

            $user_password = User::find($request -> usern_id);
            $user_password->password = Hash::make($request-> new_password);
            $user_password->update();
            return response()->json([
            'status' => 200,
            'msg' => 'Password Update Successfully',
            ]);
        }
    }
}
