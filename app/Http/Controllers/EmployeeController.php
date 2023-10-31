<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\LeaveReq;
use App\Models\Overtime;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        if(Auth::user()->hasRole(['administrator','HR','assistantHR','CEO'])){

            return view('employees.index');
        }
        else{

            return back();
        }

    }

    public function add_employee_user(Request $request){

        $validator = \Validator::make($request -> all(),[


            // Validate Creating User
            'employee_name' => 'required',
            'username'   => 'required|unique:users',
            'email' => 'required|email|string|unique:users',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
            'position' => 'required',
            'role' => 'required',
            

            // Validate Creating Employee
            'employee_no'   => 'required|unique:employees',
            'base_salary' => 'required',
            'employee_shift' => 'required',
            // 'daily_rate' => 'required',
            'monthly_rate' => 'required',
            'employee_department' => 'required',
            'sched_start' => 'required',
            'sched_end' => 'required',
            'work_days' => 'required',
            'date_hired' => 'required',
            'employee_birthday' => 'required',
            // 'sss' => 'required',
            // 'pagibig' => 'required',
            // 'philhealth' => 'required',
            'employee_address' => 'required',
            'employee_allowance' => 'required',
            'employee_contact_number' => 'required',
            'emergency_contact_name' => 'required',
            'emergency_contact_number' => 'required',
            'breaktime_start' => 'required',
            'breaktime_end' => 'required',


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
            // Create User
            $user = new User();
            $user -> name = $request -> employee_name;
            $user -> username = $request -> username;
            $user -> department = $request -> employee_department;
            $user -> position = $request -> position;
            $user -> email = $request -> email;
            $user -> password = Hash::make($request -> password);
            $user -> role = $request -> role;

            if($request -> hasfile('image'))
            {
                $file = $request->file('image');
                $extension = $file -> getClientOriginalExtension();
                $filename = time() . '.' .$extension;
                $file->storeAs('public/user/images', $filename);
                $user -> profile_image = $filename;
            }

            $user ->save();
            $user->attachRole($request -> role); // Attach role to user
            
            // Rate
            $base_salary = $request -> base_salary;
            $rate = $base_salary / 22;

            // Create Employee
            $employee = new Employee();
            $employee -> user_id = $user->id; //get id of user
            $employee -> qr = $request -> employee_no;
            $employee -> employee_name = $request -> employee_name;
            $employee -> employee_shift = $request -> employee_shift;
            $employee -> base_salary = $request -> base_salary;
            $employee -> monthly_rate = $request -> monthly_rate; 
            $employee -> daily_rate = $rate;
            $employee -> employee_no = $request -> employee_no;
            $employee -> employee_position = $request -> position;
            $employee -> employee_department = $request -> employee_department;
            $employee -> sched_start = $request -> sched_start;
            $employee -> sched_end = $request -> sched_end;
            $employee -> work_days = implode(',', $request -> work_days);
            $employee -> breaktime_start = $request -> breaktime_start;
            $employee -> breaktime_end = $request -> breaktime_end;
            $employee -> date_hired = $request -> date_hired;
            $employee -> employee_birthday = $request -> employee_birthday;
            $employee -> employee_address = $request -> employee_address;
            $employee -> sss_number = $request -> sss;
            $employee -> pagibig_number = $request -> pagibig;
            $employee -> philhealth_number = $request -> philhealth;
            $employee -> employee_allowance = $request -> employee_allowance;
            $employee -> employee_contact_number = $request -> employee_contact_number;
            $employee -> emergency_contact_name = $request -> emergency_contact_name;
            $employee -> emergency_contact_number = $request -> emergency_contact_number;

            if($request -> hasfile('image'))
            {

                $file = $request->file('image');
                $extension = $file -> getClientOriginalExtension();
                $filename = time() . '.' .$extension;
                $file->storeAs('public/employee/images', $filename);

                $employee -> image = $filename;
            }

            $employee -> save();
            return response()->json([
                'code' => 200,
                'msg' => 'Employee Added Successfully',
            ]);
        }
    }

    public function fetch_all(){

        $emps = Employee::get(['id','employee_name','employee_no','employee_position','employee_department','qr','created_at','image','base_salary','employee_shift']);
		$output = '';
		if ($emps->count() > 0) {

			$output .= '<table class="employee-tbl table" style="width: 100%" id="employee_table">

            <thead>
              <tr>
                <th hidden></th>
              </tr>
            </thead>
            <tbody>';

			foreach ($emps as $emp)
            {
                $output .= ' <tr>
                                <td>
                                    <div class="card time-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xl-2" >
                                                    <div class="d-flex">


                                                        <p class="emp_id" hidden>'.$emp -> id.'</p>';

                                                        if($emp -> image != null)
                                                        {

                                                            $output .=  '<img src="storage/employee/images/' . $emp->image . '" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:50px; height: 50px;">';

                                                        }
                                                        else
                                                        {

                                                            $output .= '<i class="bx bx-user icon profile-picture-time"> </i>';
                                                        }

                                                    $output .='  </div>

                                                </div>

                                                <div class="col-xl-3">
                                                    <h5 class="emp-name">'.$emp->employee_name .'</h5>
                                                    <p class="emp-no">'.$emp -> employee_no.'</p>

                                                </div>
                                                <div class="col-xl-3 td-div">
                                                    <p class="name">'.$emp -> employee_position.'</p>
                                                    <p class="department">'.$emp -> employee_department.'</p>
                                                </div>

                                                <div class="col-xl-4 d-flex justify-content-end">

                                                    <a href="#" type="button" id="' .$emp -> id.'" class="btn-view editQr" data-tippy-content="View QR Code" data-tippy-arrow="false" data-bs-toggle="modal" data-bs-target="#QrCode">
                                                    <i class="bx bx-qr"></i>
                                                    </a>';
                                                if(Auth::user()->hasRole(['HR','assistantHR'])){
                                                    $output .='<a href="#" type="button" id="' .$emp -> id.'" class="btn-view editIcon" data-tippy-content="View Profile" data-tippy-arrow="false" data-bs-toggle="modal" data-bs-target="#EditEmployee">
                                                                    <i class="bx bx-edit"></i>
                                                                </a>

                                                                <a href="#" type="button" id="' .$emp -> id.'" class="btn-view deleteIcon" data-tippy-content="Delete Profile" data-tippy-arrow="false" data-bs-toggle="modal" data-bs-target="#DeleteEmployee">
                                                                <i class="bx bx-trash"></i>
                                                                </a>';
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
    //edit employee
    public function edit_employee(Request $request)
    {
		$id = $request->id;
		$emp = Employee::find($id);
		return response()->json($emp);
	}
    //view qr
    public function edit_qr(Request $request)
    {
		$idd = $request->id;
		$empd = Employee::find($idd);
		return response()->json($empd);
	}

    // handle update an employee ajax request
	public function update(Request $request)
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
    //handle delete an employee ajax request
	public function delete(Request $request) {
		$id = $request->id;
		$emp = Employee::find($id);
        $users = User::find($id);


      
       
        if (Storage::delete('public/employee/images/' . $emp->image)) {

			Employee::destroy($id);
		}
        else{

            Employee::destroy($id);

        }
    
        if (Storage::delete('public/user/images/' . $users->profile_image)) {

			User::destroy($id);
		}
        else{
            
            User::destroy($id);
        }
        
	}
 }
