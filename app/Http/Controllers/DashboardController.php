<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\LeaveReq;
use App\Models\Overtime;
use App\Models\Attendance;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        // $all_employees = Employee::count();
        // $total_absent = Attendance::where('absent', true)->count();

        // $ovetime_request = Overtime::where('isApproved_HR', '=', '0')
        //                             ->count();

        // $leave_request = LeaveReq::where('is_HR_Approved', '=', '0')
        //                          ->count();

        // // $employees = DB::select(DB::raw("SELECT COUNT(*) as total_department, employee_department FROM employees GROUP BY employee_department"));
        // $employees = Employee::selectRaw('COUNT(*) as total_department, employee_department')
        //               ->groupBy('employee_department')
        //               ->get();

        // $data = "";
        // foreach($employees as $val){
        //     $data .= "['".$val -> employee_department."', ".$val -> total_department."],";
        // }
        // $ChartData = $data;

        // // $employee_shift = DB::select(DB::raw("SELECT COUNT(*) as shift, employee_shift FROM employees GROUP BY employee_shift"));
        // $employee_shift = Employee::selectRaw('COUNT(*) as shift, employee_shift')
        //                     ->groupBy('employee_shift')
        //                     ->get();

        // $value = "";
        // foreach($employee_shift as $val){
        //     $value .= "['".$val -> employee_shift."', ".$val -> shift."],";

        // }

        // $chart = $value;

        // return view('dashboard',compact('ChartData', 'chart','all_employees','total_absent',));
        if(Auth::user()->hasRole(['HR','assistantHR','CEO'])){
            return view('dashboard');
        }
        else{
           abort(403);
        }
        
   }
   public function count1()
    {
        $count = Employee::count();
        $clock_in = Attendance::whereDate('created_at', Carbon::today())
                                ->where('time_in','!=', null)
                                ->count();

        $late2 = Attendance::whereDate('created_at', Carbon::today())
                                ->where('late_hours', '!=', null)
                                ->count();

        $out = Attendance::whereDate('created_at', Carbon::today())
                            ->with('employee')
                            ->where('time_out', '!=', null)
                            ->count();

       $overtime = Overtime::where('isApproved_HR', false)->count();
                            
                           

        $departments = Employee::selectRaw('COUNT(*) as count, employee_department')
                            ->groupBy('employee_department')
                            ->get();

        return response()->json(['count'=>$count, 'clock_in'=>$clock_in, 'late2'=>$late2, 'out'=>$out, 'departments'=>$departments, 'overtime' => $overtime]);
    }

    public function index2()
    {

        $employees2 = Employee::whereMonth('employee_birthday', Carbon::now()->month)
                        ->orderByRaw('DAY(employee_birthday)')
                        ->get();

        return response()->json($employees2);
    }

    public function index3()
    {
        $clock_in2 = Attendance::with('employee')
                                ->whereDate('created_at', Carbon::today())
                                ->where('time_in','!=', null)
                                ->get();

        return response()->json($clock_in2);
    }
    public function index4()
    {
        $late_emp = Attendance::with('employee')
                                ->whereDate('created_at', Carbon::today())
                                ->where('late_hours','!=', null)
                                ->get();

        return response()->json($late_emp);
    }

    public function employee_bday()
    {
        $currentMonth = Carbon::now()->month;

        $bday = DB::table('employees')
                ->whereRaw('MONTH(employee_birthday) = ?', [$currentMonth])
                ->count();

        return response()->json(['bday'=>$bday]);
    }
}
