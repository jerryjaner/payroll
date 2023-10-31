<?php

use App\Http\Controllers\Deduction;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    TestController,
    UserController,
    AccountController,
    PayrollController,
    EmployeeController,
    LeaveReqController,
    OvertimeController,
    DashboardController,
    DeductionController,
    AttendanceController,
    HolidayController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    // return redirect()->route('dashboard');
    return view('auth.login');

});

Auth::routes();


 Route::group(['middleware' => ['auth']], function () {

    Route::get('/attendance-count', function () {
        return Attendance::count();
    });
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/employee/count', [DashboardController::class, 'count1'])->name('count1');
    Route::get('/employees1', [DashboardController::class, 'index2'])->name('index2');
    Route::get('/employees3', [DashboardController::class, 'index3'])->name('index3');
    Route::get('/employees4', [DashboardController::class, 'index4'])->name('index4');
    Route::get('/employee_bday', [DashboardController::class, 'employee_bday'])->name('employee_bday');

    // AccountController
    //Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::post('/update_password', [AccountController::class, 'update_password']);
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::get('/view-payslip1', [AccountController::class, 'view_payslip1'])->name('view_payslip1');
    Route::get('/employee-payroll1', [AccountController::class, 'get_all_payroll1'])->name('get_payroll1');
    Route::get('/edit-employees1', [AccountController::class, 'edit_employee1'])->name('edit_employee1');

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::get('/get-attendance', [AttendanceController::class, 'today_attendance'])->name('get_attendance');
    Route::post('/attendance', [AttendanceController::class, 'attendance_record'])->name('attendance_record');
    Route::get('/get-scanned-employee', [AttendanceController::class, 'first_scanned'])->name('first_scanned');
    Route::get('/edit_mod', [AttendanceController::class, 'edit_mod'])->name('edit_mod');
    Route::post('/update_mod', [AttendanceController::class, 'update_mod'])->name('update_mod');
    Route::get('/all-attendance', [AttendanceController::class, 'AllAttendance'])->name('all_attendance');
    Route::post('/absent-onleave', [AttendanceController::class, 'absent_onleave'])->name('absent_onleave');
    Route::get('/get-absent-onleave', [AttendanceController::class, 'absent_onleave_attendance'])->name('absent_onleave_attendance');

    // Holiday
    Route::get('/holiday', [HolidayController::class, 'holiday'])->name('holiday');
    Route::post('/add-holiday', [HolidayController::class, 'add_holiday'])->name('add_holiday');
    Route::get('/all-holiday-list', [HolidayController::class, 'holiday_list'])->name('holiday_list');
    Route::get('/edit-holiday', [HolidayController::class, 'edit_holiday'])->name('edit_holiday');
    Route::post('/update-holiday', [HolidayController::class, 'update_holiday'])->name('update_holiday');
    Route::delete('/delete-holiday', [HolidayController::class, 'delete_holiday'])->name('delete_holiday');

    // Leave
    Route::get('/leave-request', [LeaveReqController::class, 'leaveRequest'])->name('leaveRequest');
    Route::post('/add-leave', [LeaveReqController::class, 'add_leave'])->name('add_leave');
    // Route::get('/get-leave', [LeaveReqController::class, 'fetchAllLeave'])->name('get_leave');
    Route::get('/all-leave', [LeaveReqController::class, 'AllLeaveRequest'])->name('all_leave_request');
    Route::get('/all-leave-emp', [LeaveReqController::class, 'AllLeaveEmp'])->name('all_leave_emp');

    //APPROVE
    Route::get('/approve-leave-HR', [LeaveReqController::class, 'Approve_leave_HR'])->name('approve_leave_HR');
    Route::post('/submit-leave-HR', [LeaveReqController::class, 'Submit_leave_HR'])->name('submit_leave_HR');
    Route::get('/approve-leave-SVP', [LeaveReqController::class, 'Approve_leave_SVP'])->name('approve_leave_SVP');
    Route::post('/submit-leave-SVP', [LeaveReqController::class, 'Submit_leave_SVP'])->name('submit_leave_SVP');
    Route::get('/approve-leave-TL', [LeaveReqController::class, 'Approve_leave_TL'])->name('approve_leave_TL');
    Route::post('/approve-leave-TL', [LeaveReqController::class, 'Submit_leave_TL'])->name('submit_leave_TL');
    Route::get('/approve-leave-VPO', [LeaveReqController::class, 'Approve_leave_VPO'])->name('approve_leave_VPO');
    Route::post('/submit-leave-VPO', [LeaveReqController::class, 'Submit_leave_VPO'])->name('submit_leave_VPO');
    Route::get('/approve-leave-COO', [LeaveReqController::class, 'Approve_leave_COO'])->name('approve_leave_COO');
    Route::post('/submit-leave-COO', [LeaveReqController::class, 'Submit_leave_COO'])->name('submit_leave_COO');
    
    // DECLINE
    Route::get('/decline-leave-TL', [LeaveReqController::class, 'Decline_leave_TL'])->name('decline_leave_TL');
    Route::post('/decline-TL', [LeaveReqController::class, 'Submit_decline_TL'])->name('submit_decline_TL');
    Route::get('/decline-leave-HR', [LeaveReqController::class, 'Decline_leave_HR'])->name('decline_leave_HR');
    Route::post('/decline-HR', [LeaveReqController::class, 'Submit_decline_HR'])->name('submit_decline_HR');
    Route::get('/decline-leave-SVP', [LeaveReqController::class, 'Decline_leave_SVP'])->name('decline_leave_SVP');
    Route::post('/decline-SVP', [LeaveReqController::class, 'Submit_decline_SVP'])->name('submit_decline_SVP');
    Route::get('/decline-leave-VPO', [LeaveReqController::class, 'Decline_leave_VPO'])->name('decline_leave_VPO');
    Route::post('/decline-VPO', [LeaveReqController::class, 'Submit_decline_VPO'])->name('submit_decline_VPO');
    Route::get('/decline-leave-COO', [LeaveReqController::class, 'Decline_leave_COO'])->name('decline_leave_COO');
    Route::post('/decline-COO', [LeaveReqController::class, 'Submit_decline_COO'])->name('submit_decline_COO');



    //Over TIme
    Route::get('/overtime-request', [OvertimeController::class, 'overtimeRequest'])->name('overtimeRequest');
    Route::get('/employee-all-attendance', [OvertimeController::class, 'employee_all_attendance'])->name('employee_all_attendance');
    Route::get('/request-overtime', [OvertimeController::class, 'overtime_request'])->name('overtime_request');
    Route::post('/submit-overtime', [OvertimeController::class, 'submit_overtime_request'])->name('submit_overtime_request');

    Route::get('/all-overtime-request', [OvertimeController::class, 'all_overtime_request'])->name('all_overtime_request');
    // HR Approve button
    Route::get('/approve-HR', [OvertimeController::class, 'approve_HR'])->name('approve_HR');
    Route::post('/approve-request-HR', [OvertimeController::class, 'submit_approve_HR'])->name('submit_approve_HR');
    // SVP Approve button
    Route::get('/approve-SVP', [OvertimeController::class, 'approve_SVP'])->name('approve_SVP');
    Route::post('/approve-request-SVP', [OvertimeController::class, 'submit_approve_SVP'])->name('submit_approve_SVP');
    // TL Approve button
    Route::get('/approve-TL', [OvertimeController::class, 'approve_TL'])->name('approve_TL');
    Route::post('/approve-request-TL', [OvertimeController::class, 'submit_approve_TL'])->name('submit_approve_TL');
     // VPO Approve button
    Route::get('/approve-VPO', [OvertimeController::class, 'approve_VPO'])->name('approve_VPO');
    Route::post('/approve-request-VPO', [OvertimeController::class, 'submit_approve_VPO'])->name('submit_approve_VPO');

    Route::get('/approve-COO', [OvertimeController::class, 'approve_COO'])->name('approve_COO');
    Route::post('/approve-request-COO', [OvertimeController::class, 'submit_approve_COO'])->name('submit_approve_COO');





    //FOR DECLINE THE REQUEST OVERTIME
    Route::get('/decline-HR', [OvertimeController::class, 'decline_HR'])->name('HR_decline');
    Route::post('/submit-decline-HR', [OvertimeController::class, 'submit_HR_decline'])->name('HR_submit_decline');

    Route::get('/decline-TL', [OvertimeController::class, 'decline_TL'])->name('TL_decline');
    Route::post('/submit-decline-TL', [OvertimeController::class, 'submit_TL_decline'])->name('TL_submit_decline');

    Route::get('/decline-SVP', [OvertimeController::class, 'decline_SVP'])->name('SVP_decline');
    Route::post('/submit-decline-SVP', [OvertimeController::class, 'submit_SVP_decline'])->name('SVP_submit_decline');

    Route::get('/decline-VPO', [OvertimeController::class, 'decline_VPO'])->name('VPO_decline');
    Route::post('/submit-decline-VPO', [OvertimeController::class, 'submit_VPO_decline'])->name('VPO_submit_decline');

    Route::get('/decline-COO', [OvertimeController::class, 'decline_COO'])->name('COO_decline');
    Route::post('/submit-decline-COO', [OvertimeController::class, 'submit_COO_decline'])->name('COO_submit_decline');





    // Payroll
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll');
    Route::get('/view-employees', [PayrollController::class, 'view_employee'])->name('view_employee');
    Route::post('/submit-payroll', [PayrollController::class, 'submit_payroll'])->name('submit_payroll');
    Route::get('/employee-payroll', [PayrollController::class, 'get_all_payroll'])->name('get_payroll');
    Route::get('/view-payslip', [PayrollController::class, 'view_payslip'])->name('view_payslip');
    Route::get('/all-record', [PayrollController::class, 'get_all_payrollrecord'])->name('get_payroll_record');
    


    // Route::get('/daterange', 'DateRangeController@index');
    // Route::post('/daterange/fetch_data', 'DateRangeController@fetch_data')->name('daterange.fetch_data');
    



    // Deduction
    Route::get('/deduction', [DeductionController::class, 'index'])->name('deduction');
    // SSS
    Route::get('/all-sss-deduction', [DeductionController::class, 'sss_deduction'])->name('sss_deduction');
    Route::post('/add-sss-deduction', [DeductionController::class, 'add_sss_deduction'])->name('add_sss_deduction');
    Route::get('/edit_sss', [DeductionController::class, 'edit_sss'])->name('edit_sss_deduction');
    Route::post('/update-sss', [DeductionController::class, 'update_sss'])->name('update_sss');
    Route::delete('/delete-sss', [DeductionController::class, 'delete_sss'])->name('delete_sss');
    // Pagibig
    Route::get('/pagibig-deduction', [DeductionController::class, 'pagibig_deduction'])->name('pagibig_deduction');
    Route::post('/submit-pagibig-deduction', [DeductionController::class, 'submit_pagibig'])->name('submit_pagibig');
    Route::get('/edit-pagibig', [DeductionController::class, 'edit_pagibig'])->name('edit_pagibig');
    Route::post('/update-pagibig', [DeductionController::class, 'update_pagibig'])->name('update_pagibig');
    Route::delete('/delete-pagibig', [DeductionController::class, 'delete_pagibig'])->name('delete_pagibig');
    // Philhealth
    Route::get('/philhealth-deduction', [DeductionController::class, 'philhealth_deduction'])->name('philhealth_deduction');
    Route::post('/add-philhealth-deduction', [DeductionController::class, 'add_philhealth'])->name('add_philhealth');
    Route::get('/edit-philhealth', [DeductionController::class, 'edit_philhealth'])->name('edit_philhealth');
    Route::post('/update-philhealth', [DeductionController::class, 'update_philhealth'])->name('update_philhealth');
    Route::delete('/delete-philhealth', [DeductionController::class, 'delete_philhealth'])->name('delete_philhealth');
    // Employee
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');
    Route::post('/add-employees-user', [EmployeeController::class, 'add_employee_user'])->name('add_employees_user');
    Route::get('/get-employees', [EmployeeController::class, 'fetch_all'])->name('get_employees');
    Route::get('/edit-employees', [EmployeeController::class, 'edit_employee'])->name('edit_employee');
    Route::post('/update', [EmployeeController::class, 'update'])->name('update');
    Route::delete('/delete', [EmployeeController::class, 'delete'])->name('delete');
    Route::get('/edit-qr', [EmployeeController::class, 'edit_qr'])->name('edit_qr');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/get-users', [UserController::class, 'fetch_user'])->name('get_user');
    Route::get('/edit_user', [UserController::class, 'edit_user'])->name('edit_user');
    Route::post('/update_user', [UserController::class, 'update_user'])->name('update_user');
    Route::delete('/delete_user', [UserController::class, 'delete_user'])->name('delete_user');
    // Route::get('/viewpassword', [UserController::class, 'viewpassword'])->name('viewpassword');
    Route::post('/update_password', [UserController::class, 'update_password'])->name('update_password');


    Route::get('/account',[AccountController::class, 'index'])->name('account');


 });


//For the test or Trial and Error and Testing of Query
Route::get('/test', [TestController::class, 'test'])->name('test');
// Route::get('/view-payslip1', [TestController::class, 'view_payslip1'])->name('view_payslip1');
// Route::get('/employee-payroll1', [TestController::class, 'get_all_payroll1'])->name('get_payroll1');
// Route::get('/edit-employees1', [TestController::class, 'edit_employee1'])->name('edit_employee1');
//Route::post('/update1', [TestController::class, 'update1'])->name('update1');
//Route::post('/update_password1', [AccountController::class, 'update_password1']);
