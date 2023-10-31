<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Attendance;
use App\Models\SSS_deduction;
use App\Models\Philhealth_deduction;
use App\Models\Pagibig_deduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index()
    {

        if(Auth::user()->hasRole(['accounting','HR','assistantHR','CEO']))
        {

            $employees = Employee::get(['employee_name','id']);
            return view('payroll.index', compact('employees'));

        }
        else{

           return redirect()->back();
        }

    }
    public function view_employee(Request $request){

		$view_emp = Employee::find($request -> id);
		return response()->json($view_emp);
    }

    public function submit_payroll(Request $request){

        $validator = \Validator::make($request -> all(),[

            'start_date' => 'required',
            'end_date' => 'required',
            'employee_name' => 'required',
            'payment_type' => 'required',
            // 'allowance' => 'required',
            'pay_date' => 'required',
            'cash_advance' => 'required',
            // 'special_day' => 'required',
            // 'regular_day' => 'required',

        ]);

        if($validator -> fails()){

            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }
        else{

            $sss_deduction = SSS_deduction::all();
            $philhealth_deduction = Philhealth_deduction::all();
            $pagibig_deduction = Pagibig_deduction::all();
 
            $fromdate = $request->input('start_date');
            $todate = $request->input('end_date');

            //Work hour of employee with no holiday and restday
            $employee_workhour = DB::table('attendances')
                                    ->where('emp_no', '=', $request -> employee_number)
                                    ->where('RD', false)
                                    ->where('RDSH', false)
                                    ->where('RDRH', false)
                                    ->where('SH', false)
                                    ->where('RH', false)
                                    ->where('RDND', false)
                                    ->where('SHND', false)
                                    ->where('RHND', false)
                                    ->where('RDSHND', false)
                                    ->where('RDRHND', false)
                                    ->where('date', '>=', $fromdate)
                                    ->where('date', '<=', $todate)
                                    ->sum(DB::raw("TIME_TO_SEC(work_hours)")); // Calcuting the total hours and convert it to seconds
            $total_work_hour = gmDate("H:i:s", $employee_workhour); // Convert sec to formatted Hour Min Sec

            //Rest Day work hours 
            $RD = DB::table('attendances')
                ->where('emp_no', '=', $request -> employee_number)
                ->where('RD', true)
                ->where('RDSH', false)
                ->where('RDRH', false)
                ->where('SH', false)
                ->where('RH', false)
                ->where('RDND', false)
                ->where('SHND', false)
                ->where('RHND', false)
                ->where('RDSHND', false)
                ->where('RDRHND', false)
                ->where('date', '>=', $fromdate)
                ->where('date', '<=', $todate)
                ->sum(DB::raw("TIME_TO_SEC(work_hours)")); // Calcuting the total hours and convert it to seconds
            $RD_total_work_hour = gmDate("H", $RD); 
            $RD_min = gmDate("i", $RD);

            //Restday special holiday
            $RDSH_work_hrs = DB::table('attendances')
                                ->where('emp_no', '=', $request -> employee_number)
                                ->where('RD', false)
                                ->where('RDSH', true)
                                ->where('RDRH', false)
                                ->where('SH', false)
                                ->where('RH', false)
                                ->where('RDND', false)
                                ->where('SHND', false)
                                ->where('RHND', false)
                                ->where('RDSHND', false)
                                ->where('RDRHND', false)
                                ->where('date', '>=', $fromdate)
                                ->where('date', '<=', $todate)
                                ->sum(DB::raw("TIME_TO_SEC(work_hours)")); // Calcuting the total hours and convert it to seconds
            $RDSH_total_work_hour = gmDate("H", $RDSH_work_hrs); // Convert sec to formatted Hour Min Sec
            $RDSH_min = gmDate("i", $RDSH_work_hrs);

            //restday regular holiday 
            $RDRH_work_hrs = DB::table('attendances')
                                ->where('emp_no', '=', $request -> employee_number)
                                ->where('RD', false)
                                ->where('RDSH', false)
                                ->where('RDRH', true)
                                ->where('SH', false)
                                ->where('RH', false)
                                ->where('RDND', false)
                                ->where('SHND', false)
                                ->where('RHND', false)
                                ->where('RDSHND', false)
                                ->where('RDRHND', false)
                                ->where('date', '>=', $fromdate)
                                ->where('date', '<=', $todate)
                                ->sum(DB::raw("TIME_TO_SEC(work_hours)")); // Calcuting the total hours and convert it to seconds
            $RDRH_total_work_hour = gmDate("H", $RDRH_work_hrs); // Convert sec to formatted Hour Min Sec
            $RDRH_min = gmDate("i", $RDRH_work_hrs);

            //special holiday
            $SH_work_hrs = DB::table('attendances')
                                ->where('emp_no', '=', $request -> employee_number)
                                ->where('RD', false)
                                ->where('RDSH', false)
                                ->where('RDRH', false)
                                ->where('SH', true)
                                ->where('RH', false)
                                ->where('RDND', false)
                                ->where('SHND', false)
                                ->where('RHND', false)
                                ->where('RDSHND', false)
                                ->where('RDRHND', false)
                                ->where('date', '>=', $fromdate)
                                ->where('date', '<=', $todate)
                                ->sum(DB::raw("TIME_TO_SEC(work_hours)")); // Calcuting the total hours and convert it to seconds
            $SH_total_work_hour = gmDate("H", $SH_work_hrs); // Convert sec to formatted Hour Min Sec
            $SH_min = gmDate("i", $SH_work_hrs);

            //Regular holiday
            $RH_work_hrs = DB::table('attendances')
                            ->where('emp_no', '=', $request -> employee_number)
                            ->where('RD', false)
                            ->where('RDSH', false)
                            ->where('RDRH', false)
                            ->where('SH', false)
                            ->where('RH', true)
                            ->where('RDND', false)
                            ->where('SHND', false)
                            ->where('RHND', false)
                            ->where('RDSHND', false)
                            ->where('RDRHND', false)
                            ->where('date', '>=', $fromdate)
                            ->where('date', '<=', $todate)
                            ->sum(DB::raw("TIME_TO_SEC(work_hours)")); // Calcuting the total hours and convert it to seconds
            $RH_total_work_hour = gmDate("H", $RH_work_hrs); // Convert sec to formatted Hour Min Sec
            $RH_min = gmDate("i", $RH_work_hrs);


            //late
            $employee_latehours = DB::table('attendances')
                                    ->where('emp_no', '=', $request -> employee_number)
                                    ->where('date', '>=', $fromdate)
                                    ->where('date', '<=', $todate)
                                    ->sum(DB::raw("TIME_TO_SEC(late_hours)")); // Calcuting the total hours and convert it to seconds
            $total_latehours = gmDate("H", $employee_latehours); // Convert sec to formatted Hour Min Sec
            $late_min = gmDate("i", $employee_latehours);
            //Undertime
            $employee_undertime = DB::table('attendances')
                                ->where('emp_no', '=', $request -> employee_number)
                                ->where('date', '>=', $fromdate)
                                ->where('date', '<=', $todate)
                                ->sum(DB::raw("TIME_TO_SEC(undertime_hours)")); // Calcuting the total hours and convert it to seconds
            $total_undertime = gmDate("H", $employee_undertime); // Convert sec to formatted Hour Min Sec
            $undertime_min = gmDate("i", $employee_undertime);

            //night_diff
            $ten_percent_night = DB::table('attendances')
                                ->where('emp_no', '=',$request -> employee_number)
                                ->where('date', '>=', $fromdate)
                                ->where('date', '<=', $todate)
                                ->sum(DB::raw("TIME_TO_SEC(night_diff_hours)"));
            $ten_percent_addional = gmDate("H", $ten_percent_night);


            //Absent
            $absent = DB::table('attendances')
                        ->where('emp_no', '=', $request -> employee_number)
                        ->where('date', '>=', $fromdate)
                        ->where('date', '<=', $todate)
                        ->where('status', '=', 'absent')
                        ->count();

                                
            
            // Normal Overtime
            $employee_OT = DB::table('overtimes')
                            ->where('emp_number', '=', $request -> employee_number)
                            ->where('RDOT', false)
                            ->where('SHOT', false)
                            ->where('RHOT', false)
                            ->where('RDSHOT', false)
                            ->where('RDRHOT', false)
                         //   ->where('isApproved_TL', true)
                            ->where('isApproved_HR', true)
                         //   ->where('isApproved_SVP', true)
                            ->whereDate('created_at', '>=', $fromdate)
                            ->whereDate('created_at', '<=', $todate)
                            ->sum(DB::raw("TIME_TO_SEC(hours_OT)")); // Calcuting the total hours and convert it to seconds
            $total_overtime = gmDate("H", $employee_OT); // Convert sec to formatted Hour Min Sec


            //Rest day Over time
            $RDOT = DB::table('overtimes')
                    ->where('emp_number', '=', $request -> employee_number)
                    ->where('RDOT', '=', true)
                    ->where('SHOT', '=', false)
                    ->where('RHOT', '=', false)
                    ->where('RDSHOT', '=', false)
                    ->where('RDRHOT', '=', false)
                  //  ->where('isApproved_TL', true)
                    ->where('isApproved_HR', true)
                  //  ->where('isApproved_SVP', true)
                    ->whereDate('created_at', '>=', $fromdate)
                    ->whereDate('created_at', '<=', $todate)
                    ->sum(DB::raw("TIME_TO_SEC(hours_OT)")); // Calcuting the total hours and convert it to seconds
            $RDOT_hrs = gmDate("H", $RDOT); // Convert sec to formatted Hour Min Sec

            //Special holiday Overtime
            $SHOT = DB::table('overtimes')
                    ->where('emp_number', '=', $request -> employee_number)
                    ->where('RDOT', '=', false)
                    ->where('SHOT', '=', true)
                    ->where('RHOT', '=', false)
                    ->where('RDSHOT', '=', false)
                    ->where('RDRHOT', '=', false)
                  //  ->where('isApproved_TL', true)
                    ->where('isApproved_HR', true)
                   // ->where('isApproved_SVP', true)
                    ->whereDate('created_at', '>=', $fromdate)
                    ->whereDate('created_at', '<=', $todate)
                    ->sum(DB::raw("TIME_TO_SEC(hours_OT)")); // Calcuting the total hours and convert it to seconds
            $SHOT_hrs = gmDate("H", $SHOT); // Convert sec to formatted Hour Min Sec

            //Regular holiday  overtime
            $RHOT = DB::table('overtimes')
                    ->where('emp_number', '=', $request -> employee_number)
                    ->where('RDOT', '=', false)
                    ->where('SHOT', '=', false)
                    ->where('RHOT', '=', true)
                    ->where('RDSHOT', '=', false)
                    ->where('RDRHOT', '=', false)
                  //  ->where('isApproved_TL', true)
                    ->where('isApproved_HR', true)
                  //  ->where('isApproved_SVP', true)
                    ->whereDate('created_at', '>=', $fromdate)
                    ->whereDate('created_at', '<=', $todate)
                    ->sum(DB::raw("TIME_TO_SEC(hours_OT)")); // Calcuting the total hours and convert it to seconds
            $RHOT_hrs = gmDate("H", $RHOT);


            // Rest Day special holiday overtime
            $RDSHOT = DB::table('overtimes')
                    ->where('emp_number', '=', $request -> employee_number)
                    ->where('RDOT', '=', false)
                    ->where('SHOT', '=', false)
                    ->where('RHOT', '=', false)
                    ->where('RDSHOT', '=', true)
                    ->where('RDRHOT', '=', false)
                   // ->where('isApproved_TL', true)
                    ->where('isApproved_HR', true)
                    // ->where('isApproved_SVP', true)
                    ->whereDate('created_at', '>=', $fromdate)
                    ->whereDate('created_at', '<=', $todate)
                    ->sum(DB::raw("TIME_TO_SEC(hours_OT)")); // Calcuting the total hours and convert it to seconds
            $RDSHOT_hrs = gmDate("H", $RDSHOT);


            //Rest day regular holiday overtime
            $RDRHOT = DB::table('overtimes')
                    ->where('emp_number', '=', $request -> employee_number)
                    ->where('RDOT', '=', false)
                    ->where('SHOT', '=', false)
                    ->where('RHOT', '=', false)
                    ->where('RDSHOT', '=', false)
                    ->where('RDRHOT', '=', true)
                    //->where('isApproved_TL', true)
                    ->where('isApproved_HR', true)
                   // ->where('isApproved_SVP', true)
                    ->whereDate('created_at', '>=', $fromdate)
                    ->whereDate('created_at', '<=', $todate)
                    ->sum(DB::raw("TIME_TO_SEC(hours_OT)")); // Calcuting the total hours and convert it to seconds
            $RDRHOT_hrs = gmDate("H", $RDRHOT);
            

            
           if($request -> schedule_shift == 'Day'){

                if($request -> monthly_rate == "Fixed Rate"){

                    $payroll = new Payroll();
                    $payroll -> employee_name = $request -> employee_name;
                    $payroll -> payment_type = $request -> payment_type;
                    $payroll -> employee_number = $request -> employee_number;
                    $payroll -> start_date = $request -> start_date;
                    $payroll -> end_date = $request -> end_date;
                    $payroll -> pay_date = $request -> pay_date;
                    $payroll -> allowance = $request -> allowance;
                    $payroll -> rate_per_hour = $request -> rate_per_hour;
                    $payroll -> cash_advance = $request -> cash_advance;
                    $payroll -> employee_base_salary = $request -> base_salary / 2;
                    $payroll -> night_diff = 0.00;
                    $payroll -> late_undertime = 0.00;
                    $payroll -> total_overtime = 0.00;
                    $payroll -> sss_deduction = 0.00;
                    $payroll -> pag_ibig_deduction = 0.00;
                    $payroll -> philhealth_deduction = 0.00;
                    $payroll -> employee_absent = 0.00;
                    $payroll -> restday = 0.00;
                    $payroll -> gov_contribution = 0.00;
                    $payroll -> total_deduction = 0.00   +  $payroll -> cash_advance;
                    $payroll -> gross =  $payroll -> employee_base_salary +   $payroll -> allowance;
                  //  $payroll -> net_pay =  ($payroll -> employee_base_salary  +   $payroll -> allowance) - $payroll -> total_deduction; 
                    $payroll -> net_pay = $payroll -> gross - $payroll -> total_deduction;

                    $payroll -> save();

                    return response()->json([
                            'status' => 200,
                            'msg' => 'Payroll Successfully',
                    ]);
                }
                else{   
                    
                    //May Kaltas
                    
                    //------------------ late Computation -----------------------------------------------//
                    $late_total = ($total_latehours * 60) + $late_min;
                    $late = $late_total / 60;
                    $computed_late = $late * $request -> rate_per_hour * 1; //Computed Salary

                    //------------------ late Computation -----------------------------------------------//
                    $undertime_total = ($total_undertime * 60) + $undertime_min;
                    $undertime = $undertime_total / 60;
                    $computed_undertime = $undertime * $request -> rate_per_hour * 1; //Computed Salary

                    //------------------ Restday Computation -----------------------------------------------//
                    $restday_total_MinutesWorked = ($RD_total_work_hour * 60) + $RD_min;
                    $restday_hoursWorked = $restday_total_MinutesWorked / 60;
                    $restday_salary = $restday_hoursWorked * $request -> rate_per_hour * 1.3; //Computed Salary
                    
                    //------------------ Rest Day and Special Holiday Computation ------------------------//
                    $RDSH_total_MinutesWorked = ($RDSH_total_work_hour * 60) + $RDSH_min;
                    $RDSH_hoursWorked = $RDSH_total_MinutesWorked / 60;
                    $RDSH_salary = $RDSH_hoursWorked * $request -> rate_per_hour * 1.5;


                    //------------------ Rest Day Regular Holiday -------------------------------------//
                    $RDRH_total_MinutesWorked = ($RDRH_total_work_hour * 60) + $RDRH_min;
                    $RDRH_hoursWorked = $RDRH_total_MinutesWorked / 60;
                    $RDRH_salary = $RDRH_hoursWorked * $request -> rate_per_hour * 2.6;


                    //------------------ Special Holiday -------------------------------------//
                    $SH_total_MinutesWorked = ($SH_total_work_hour * 60) + $SH_min;
                    $SH_hoursWorked = $SH_total_MinutesWorked / 60;
                    $SH_salary = $SH_hoursWorked * $request -> rate_per_hour * 0.3;
                    
                    //------------------ Regular Holiday -------------------------------------//
                    $RH_total_MinutesWorked = ($RH_total_work_hour * 60) + $RH_min;
                    $RH_hoursWorked = $RH_total_MinutesWorked / 60;
                    $RH_salary = $RH_hoursWorked * $request -> rate_per_hour * 1;

                    //------------------------------ Natural na Overtime  ----------------------------//
                    $total_overtime = ($total_overtime * $request -> rate_per_hour) * 1.25;
                     
                    //------------------------------ Rest day Overtime ----------------------------------------- //
                    $restday_ot = ($RDOT_hrs * $request -> rate_per_hour )* 1.69;
                    
                    //------------------------------ Special Holiday overtime ---------------------------------//
                    $special_holiday_ot = ($SHOT_hrs * $request -> rate_per_hour )* 1.69;
                    
                    //------------------------------ Regular Holiday overtime ---------------------------------//
                    $regular_holiday_ot = ($RHOT_hrs * $request -> rate_per_hour )* 2.6;

                    //------------------------------ Restday Special Holiday overtime ---------------------------------//
                    $restday_special_holiday_ot = ($RDSHOT_hrs * $request -> rate_per_hour )* 1.95;

                    //------------------------------ Restday Regular Holiday overtime ---------------------------------//
                    $restday_regular_holiday_ot = ($RDRHOT_hrs * $request -> rate_per_hour )* 3.38;



                    //Calculation for allowance
                   
                    $allowance_per_day =  $request -> allowance  / 11;  // makukuha an daily allowance
                    $total_absent = $absent * $allowance_per_day; // total absent multiply by total allowance
                    $total_allowance =  $request -> allowance  -  $total_absent; // to get allowance = allowance per cut  then subract the total absent


               


                    $payroll = new Payroll();
                    $payroll -> employee_name = $request -> employee_name;
                    $payroll -> payment_type = $request -> payment_type;
                    $payroll -> employee_number = $request -> employee_number;
                    $payroll -> start_date = $request -> start_date;
                    $payroll -> end_date = $request -> end_date;
                    $payroll -> pay_date = $request -> pay_date;
                    $payroll -> rate_per_hour = $request -> rate_per_hour;
                    $payroll -> cash_advance = $request -> cash_advance;
                    $payroll -> employee_base_salary = $request -> base_salary / 2;
                    $payroll -> night_diff = 0.00;
                    $payroll -> employee_absent = $absent * $request -> daily_rate;
                    $payroll -> allowance = $total_allowance;
                    $payroll -> undertime_total =  $computed_undertime;
                    $payroll -> late_total = $computed_late;
                    $payroll -> late_undertime = $computed_late + $computed_undertime;
                    $payroll -> restday = $restday_salary;
                    $payroll -> special_holiday = $SH_salary;
                    $payroll -> regular_holiday = $RH_salary;
                    $payroll -> restday_regular_holiday = $RDRH_salary;
                    $payroll -> restday_special_holiday = $RDSH_salary;
                    $payroll -> overtime = $total_overtime;
                    $payroll -> restday_overtime = $restday_ot;
                    $payroll -> special_holiday_overtime = $special_holiday_ot;
                    $payroll -> regular_holiday_overtime = $regular_holiday_ot;
                    $payroll -> restday_special_holiday_overtime = $restday_special_holiday_ot;
                    $payroll -> restday_regular_holiday_overtime = $restday_regular_holiday_ot;
                   
                    
                    $payroll -> gross =  $payroll -> employee_base_salary +  $payroll -> allowance +  $payroll -> total_overtime +  $payroll -> restday  + $SH_salary + $RH_salary + $RDRH_salary + $RDSH_salary + 
                                         $payroll -> overtime +  $payroll -> restday_overtime +  $payroll -> special_holiday_overtime +  $payroll -> regular_holiday_overtime +  $payroll -> restday_special_holiday_overtime + $payroll -> restday_regular_holiday_overtime;
                   
                    // SSS Deduction
                    if($sss_deduction -> isNotEmpty()){

                        foreach($sss_deduction as $deduction){

                            if ($payroll -> employee_base_salary >= $deduction->from && $payroll -> employee_base_salary >= $deduction->to) {

                                $payroll -> sss_deduction = $deduction -> regular_EE / 2;
                              //  break; // Exit the loop after the first deduction that matches
                            }
                            elseif ($payroll -> employee_base_salary >= $deduction->from && $payroll -> employee_base_salary <= $deduction->to) {

                                $payroll -> sss_deduction = $deduction -> regular_EE / 2;
                              //  break; // Exit the loop after the first deduction that matches
                            }
                           
                        }
                    }
                    else{
                        $payroll -> sss_deduction = 0.00;
                    }


                    // Philhealth Deduction
                    if($philhealth_deduction -> isNotEmpty()){

                        foreach($philhealth_deduction as $phil_deduction){
                            
                            if($payroll -> employee_base_salary >= $phil_deduction->monthly_basic_salary_from && $payroll -> employee_base_salary >= $phil_deduction->monthly_basic_salary_to){

                                $payroll -> philhealth_deduction =  $payroll -> employee_base_salary * $phil_deduction -> premium_rate / 2;
                            }
                            elseif($payroll -> employee_base_salary >= $phil_deduction->monthly_basic_salary_from && $payroll -> employee_base_salary <= $phil_deduction->monthly_basic_salary_to) {

                                $payroll -> philhealth_deduction =  $payroll -> employee_base_salary * $phil_deduction -> premium_rate / 2;
                               // break; // Exit the loop after the first deduction that matches
                            }
                           
                        }
                    }
                    else{
                        $payroll -> philhealth_deduction = 0.00;
                    }


                    // Pag ibig Deduction
                    if($pagibig_deduction -> isNotEmpty()){

                        foreach($pagibig_deduction as $pagibig_deduct){

                         
                            if ($payroll -> employee_base_salary >= $pagibig_deduct->monthly_salary_from && $payroll -> employee_base_salary <= $pagibig_deduct->monthly_salary_to) {

                                $payroll -> pag_ibig_deduction =  $pagibig_deduct -> employees_share / 2;

                                $payroll -> total_deduction =  $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction + $payroll -> late_undertime + $payroll -> cash_advance + $payroll -> employee_absent;
                                $payroll -> gov_contribution = $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction;
                                $payroll -> net_pay =   $payroll -> net_pay = $payroll -> gross - $payroll -> total_deduction;
                                break; // Exit the loop after the first deduction that matches
   
                            }
                            elseif ($payroll -> employee_base_salary >= $pagibig_deduct->monthly_salary_from && $payroll -> employee_base_salary >= $pagibig_deduct->monthly_salary_to) {

                                $payroll -> pag_ibig_deduction =  $pagibig_deduct -> employees_share / 2;

                                $payroll -> total_deduction =  $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction + $payroll -> late_undertime + $payroll -> cash_advance + $payroll -> employee_absent;
                                $payroll -> gov_contribution = $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction;
                                $payroll -> net_pay =   $payroll -> net_pay = $payroll -> gross - $payroll -> total_deduction;
                                break; // Exit the loop after the first deduction that matches
                               
                            }
                            
                        }
                    }
                    else{

                         $payroll -> pag_ibig_deduction = 0.00;
                         $payroll -> total_deduction =  $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction + $payroll -> late_undertime + $payroll -> cash_advance + $payroll -> employee_absent;
                         $payroll -> gov_contribution = $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction;
                         $payroll -> net_pay =   $payroll -> net_pay = $payroll -> gross - $payroll -> total_deduction;
                    }
                    
                
                    $payroll -> save();
                    return response()->json([
                            'status' => 200,
                            'msg' => 'Payroll Successfully',
                    ]);

                }
           }    

           //--------------------- Night SHIFT HERE ------------------------------------------
           else{

                if($request -> monthly_rate == "Fixed Rate"){

                    $payroll = new Payroll();
                    $payroll -> employee_name = $request -> employee_name;
                    $payroll -> payment_type = $request -> payment_type;
                    $payroll -> employee_number = $request -> employee_number;
                    $payroll -> start_date = $request -> start_date;
                    $payroll -> end_date = $request -> end_date;
                    $payroll -> pay_date = $request -> pay_date;
                    $payroll -> allowance = $request -> allowance;
                    $payroll -> rate_per_hour = $request -> rate_per_hour;
                    $payroll -> cash_advance = $request -> cash_advance;
                    $payroll -> employee_base_salary = $request -> base_salary / 2;
                    $payroll -> night_diff = 0.00;
                    $payroll -> late_undertime = 0.00;
                    $payroll -> total_overtime = 0.00;
                    $payroll -> sss_deduction = 0.00;
                    $payroll -> pag_ibig_deduction = 0.00;
                    $payroll -> philhealth_deduction = 0.00;
                    $payroll -> employee_absent = 0.00;
                    
                    // $payroll -> restday = 0.00;
                    $payroll -> total_deduction = 0.00 + $payroll -> cash_advance;
                    $payroll -> gross =  $payroll -> employee_base_salary +   $payroll -> allowance;
                    $payroll -> net_pay = $payroll -> gross - $payroll -> total_deduction;

                    $payroll -> save();

                    return response()->json([
                            'status' => 200,
                            'msg' => 'Payroll Successfully',
                    ]);
                }
                else{

                    //May Kaltas
                    
                    //------------------ late Computation -----------------------------------------------//
                    $late_total = ($total_latehours * 60) + $late_min;
                    $late = $late_total / 60;
                    $computed_late = $late * $request -> rate_per_hour * 1; //Computed Salary

                    //------------------ late Computation -----------------------------------------------//
                    $undertime_total = ($total_undertime * 60) + $undertime_min;
                    $undertime = $undertime_total / 60;
                    $computed_undertime = $undertime * $request -> rate_per_hour * 1; //Computed Salary

                    //------------------ Restday Computation -----------------------------------------------//
                    $restday_total_MinutesWorked = ($RD_total_work_hour * 60) + $RD_min;
                    $restday_hoursWorked = $restday_total_MinutesWorked / 60;
                    $restday_salary = $restday_hoursWorked * $request -> rate_per_hour * 1.3; //Computed Salary
                    
                    //------------------ Rest Day and Special Holiday Computation ------------------------//
                    $RDSH_total_MinutesWorked = ($RDSH_total_work_hour * 60) + $RDSH_min;
                    $RDSH_hoursWorked = $RDSH_total_MinutesWorked / 60;
                    $RDSH_salary = $RDSH_hoursWorked * $request -> rate_per_hour * 1.5;


                    //------------------ Rest Day Regular Holiday -------------------------------------//
                    $RDRH_total_MinutesWorked = ($RDRH_total_work_hour * 60) + $RDRH_min;
                    $RDRH_hoursWorked = $RDRH_total_MinutesWorked / 60;
                    $RDRH_salary = $RDRH_hoursWorked * $request -> rate_per_hour * 2.6;


                    //------------------ Special Holiday -------------------------------------//
                    $SH_total_MinutesWorked = ($SH_total_work_hour * 60) + $SH_min;
                    $SH_hoursWorked = $SH_total_MinutesWorked / 60;
                    $SH_salary = $SH_hoursWorked * $request -> rate_per_hour * 0.3;
                    
                    //------------------ Regular Holiday -------------------------------------//
                    $RH_total_MinutesWorked = ($RH_total_work_hour * 60) + $RH_min;
                    $RH_hoursWorked = $RH_total_MinutesWorked / 60;
                    $RH_salary = $RH_hoursWorked * $request -> rate_per_hour * 1;

                    //------------------------------ Natural na Overtime  ----------------------------//
                    $total_overtime = ($total_overtime * $request -> rate_per_hour) * 1.25;
                     
                    //------------------------------ Rest day Overtime ----------------------------------------- //
                    $restday_ot = ($RDOT_hrs * $request -> rate_per_hour )* 1.69;
                    
                    //------------------------------ Special Holiday overtime ---------------------------------//
                    $special_holiday_ot = ($SHOT_hrs * $request -> rate_per_hour )* 1.69;
                    
                    //------------------------------ Regular Holiday overtime ---------------------------------//
                    $regular_holiday_ot = ($RHOT_hrs * $request -> rate_per_hour )* 2.6;

                    //------------------------------ Restday Special Holiday overtime ---------------------------------//
                    $restday_special_holiday_ot = ($RDSHOT_hrs * $request -> rate_per_hour )* 1.95;

                    //------------------------------ Restday Regular Holiday overtime ---------------------------------//
                    $restday_regular_holiday_ot = ($RDRHOT_hrs * $request -> rate_per_hour )* 3.38;


                    //Calculation for allowance
                   
                    $allowance_per_day =  $request -> allowance  / 11;  // makukuha an daily allowance
                    $total_absent = $absent * $allowance_per_day; // total absent multiply by total allowance
                    $total_allowance =  $request -> allowance  -  $total_absent; // to get allowance = allowance per cut  then subract the total absent


                       //Night diff with 10%
                   $ten_percent_night_differential = ($ten_percent_addional *  $request -> rate_per_hour) * 0.1;




                    $payroll = new Payroll();
                    $payroll -> employee_name = $request -> employee_name;
                    $payroll -> payment_type = $request -> payment_type;
                    $payroll -> employee_number = $request -> employee_number;
                    $payroll -> start_date = $request -> start_date;
                    $payroll -> end_date = $request -> end_date;
                    $payroll -> pay_date = $request -> pay_date;
                    $payroll -> rate_per_hour = $request -> rate_per_hour;
                    $payroll -> cash_advance = $request -> cash_advance;
                    $payroll -> employee_base_salary = $request -> base_salary / 2;
                    $payroll -> night_diff = $ten_percent_night_differential;
                    $payroll -> employee_absent = $absent * $request -> daily_rate;
                    $payroll -> allowance = $total_allowance;
                    $payroll -> undertime_total =  $computed_undertime;
                    $payroll -> late_total = $computed_late;
                    $payroll -> late_undertime = $computed_late + $computed_undertime;
                    $payroll -> restday = $restday_salary;
                    $payroll -> special_holiday = $SH_salary;
                    $payroll -> regular_holiday = $RH_salary;
                    $payroll -> restday_regular_holiday = $RDRH_salary;
                    $payroll -> restday_special_holiday = $RDSH_salary;
                    $payroll -> overtime = $total_overtime;
                    $payroll -> restday_overtime = $restday_ot;
                    $payroll -> special_holiday_overtime = $special_holiday_ot;
                    $payroll -> regular_holiday_overtime = $regular_holiday_ot;
                    $payroll -> restday_special_holiday_overtime = $restday_special_holiday_ot;
                    $payroll -> restday_regular_holiday_overtime = $restday_regular_holiday_ot;
                   
                    
                    $payroll -> gross =  $payroll -> employee_base_salary +  $payroll -> allowance + $payroll -> night_diff +  $payroll -> total_overtime +  $payroll -> restday  + $SH_salary + $RH_salary + $RDRH_salary + $RDSH_salary + 
                                         $payroll -> overtime +  $payroll -> restday_overtime +  $payroll -> special_holiday_overtime +  $payroll -> regular_holiday_overtime +  $payroll -> restday_special_holiday_overtime + $payroll -> restday_regular_holiday_overtime;
                   
                 
                    if($sss_deduction -> isNotEmpty()){

                        foreach($sss_deduction as $deduction){

                            if ($payroll -> employee_base_salary >= $deduction->from && $payroll -> employee_base_salary >= $deduction->to) {

                                $payroll -> sss_deduction = $deduction -> regular_EE / 2;
                              //  break; // Exit the loop after the first deduction that matches
                            }
                            elseif ($payroll -> employee_base_salary >= $deduction->from && $payroll -> employee_base_salary <= $deduction->to) {

                                $payroll -> sss_deduction = $deduction -> regular_EE / 2;
                              //  break; // Exit the loop after the first deduction that matches
                            }
                           
                        }
                    }
                    else{
                        $payroll -> sss_deduction = 0.00;
                    }

                    if($philhealth_deduction -> isNotEmpty()){

                        foreach($philhealth_deduction as $phil_deduction){
                            
                            if($payroll -> employee_base_salary >= $phil_deduction->monthly_basic_salary_from && $payroll -> employee_base_salary >= $phil_deduction->monthly_basic_salary_to){

                                $payroll -> philhealth_deduction =  $payroll -> employee_base_salary * $phil_deduction -> premium_rate / 2;
                            }

                            elseif($payroll -> employee_base_salary >= $phil_deduction->monthly_basic_salary_from && $payroll -> employee_base_salary <= $phil_deduction->monthly_basic_salary_to) {

                                $payroll -> philhealth_deduction =  $payroll -> employee_base_salary * $phil_deduction -> premium_rate / 2;
                               // break; // Exit the loop after the first deduction that matches
                            }
                           
                        }
                    }
                    else{
                        $payroll -> philhealth_deduction = 0.00;
                    }

                    if($pagibig_deduction -> isNotEmpty()){

                        foreach($pagibig_deduction as $pagibig_deduct){

                         
                            if ($payroll -> employee_base_salary >= $pagibig_deduct->monthly_salary_from && $payroll -> employee_base_salary <= $pagibig_deduct->monthly_salary_to) {

                                $payroll -> pag_ibig_deduction =  $pagibig_deduct -> employees_share / 2;

                                $payroll -> total_deduction =  $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction + $payroll -> late_undertime + $payroll -> cash_advance + $payroll -> employee_absent;
                                $payroll -> gov_contribution = $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction;
                                $payroll -> net_pay =   $payroll -> net_pay = $payroll -> gross - $payroll -> total_deduction;
                                break; // Exit the loop after the first deduction that matches
   
                            }
                            elseif ($payroll -> employee_base_salary >= $pagibig_deduct->monthly_salary_from && $payroll -> employee_base_salary >= $pagibig_deduct->monthly_salary_to) {

                                $payroll -> pag_ibig_deduction =  $pagibig_deduct -> employees_share / 2;

                                $payroll -> total_deduction =  $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction + $payroll -> late_undertime + $payroll -> cash_advance + $payroll -> employee_absent;
                                $payroll -> gov_contribution = $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction;
                                $payroll -> net_pay =   $payroll -> net_pay = $payroll -> gross - $payroll -> total_deduction;
                                break; // Exit the loop after the first deduction that matches
                               
                            }
                            
                        }
                    }
                    else{

                         $payroll -> pag_ibig_deduction = 0.00;
                         $payroll -> total_deduction =  $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction + $payroll -> late_undertime + $payroll -> cash_advance + $payroll -> employee_absent;
                         $payroll -> gov_contribution = $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction;
                         $payroll -> net_pay =   $payroll -> net_pay = $payroll -> gross - $payroll -> total_deduction;
                    }
                    
                
                    $payroll -> save();
                    return response()->json([
                            'status' => 200,
                            'msg' => 'Payroll Successfully',
                    ]);
                }

           }

           
            

        }
    }

    public function get_all_payroll(Request $request){

         $show_payrolls = Payroll::all();

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

    public function view_payslip(Request $request){

        $view_payslip = Payroll::with('employee')->find($request -> id);
		return response()->json($view_payslip);
    }



    public function get_all_payrollrecord(Request $request)
    {
        if ($request->ajax()) {
 
            if ($request->input('start_date') && $request->input('end_date')) {
 
                $start_date = Carbon::parse($request->input('start_date'));
                $end_date = Carbon::parse($request->input('end_date'));
 
            
                $payrolls = Payroll::with('employee')
                                    ->where('start_date', '>=', $start_date)
                                    ->where('end_date', '<=', $end_date)
                                    ->get();


            }
             else {
                
                $payrolls = Payroll::with('employee')->get();
            }
 
            return response()->json([
                'payrolls' => $payrolls
            ]);
        }
        else {

            abort(403);
        }
    }
   
}
