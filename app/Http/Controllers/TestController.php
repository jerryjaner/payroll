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

use App\person;

class TestController extends Controller
{

  public function test(){

    $user = Auth::user();
    return view('test');
       // //FOR THE FIXED RATE
                // if($request -> monthly_rate == "Fixed Rate"){

                //     $payroll = new Payroll();
                //     $payroll -> employee_name = $request -> employee_name;
                //     $payroll -> payment_type = $request -> payment_type;
                //     $payroll -> employee_number = $request -> employee_number;
                //     $payroll -> start_date = $request -> start_date;
                //     $payroll -> end_date = $request -> end_date;
                //     $payroll -> pay_date = $request -> pay_date;
                //     $payroll -> allowance = $request -> allowance;
                //     $payroll -> rate_per_hour = $request -> rate_per_hour;
                //     $payroll -> cash_advance = $request -> cash_advance;
                //     $payroll -> employee_base_salary = $request -> base_salary / 2;
                //     $payroll -> night_diff = 0.00;
                //     $payroll -> late_undertime = 0.00;
                //     $payroll -> total_overtime = 0.00;
                //     $payroll -> sss_deduction = 0.00;
                //     $payroll -> pag_ibig_deduction = 0.00;
                //     $payroll -> philhealth_deduction = 0.00;
                //     $payroll -> employee_absent = 0.00;
                //     $payroll -> add_adjustment = $request -> add_adjustment;
                //     $payroll -> deduct_adjustment = $request -> deduct_adjustment;
                    
                //     // $payroll -> restday = 0.00;
                //     $payroll -> total_deduction = 0.00 + $payroll -> cash_advance +  $payroll -> deduct_adjustment;
                //     $payroll -> gross =  $payroll -> employee_base_salary +   $payroll -> allowance + $payroll -> add_adjustment;
                //     $payroll -> net_pay = $payroll -> gross - $payroll -> total_deduction;

                //     $payroll -> save();

                //     return response()->json([
                //             'status' => 200,
                //             'msg' => 'Payroll Successfully',
                //     ]);
                // }
                // else{

                //     //NIGHT SHIFT WITH DEDUCTION 
                    
                //     //------------------ late Computation -----------------------------------------------//
                //     $late_total = ($total_latehours * 60) + $late_min;
                //     $late = $late_total / 60;
                //     $computed_late = $late * $request -> rate_per_hour * 1; //Computed Salary

                //     //------------------ late Computation -----------------------------------------------//
                //     $undertime_total = ($total_undertime * 60) + $undertime_min;
                //     $undertime = $undertime_total / 60;
                //     $computed_undertime = $undertime * $request -> rate_per_hour * 1; //Computed Salary

                //     //------------------ Restday Computation -----------------------------------------------//
                //     $restday_total_MinutesWorked = ($RD_total_work_hour * 60) + $RD_min;
                //     $restday_hoursWorked = $restday_total_MinutesWorked / 60;
                //     $restday_salary = $restday_hoursWorked * $request -> rate_per_hour * 1.3; //Computed Salary
                    
                //     //------------------ Rest Day and Special Holiday Computation ------------------------//
                //     $RDSH_total_MinutesWorked = ($RDSH_total_work_hour * 60) + $RDSH_min;
                //     $RDSH_hoursWorked = $RDSH_total_MinutesWorked / 60;
                //     $RDSH_salary = $RDSH_hoursWorked * $request -> rate_per_hour * 1.5;


                //     //------------------ Rest Day Regular Holiday -------------------------------------//
                //     $RDRH_total_MinutesWorked = ($RDRH_total_work_hour * 60) + $RDRH_min;
                //     $RDRH_hoursWorked = $RDRH_total_MinutesWorked / 60;
                //     $RDRH_salary = $RDRH_hoursWorked * $request -> rate_per_hour * 2.6;


                //     //------------------ Special Holiday -------------------------------------//
                //     $SH_total_MinutesWorked = ($SH_total_work_hour * 60) + $SH_min;
                //     $SH_hoursWorked = $SH_total_MinutesWorked / 60;
                //     $SH_salary = $SH_hoursWorked * $request -> rate_per_hour * 0.3;
                    
                //     //------------------ Regular Holiday -------------------------------------//
                //     $RH_total_MinutesWorked = ($RH_total_work_hour * 60) + $RH_min;
                //     $RH_hoursWorked = $RH_total_MinutesWorked / 60;
                //     $RH_salary = $RH_hoursWorked * $request -> rate_per_hour * 1;

                //     //------------------------------ Natural na Overtime  ----------------------------//
                //     $total_overtime = ($total_overtime * $request -> rate_per_hour) * 1.25;
                     
                //     //------------------------------ Rest day Overtime ----------------------------------------- //
                //     $restday_ot = ($RDOT_hrs * $request -> rate_per_hour )* 1.69;
                    
                //     //------------------------------ Special Holiday overtime ---------------------------------//
                //     $special_holiday_ot = ($SHOT_hrs * $request -> rate_per_hour )* 1.69;
                    
                //     //------------------------------ Regular Holiday overtime ---------------------------------//
                //     $regular_holiday_ot = ($RHOT_hrs * $request -> rate_per_hour )* 2.6;

                //     //------------------------------ Restday Special Holiday overtime ---------------------------------//
                //     $restday_special_holiday_ot = ($RDSHOT_hrs * $request -> rate_per_hour )* 1.95;

                //     //------------------------------ Restday Regular Holiday overtime ---------------------------------//
                //     $restday_regular_holiday_ot = ($RDRHOT_hrs * $request -> rate_per_hour )* 3.38;


                //     //Calculation for allowance
                   
                //     $allowance_per_day =  $request -> allowance  / 11;  // makukuha an daily allowance
                //     $total_absent = $absent * $allowance_per_day; // total absent multiply by total allowance
                //     $total_allowance =  $request -> allowance  -  $total_absent; // to get allowance = allowance per cut  then subract the total absent


                //     //Night diff with 10%
                //     $ten_percent_night_differential = ($ten_percent_addional *  $request -> rate_per_hour) * 0.1;




                //     $payroll = new Payroll();
                //     $payroll -> employee_name = $request -> employee_name;
                //     $payroll -> payment_type = $request -> payment_type;
                //     $payroll -> employee_number = $request -> employee_number;
                //     $payroll -> start_date = $request -> start_date;
                //     $payroll -> end_date = $request -> end_date;
                //     $payroll -> pay_date = $request -> pay_date;
                //     $payroll -> rate_per_hour = $request -> rate_per_hour;
                //     $payroll -> cash_advance = $request -> cash_advance;
                //     $payroll -> employee_base_salary = $request -> base_salary / 2;
                //     $payroll -> night_diff = $ten_percent_night_differential;
                //     $payroll -> employee_absent = $absent * $request -> daily_rate;
                //     $payroll -> allowance = $total_allowance;
                //     $payroll -> undertime_total =  $computed_undertime;
                //     $payroll -> late_total = $computed_late;
                //     $payroll -> late_undertime = $computed_late + $computed_undertime;
                //     $payroll -> restday = $restday_salary;
                //     $payroll -> special_holiday = $SH_salary;
                //     $payroll -> regular_holiday = $RH_salary;
                //     $payroll -> restday_regular_holiday = $RDRH_salary;
                //     $payroll -> restday_special_holiday = $RDSH_salary;
                //     $payroll -> total_holiday = $payroll -> restday_special_holiday + $payroll -> restday_regular_holiday + $payroll -> regular_holiday + $payroll -> special_holiday + 

                //     $payroll -> overtime = $total_overtime;
                //     $payroll -> restday_overtime = $restday_ot;
                //     $payroll -> special_holiday_overtime = $special_holiday_ot;
                //     $payroll -> regular_holiday_overtime = $regular_holiday_ot;
                //     $payroll -> restday_special_holiday_overtime = $restday_special_holiday_ot;
                //     $payroll -> restday_regular_holiday_overtime = $restday_regular_holiday_ot;
                //     $payroll -> add_adjustment = $request -> add_adjustment;
                //     $payroll -> deduct_adjustment = $request -> deduct_adjustment;
                    

                //   //  dd($payroll);
                        
                //     $payroll -> gross =  $payroll -> employee_base_salary +  $payroll -> allowance + $payroll -> night_diff +  $payroll -> total_overtime +  $payroll -> restday  + $SH_salary + $RH_salary + $RDRH_salary + $RDSH_salary + 
                //                          $payroll -> overtime +  $payroll -> restday_overtime +  $payroll -> special_holiday_overtime +  $payroll -> regular_holiday_overtime +  $payroll -> restday_special_holiday_overtime + $payroll -> restday_regular_holiday_overtime 
                //                          + $payroll -> add_adjustment;
                   
                 
                //     if($sss_deduction -> isNotEmpty()){

                //         foreach($sss_deduction as $deduction){

                //             if ($payroll -> employee_base_salary >= $deduction->from && $payroll -> employee_base_salary >= $deduction->to) {

                //                 $payroll -> sss_deduction = $deduction -> regular_EE / 2;
                //               //  break; // Exit the loop after the first deduction that matches
                //             }
                //             elseif ($payroll -> employee_base_salary >= $deduction->from && $payroll -> employee_base_salary <= $deduction->to) {

                //                 $payroll -> sss_deduction = $deduction -> regular_EE / 2;
                //               //  break; // Exit the loop after the first deduction that matches
                //             }
                           
                //         }
                //     }
                //     else{
                //         $payroll -> sss_deduction = 0.00;
                //     }

                //     if($philhealth_deduction -> isNotEmpty()){

                //         foreach($philhealth_deduction as $phil_deduction){
                            
                //             if($payroll -> employee_base_salary >= $phil_deduction->monthly_basic_salary_from && $payroll -> employee_base_salary >= $phil_deduction->monthly_basic_salary_to){

                //                 $payroll -> philhealth_deduction =  $payroll -> employee_base_salary * $phil_deduction -> premium_rate / 2;
                //             }

                //             elseif($payroll -> employee_base_salary >= $phil_deduction->monthly_basic_salary_from && $payroll -> employee_base_salary <= $phil_deduction->monthly_basic_salary_to) {

                //                 $payroll -> philhealth_deduction =  $payroll -> employee_base_salary * $phil_deduction -> premium_rate / 2;
                //                // break; // Exit the loop after the first deduction that matches
                //             }
                           
                //         }
                //     }
                //     else{
                //         $payroll -> philhealth_deduction = 0.00;
                //     }

                //     if($pagibig_deduction -> isNotEmpty()){

                //         foreach($pagibig_deduction as $pagibig_deduct){

                         
                //             if ($payroll -> employee_base_salary >= $pagibig_deduct->monthly_salary_from && $payroll -> employee_base_salary <= $pagibig_deduct->monthly_salary_to) {

                //                 $payroll -> pag_ibig_deduction =  $pagibig_deduct -> employees_share / 2;

                //                 $payroll -> total_deduction =  $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction + $payroll -> late_undertime + $payroll -> cash_advance + $payroll -> employee_absent + $payroll -> deduct_adjustment;
                //                 $payroll -> gov_contribution = $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction;
                //                 $payroll -> net_pay = $payroll -> gross - $payroll -> total_deduction;
                //                 break; // Exit the loop after the first deduction that matches
   
                //             }
                //             elseif ($payroll -> employee_base_salary >= $pagibig_deduct->monthly_salary_from && $payroll -> employee_base_salary >= $pagibig_deduct->monthly_salary_to) {

                //                 $payroll -> pag_ibig_deduction =  $pagibig_deduct -> employees_share / 2;

                //                 $payroll -> total_deduction =  $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction + $payroll -> late_undertime + $payroll -> cash_advance + $payroll -> employee_absent + $payroll -> deduct_adjustment;
                //                 $payroll -> gov_contribution = $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction;
                //                 $payroll -> net_pay = $payroll -> gross - $payroll -> total_deduction;
                //                 break; // Exit the loop after the first deduction that matches
                               
                //             }
                            
                //         }
                //     }
                //     else{

                //          $payroll -> pag_ibig_deduction = 0.00;

                //          $payroll -> total_deduction =  $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction + $payroll -> late_undertime + $payroll -> cash_advance + $payroll -> employee_absent + $payroll -> deduct_adjustment;
                //          $payroll -> gov_contribution = $payroll -> philhealth_deduction + $payroll -> sss_deduction +  $payroll -> pag_ibig_deduction;
                //          $payroll -> net_pay = $payroll -> gross - $payroll -> total_deduction;
                //     }
                    
                
                //     $payroll -> save();
                //     return response()->json([
                //             'status' => 200,
                //             'msg' => 'Payroll Successfully',
                //     ]);
                // }

  }

}