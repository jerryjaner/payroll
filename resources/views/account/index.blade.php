@extends('layouts.main')
@section('main-content')
<div class="page-row row">
    <div class="col-xl-6">
        <h2 class="page-heading">User Profile</h2>
    </div>
</div>

{{-- Employee Profile --}}

<div class="container-fluid">
    <div class="row d-flex p-2">
        {{-- left ----------------------------------------------------------------------------------------------------------------------------------------------}}
        <div class="col-lg-4 p-2">
            <div class="employee-card p-2">
                <div class="d-flex justify-content-end">
                    <a href="#" type="button" id="{{auth()->user()->employee->id ?? 'n/a'}}" class="btn-view editIcon" style="background-color: transparent; box-shadow: none;" data-tippy-content="Edit Profile" data-tippy-arrow="false" data-bs-toggle="modal" data-bs-target="#EditEmployee">
                        <i class="bx bxs-edit fs-5" style="color:#999999"></i>
                    </a>
                </div>
                    {{-- pic --}}
                <div class="p-2">
                    <div class="text-center">
                        @if(Auth::user()->employee->image != null)

                        <img src="{{asset('storage/employee/images/'. Auth::user()->employee->image)}}" class="img-fluid mx-auto" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:20vh; height: 20vh;">
                        @else

                        {{-- <i class='bx bx-user icon' id="profile-pic" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:130px; height: 130px;"></i> --}}
                        <img src="https://img.freepik.com/premium-vector/user-profile-login-access-authentication-icon_690577-203.jpg" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:20vh; height: 20vh;">

                        @endif
                        
                    </div>

                    <div class="text-center">
                        <h1 class="mt-2 page-heading fs-3">{{auth()->user()->name}}</h1>
                        <p class="mt-1 emp-no d-flex align-items-center justify-content-center gap-1" style="color:#bc3d4f"><i class='bx bxs-user fs-6'></i>{{auth()->user()->employee->employee_department ?? 'n/a'}} Department</p>
                        <p class="mt-1 emp-no d-flex align-items-center justify-content-center gap-1" style="color:#bc3d4f"><i class='bx bxl-google-plus fs-6'></i>{{auth()->user()->email ?? 'n/a'}}</p>
                        <p class="mt-1 emp-no d-flex align-items-center justify-content-center gap-1" style="color:#bc3d4f"><i class='bx bx-phone fs-6'></i>{{auth()->user()->employee->employee_contact_number ?? 'n/a'}}</p>
                    </div>

                </div>
                {{-- profile info --}}
                <div class="mt-3 section-container p-auto m-auto">
                    <div class="row d-flex">
                        <div>
                            <div class="d-flex align-items-center col">
                                <i class='bx bxs-user-detail section-icon'></i>
                                <h5 class="section-header">Profile Info</h5>
                            </div>
                        </div>
                        <div class="p-2">
                            <div>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="emp-no text-start">Username</span></td>
                                            <td class="emp-no text-end" style="color:#bc3d4f">{{auth()->user()->username ?? 'n/a'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="emp-no text-start">Email</td>
                                            <td class="emp-no text-end" style="color:#bc3d4f">{{auth()->user()->email ?? 'n/a'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="emp-no text-start">Department</td>
                                            <td class="emp-no text-end" style="color: #bc3d4f">{{auth()->user()->employee->employee_department ?? 'n/a'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="emp-no text-start">Position</td>
                                            <td class="emp-no text-end" style="color: #bc3d4f">{{auth()->user()->position ?? 'n/a'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="emp-no text-start">Address</td>
                                            <td class="emp-no text-end" style="color:#bc3d4f">{{auth()->user()->employee->employee_address ?? 'n/a'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="emp-no text-start">Contact Number</td>
                                            <td class="emp-no text-end" style="color:#bc3d4f">{{auth()->user()->employee->employee_contact_number ?? 'n/a'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="emp-no text-start">Birthday</td>
                                            <td class="emp-no text-end" style="color:#bc3d4f">{{Carbon\Carbon::parse(auth()->user()->employee->employee_birthday)->format('M d, Y')}}</td>
                                        </tr>
                                        <tr>
                                            <td class="emp-no text-start">SSS Number</td>
                                            <td class="emp-no text-end" style="color:#bc3d4f">{{auth()->user()->employee->sss_number ?? 'n/a'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="emp-no text-start">Pagibig Number</td>
                                            <td class="emp-no text-end" style="color:#bc3d4f">{{auth()->user()->employee->pagibig_number ?? 'n/a'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="emp-no text-start">Philhealth Number</td>
                                            <td class="emp-no text-end" style="color:#bc3d4f">{{auth()->user()->employee->philhealth_number ?? 'n/a'}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>

                        {{-- emergency contct --}}
                        <div class="d-flex align-items-center">
                            <i class='bx bxs-phone section-icon'></i>
                            <h5 class="section-header">Emergency Contact</h5>
                        </div>
                        <div class="p-2">
                            <div>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="emp-no text-start">Name</td>
                                            <td class="emp-no text-end" style="color:#bc3d4f">{{auth()->user()->employee->emergency_contact_name ?? 'n/a'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="emp-no text-start">Contact Number</td>
                                            <td class="emp-no text-end" style="color:#bc3d4f">{{auth()->user()->employee->emergency_contact_number ?? 'n/a'}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div> 
                </div>
            </div>
            
        </div>
        {{-- right ----------------------------------------------------------------------------------------------------------------------------------------}}
        <div class="col-lg-8 p-2" >

            {{-- payslip table --}}
            <div class="section-container mt-2" id="form2" >
                <div class="d-flex align-items-center">
                    <i class='bx bx-money-withdraw section-icon'></i>
                    <h5 class="section-header">My Payslip</h5>
                </div>
                <br>
                <div class="row table-row" id="show_payroll">
        
                </div>
            </div>

            {{-- password qr--}}
            <div class="row d-flex employee-card mt-3" style=" padding:20px; border-bottom: 3px solid #bc3d4f;">

                <div class="col-lg-5" >

                    <div class="d-flex align-items-center">
                        <i class='bx bx-qr section-icon'></i>
                        <h5 class="section-header fs-auto">QR Code</h5>
                    </div>

                    <div class="row-auto first-row" style="word-wrap: break-word;">
                        <div class="d-flex justify-content-center p-auto">
                            <img src="http://chart.apis.google.com/chart?cht=qr&chs=500x500&chl={{Auth::user()->employee->employee_no}}" style="width: 15rem; height: 15rem;" >
                        </div>
                    </div>
                </div>

                <div class="col-lg-7" >

                    <div class="d-flex align-items-center">
                        <i class='bx bx-key section-icon'></i>
                        <h5 class="section-header fs-auto">Change Password</h5>
                    </div>

                    <div class="row-auto first-row" style="word-wrap: break-word;">

                            <form action="{{ route('update_password') }}" method="POST" method="POST" id="update_password"  enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="usern_id" id="users_id" value="{{Auth::user()->id}}">

                                <div class="form-row row-auto">

                                    <div class="row-xl-12 m-2">
                                        <label for="txt-time">New password:</label>
                                        <input type="password" name="new_password" class="form-control" id="new_password">
                                        <span class="text-danger error-text new_password_error"></span>
                                    </div>
                                    <div class="row-xl-12 m-2">
                                        <label for="txt-time">Confirm new password:</label>
                                        <input type="password" name="confirm_password" class="form-control" id="confirm_password">
                                        <span class="text-danger error-text confirm_password_error"></span>
                                    </div>
                                </div>

                                <div class="row-auto d-flex justify-content-end m-0 p-2">
                                    <button type="submit" class="btn btn-form btn-sm modal-btn" id="update_password_btn" >Submit</button>
                                </div>

                            </form>
                    </div>
                </div>

                

                {{-- <div class="p-3 col-lg-6 section-container" style=" padding:20px; border-bottom: 3px solid #bc3d4f;">
                    <div class="">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-qr section-icon'></i>
                            <h5 class="section-header fs-auto">QR Code</h5>
                        </div>

                        <div class="d-flex justify-content-center p-auto">
                            <img src="http://chart.apis.google.com/chart?cht=qr&chs=500x500&chl={{Auth::user()->employee->employee_no}}" style="width: 15rem; height: 15rem;" >
                        </div>
                    </div>
                </div> --}}
            </div>
            

        </div>
    </div>
</div>

{{-- auto pop up button --}}
<button id="scrollToTopButton" onclick="scrollToTop()" class="vibrate-1 btn-view editIcon m-0 p-0" data-tippy-content="Back to top" data-tippy-arrow="false"><i class="bx bx-up-arrow-alt mt-2"></i></button>

{{-- MODAL FOR PAYSLIP --}}
<div class="modal fade" id="payslip"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content section-container" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body"  id="printThis">

                <form action="" method="POST" id=""  enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="payslip_id" id="payslip_id">

                    <div class="form-row">
                        <div class="row  first-row" style="word-wrap: break-word; ">
                            <div class="col-xl-3 align-item-start">
                                <div class="row d-flex justify-content-center ">
                                    <img src="images/logo.png" style="height: auto; width:13rem;" alt="">
                                </div>
                            </div>
                            <div class="col-xl-12 align-item-center">
                                <div class="row text-center align-item-start">
                                        <span class="time">GLP THEOREM VENTURES CORPORATION</span>
                                        <span>DGM BLDG., Maharlika Highway, Cabin-an, East District,</span>
                                        <span>Sorsogon City, 4700 Philippines</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        <div class="row justify-content-between first-row" style="word-wrap: break-word; ">
                            <div class="col-6">
                                <div class="row first-row d-flex justify-content-start">
                                    <div class="row-xl-auto">
                                        <small>Name:<span id="name" class="time" style="margin-left: 20px;"></span></small>
                                    </div>
                                    <div class="row-xl-auto">
                                        <small>Birthday:<span id="bday" class="time" style="margin-left: 20px;"></span></small>
                                    </div>
                                    <div class="row-xl-auto">
                                        <small>Employee Number:<span id="employee_num" class="time" style="margin-left: 20px;"></span></small>
                                    </div>
                                    <div class="row-xl-auto">
                                        <small>Department:<span id="department"  class="time" style="margin-left: 20px;"></span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row first-row  d-flex justify-content-end">
                                    <div class="row-xl-auto">
                                        <small>Pay Period:<span id="from" class="time" style="margin-left: 20px;"></span> - <span id="to" class="time"></span></small>
                                    </div>
                                    <div class="row-xl-auto">
                                        <small>Pay Date:<span id="paydate" class="time" style="margin-left: 20px;"></span></small>
                                    </div>
                                    <div class="row-xl-auto">
                                        <small>Payment Method:<span id="payment_type" class="time" style="margin-left: 20px;"></span></small>
                                    </div>
                                    <div class="row-xl-auto">
                                        <small>Position:<span id="position" class="time" style="margin-left: 20px;"></span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-xl-auto time mt-3">
                        <small><span>Memos:  </span><span id="" ></span></small>
                    </div>
                    <div class="container">

                        <div class="row justify-content-between first-row" style="word-wrap: break-word;">

                                <div class="col-6 border border-dark time text-center bg-info  mx-auto">Current Salary Details</div>
                                <div class="col-6 border border-dark time text-center bg-info mx-auto">Year to Date</div>

                            {{--  left column  --}}
                            <div class="col-6 border border-dark mx-auto" style="font-size: 12px">
                                <div class="row justify-content-between first-row" style="word-wrap: break-word; ">

                                    <div class="col-6">
                                        <div class="row d-flex justify-content-start text-end">
                                            <div class="row-sm-auto">
                                                <small>Base Salary:<span id="employee_base_salary" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            {{-- <div class="row-sm-auto">
                                                <small>Work Hours:<span id="work_hours" class="text1" style="margin-left: 10px;"></span></small>
                                            </div> --}}
                                            <div class="row-sm-auto">
                                                <small>Overtime:<span id="total_overtime" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Night Diff:<span id="night_differential" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Restday:<span id="employee_restday" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>REG/SPE:<span id="employee_reg_spe" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Allowance:<span id="allowance" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Adjustment:<span id="name" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="row d-flex justify-content-end text-end">
                                            <div class="row-sm-auto">
                                                <small>Late/UT:<span id="late_undertime" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>A/VTO/LWP/S:<span id="absent" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>SSS:<span id="sss_deduction" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Pag Ibig:<span id="pagibig_deduction" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Phil Health:<span id="philhealth_deduction"  class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Tax:<span id="from" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>SSS Loan:<span id="position" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Pag Ibig Loan:<span id="name" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Cash Advance:<span id="employee_cash_advance" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Adjustment:<span id="department"  class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row justify-content-between first-row mb-3" style="word-wrap: break-word; ">
                                    <div class="col-6">
                                        <div class="row d-flex justify-content-start text-end">
                                            <div class="row-sm-auto">
                                                <small>Gross:<span id="gross" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Net Salary:<span id="net_salary" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row d-flex justify-content-end text-end">
                                            <div class="row-sm-auto">
                                                <small><span id="from" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Total Ded:<span id="total_deduction" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{--  right column  --}}
                            <div class="col-6 border border-dark mx-auto" style="font-size: 12px">
                                <div class="row justify-content-between first-row" style="word-wrap: break-word; ">

                                    <div class="col-6">
                                        <div class="row d-flex justify-content-start text-end">
                                            <div class="row-sm-auto">
                                                <small>Base Salary:<span id="name" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Overtime:<span id="employee_num" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Night Diff:<span id="name" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Restday:<span id="employee_num" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>REG/SPE:<span id="name" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Allowance:<span id="employee_num" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Adjustment:<span id="name" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="row d-flex justify-content-end text-end">
                                            <div class="row-sm-auto">
                                                <small>Late/UT:<span id="from" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>A/VTO/LWP/S:<span id="position" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>SSS:<span id="name" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Pag Ibig:<span id="employee_num" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Phil Health:<span id="department"  class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Tax:<span id="from" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>SSS Loan:<span id="position" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Pag Ibig Loan:<span id="name" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Cash Advance:<span id="" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Adjustment:<span id="department"  class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row justify-content-between first-row mb-3" style="word-wrap: break-word; ">
                                    <div class="col-6">
                                        <div class="row d-flex justify-content-start text-end">
                                            <div class="row-sm-auto">
                                                <small>Gross:<span id="name" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Net Salary:<span id="" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row d-flex justify-content-end text-end">
                                            <div class="row-sm-auto">
                                                <small><span id="from" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                            <div class="row-sm-auto">
                                                <small>Total Ded:<span id="" class="text1" style="margin-left: 10px;"></span></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 gap-2 d-flex justify-content-end">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" id="closemodal" data-bs-dismiss="modal" aria-label="Close" style="background-color:#1e1e1e;"><i class='bx bx-x'></i>Close</button>
                        <button type="button"  class="btn btn-form btn-sm modal-btn" id="btnPrint"><i class='bx bx-printer'></i> Print</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL FOR EDIT PROFILE--}}
<div class="modal fade " id="EditEmployee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{route('update')}}" method="POST" id="update"  enctype="multipart/form-data">
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">Edit Employee</h5>
                    </div>
                    <input type="hidden" name="emp_id" id="emp_id">

                    <div class="mt-2" id="image" >
                        {{-- The Employee Picture Appear Here --}}
                    </div>
                    <div class="form-row row first-row">
                        <div class="col-xl-12">
                            <label for="txt-time">Name:</label>
                            <input type="text" name="employee_name" class="form-control" id="employee_name">
                            <span class="text-danger error-text employee_name_error"></span>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Employee Picture</label>
                            <input type="file" name="image" class="form-control" id="image">
                            <span class="text-danger error-text image_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="txt-time">Employee No:</label>
                            <input type="text" name="employee_no" class="form-control" id="employee_no" readonly>
                            <span class="text-danger error-text employee_no_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Base Salary:</label>
                            <input type="number" name="base_salary" min="0" class="form-control" id="base_salary">
                            <span class="text-danger error-text base_salary_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Monthly Rate:</label>
                            <select name="monthly_rate" class="form-select" id="monthly_rate">
                                <option value="">Select Rate</option>
                                <option value="Daily Rate">Daily Rate</option>
                                <option value="Fixed Rate">Fixed Rate</option>
                            </select>
                            <span class="text-danger error-text monthly_rate_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Position:</label>
                            <input type="text" name="employee_position" class="form-control" id="employee_position">
                            <span class="text-danger error-text employee_position_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Department:</label>
                            <select name="employee_department" class="form-select" id="employee_department">
                                <option value="Administration">Administration</option>
                                <option value="App Intake">App Intake</option>
                                <option value="Audit">Audit</option>
                                <option value="Verification">Verification</option>
                                {{-- <option value="Orenda">Orenda</option> --}}
                                <option value="Returns">Returns</option>
                                <option value="IT">IT</option>
                                <option value="Project Management">Project Management</option>
                                <option value="Provider Relation">Provider Relation</option>
                                <option value="Provider Enrollment">Provider Enrollment</option>
                            </select>
                            <span class="text-danger error-text employee_department_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Date Hired:</label>
                            <input type="date" name="date_hired" class="form-control" id="date_hired">
                            <span class="text-danger error-text date_hired_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="txt-time">Allowance:</label>
                            <input type="number" step="any" name="employee_allowance" class="form-control" id="employee_allowance">
                            <span class="text-danger error-text employee_allowance_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">SSS Number:</label>
                            <input type="text" name="sss" class="form-control"  id="sss">
                            <span class="text-danger error-text sss_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="txt-time">Pagibig Number:</label>
                            <input type="text" name="pagibig" class="form-control" id="pagibig">
                            <span class="text-danger error-text pagibig_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-12">
                            <label for="txt-time">Philhealth Number:</label>
                            <input type="text" name="philhealth" class="form-control" id="philhealth">
                            <span class="text-danger error-text philhealth_error"></span>
                        </div>
                    </div>
                    <div class="form-row first-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center">
                                <i class='bx bxs-time-five'></i>
                                Schedule
                            </span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Start:</label>
                            <input type="time" name="sched_start" class="form-control" id="sched_start">
                            <span class="text-danger error-text sched_start_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="txt-time">End:</label>
                            <input type="time" name="sched_end" class="form-control" id="sched_end">
                            <span class="text-danger error-text sched_end_error"></span>
                        </div>
                    </div>
                    <div class="form-row row ">
                        <div class="col-xl-12">
                            <label for="">Employee Shift:</label>
                            <select name="employee_shift" class="form-select" id="employee_shift">
                                <option value="">Select Employee Shift</option>
                                <option value="Day">Day Shift</option>
                                <option value="Night">Night Shift</option>
                            </select>
                            <span class="text-danger error-text employee_shift_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <label for="">Days:</label>
                        <div class="days-wrp">
                            <label>
                                <input type="checkbox" class="checkedit" name="work_days[]" value="Sunday" id="Sunday"><span>S</span>
                            </label>
                            <label>
                                <input type="checkbox" class="checkedit" name="work_days[]" value="Monday" id="Monday"><span>M</span>
                            </label>
                            <label>
                                <input type="checkbox" class="checkedit" name="work_days[]" value="Tuesday" id="Tuesday"><span>T</span>
                            </label>
                            <label>
                                <input type="checkbox" class="checkedit" name="work_days[]" value="Wednesday" id="Wednesday"><span>W</span>
                            </label>
                            <label>
                                <input type="checkbox" class="checkedit" name="work_days[]" value="Thursday" id="Thursday"><span>TH</span>
                            </label>
                            <label>
                                <input type="checkbox" class="checkedit" name="work_days[]" value="Friday" id="Friday"><span>F</span>
                            </label>
                            <label>
                                <input type="checkbox" class="checkedit" name="work_days[]" value="Saturday" id="Saturday"><span>S</span>
                            </label>
                        </div>
                        <div>
                            <span class="text-danger error-text work_days_error"></span>
                        </div>
                    </div>

                    <div class="form-row first-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center">
                                <i class='bx bxs-time-five'></i>
                                Break Time Schedule
                            </span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Start:</label>
                            <input type="time" name="breaktime_start" class="form-control" id="breaktime_start">
                            <span class="text-danger error-text breaktime_start_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="txt-time">End:</label>
                            <input type="time" name="breaktime_end" class="form-control" id="breaktime_end">
                            <span class="text-danger error-text breaktime_end_error"></span>
                        </div>
                    </div>
                    <div class="form-row first-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center">
                                <i class='bx bxs-user-detail'></i>
                                Personal Information
                            </span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Birthday:</label>
                            <input type="date" name="employee_birthday" class="form-control" id="employee_birthday">
                            <span class="text-danger error-text employee_birthday_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="txt-time">Address:</label>
                            <input type="text" name="employee_address" class="form-control" id="employee_address" >
                            <span class="text-danger error-text employee_address_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-12">
                            <label for="txt-time">Contact Number:</label>
                            <input type="tel" name="employee_contact_number" class="form-control" id="employee_contact_number"
                                    pattern="[0-9]{11}"
                                    oninvalid="this.setCustomValidity('Make sure to follow the pattern EX: 099055*****')"
                                    oninput="this.setCustomValidity('')">
                            <span class="text-danger error-text employee_contact_number_error"></span>
                        </div>
                    </div>

                    <div class="form-row first-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center">
                                <i class='bx bxs-info-circle'></i>
                               In Case of Emergency
                            </span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Name:</label>
                            <input type="text" name="emergency_contact_name" class="form-control" id="emergency_contact_name">
                            <span class="text-danger error-text emergency_contact_name_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="txt-time">Contact Number:</label>
                            <input type="text" name="emergency_contact_number" class="form-control" id="emergency_contact_number"
                                    pattern="[0-9]{11}"
                                    oninvalid="this.setCustomValidity('Make sure to follow the pattern EX: 099055*****')"
                                    oninput="this.setCustomValidity('')">
                            <span class="text-danger error-text emergency_contact_number_error"></span>
                        </div>
                    </div>
                    <div class="mt-4 gap-2 d-flex justify-content-end">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="update_employee_btn">Update Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}








<style>

    .vibrate-1 {
        -webkit-animation: vibrate-1 0.9s linear infinite both;
                animation: vibrate-1 0.9s linear infinite both;
    }

    @-webkit-keyframes vibrate-1 {
    0% {
        -webkit-transform: translate(0);
                transform: translate(0);
    }
    20% {
        -webkit-transform: translate(-2px, 2px);
                transform: translate(-2px, 2px);
    }
    40% {
        -webkit-transform: translate(-2px, -2px);
                transform: translate(-2px, -2px);
    }
    60% {
        -webkit-transform: translate(2px, 2px);
                transform: translate(2px, 2px);
    }
    80% {
        -webkit-transform: translate(2px, -2px);
                transform: translate(2px, -2px);
    }
    100% {
        -webkit-transform: translate(0);
                transform: translate(0);
    }
    }
    @keyframes vibrate-1 {
    0% {
        -webkit-transform: translate(0);
                transform: translate(0);
    }
    20% {
        -webkit-transform: translate(-2px, 2px);
                transform: translate(-2px, 2px);
    }
    40% {
        -webkit-transform: translate(-2px, -2px);
                transform: translate(-2px, -2px);
    }
    60% {
        -webkit-transform: translate(2px, 2px);
                transform: translate(2px, 2px);
    }
    80% {
        -webkit-transform: translate(2px, -2px);
                transform: translate(2px, -2px);
    }
    100% {
        -webkit-transform: translate(0);
                transform: translate(0);
    }
    }

    #scrollToTopButton {
    display: none;
    position: fixed;
    bottom: 50px;
    right: 20px;
    z-index: 999;
    background-color: #bc3d4f;
    color: #fff;
    border: none;
    padding: 10px 20px;
    padding-top: 30px;
    border-radius: 50px;
    cursor: pointer;
    }

    #scrollToTopButton:hover {
    background-color: #d95569;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.0.0/html2canvas.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>

    window.onscroll = function() {
        scrollFunction();
    };

    function scrollFunction() {
    const button = document.getElementById("scrollToTopButton");
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        button.style.display = "block";
    } else {
        button.style.display = "none";
    }
    }

    function scrollToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
    }

</script>


@section('page-scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script>

    document.getElementById("btnPrint").onclick = function () {

        printElement(document.getElementById("printThis"));
    }

    function printElement(elem) {

        var domClone = elem.cloneNode(true);
        var $printSection = document.getElementById("printSection");

        if (!$printSection) {
            var $printSection = document.createElement("div");
            $printSection.id = "printSection";
            document.body.appendChild($printSection);
        }

        $printSection.innerHTML = "";
        $printSection.appendChild(domClone);
        window.print();
    }
</script>
<script>
    $(document).ready(function () {


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

         AllPayroll();

         function AllPayroll(){
                $.ajax({
                    url: '{{ route('get_payroll1') }}',
                    method: 'GET',
                    success: function(response) {
                        $("#show_payroll").html(response);
                        $("#show_all_payroll").DataTable({
                            order: [[ 0, 'desc' ]]
                        });
                    }
                });
            }

            $(function() {
            $("#start_date").datepicker({
                "dateFormat": "yy-mm-dd"
            });
            $("#end_date").datepicker({
                "dateFormat": "yy-mm-dd"
            });
        });

        // show emp-modal
        $(document).on('click', '.modalpayslip', function (e) {
            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('view_payslip1') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },

                success: function(response) {

                    var from = moment(response.start_date).format('MMM DD, YYYY');
                    var to = moment(response.end_date).format('MMM DD, YYYY');
                    var birthday = moment(response.employee.employee_birthday).format('MMM DD, YYYY');
                    var paydate = moment(response.pay_date).format('MMM DD, YYYY'); 
                    

                    var reg = response.regular_holiday;
                    var spe = response.special_holiday;
                    var rdsh = response.restday_special_holiday;
                    var rdrh = response.restday_regular_holiday;
                    var total = parseFloat(reg) + parseFloat(spe) + parseFloat(rdsh) + parseFloat(rdrh);

                    //Add all OT
                    var overtime = response.overtime;
                    var restday_ot = response.restday_overtime;
                    var special_holiday_ot = response.special_holiday_overtime;
                    var regular_holiday_ot =  response.regular_holiday_overtime;
                    var restday_special_holiday_ot = response.restday_special_holiday_overtime;
                    var restday_regular_holiday_ot = response.restday_regular_holiday_overtime;
                    var overtime_total =  parseFloat(overtime) + parseFloat(restday_ot) + parseFloat(special_holiday_ot) + parseFloat(regular_holiday_ot) + parseFloat(restday_special_holiday_ot) + parseFloat(restday_regular_holiday_ot);
                  
                  
                  
                    $("#payslip_id").val(response.id);
                    $("#from").html( `${from}`);
                    $("#to").html( `${to}`);
                    $("#payment_type").html( `${response.payment_type}`);
                    $("#name").html( `${response.employee_name}`);
                    $("#employee_num").html( `${response.employee_number}`);
                    $("#bday").html( `${birthday}`);
                    $("#department").html( `${response.employee.employee_department}`);
                    $("#position").html( `${response.employee.employee_position}`);
                    $("#paydate").html( `${paydate}`);
                    // $("#work_hours").html( `${response.total_workhour}`);
                    $("#total_overtime").html( `${overtime_total}`);
                    $("#late_undertime").html( `${response.late_undertime}`);
                    $("#allowance").html( `${response.allowance}`);
                    $("#sss_deduction").html( `${response.sss_deduction}`);
                    $("#philhealth_deduction").html( `${response.philhealth_deduction}`);
                    $("#pagibig_deduction").html( `${response.pag_ibig_deduction}`);
                    $("#net_salary").html( `${response.net_pay}`);
                    $("#total_deduction").html( `${response.total_deduction}`);
                    $("#employee_base_salary").html( `${response.employee_base_salary}`);
                    $("#employee_cash_advance").html( `${response.cash_advance}`);
                    $("#night_differential").html( `${response.night_diff}`);
                    $("#absent").html( `${response.employee_absent}`);
                    $("#gross").html( `${response.gross}`);
                    $("#employee_restday").html( `${response.restday}`);
                    $("#employee_reg_spe").html( `${total}`);

                }
            });
        });

        //Edit the Employee
        $(document).on('click','.editIcon', function(e) {

            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('edit_employee1') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },

                success: function(response) {

                    $("#emp_id").val(response.id);
                    $("#user_id").val(response.user_id);
                    $("#employee_name").val(response.employee_name);
                    $("#image").val(response.image);
                    $("#employee_no").val(response.employee_no);
                    $("#base_salary").val(response.base_salary);
                    $("#monthly_rate").val(response.monthly_rate);
                    $("#employee_position").val(response.employee_position);
                    $("#employee_department").val(response.employee_department);
                    $("#sched_start").val(response.sched_start);
                    $("#sched_end").val(response.sched_end);
                    $("#breaktime_start").val(response.breaktime_start);
                    $("#breaktime_end").val(response.breaktime_end);
                    $("#employee_shift").val(response.employee_shift);
                    $("#date_hired").val(response.date_hired);
                    $("#employee_birthday").val(response.employee_birthday);
                    $("#employee_address").val(response.employee_address);
                    $("#sss").val(response.sss_number);
                    $("#pagibig").val(response.pagibig_number);
                    $("#philhealth").val(response.philhealth_number);
                    $("#employee_allowance").val(response.employee_allowance);
                    $("#employee_contact_number").val(response.employee_contact_number);
                    $("#emergency_contact_name").val(response.emergency_contact_name);
                    $("#emergency_contact_number").val(response.emergency_contact_number);

                    //validate if there has an image to the database
                    // if no image the result to the modal is null
                    // if the employee upload image the picture appreared in the modal
                    var avatar = response.image;
                    if(avatar != null)
                    {

                        $("#image").html( `<img src="storage/employee/images/${response.image}" class="modal_image">`);

                    }
                    else
                    {
                        //if the employee has no image the display of image will be none;
                        $("#image").html( `<img src="storage/employee/images/${response.image}" class="modal_image" style="display:none">`);
                    }

                    //getting the exact result of work days
                    var myarray = response.work_days.split(',');
                    $.each(myarray, function (index, value) {

                        console.log(value);

                        if( $("#Sunday").val()=== value){

                            $("#Sunday").prop('checked', true);
                        }
                        else if( $("#Monday").val()=== value){

                            $("#Monday").prop('checked', true);
                        }
                        else if( $("#Tuesday").val()=== value){

                            $("#Tuesday").prop('checked', true);
                        }
                        else if( $("#Wednesday").val()=== value){

                            $("#Wednesday").prop('checked', true);
                        }
                        else if( $("#Thursday").val()=== value){

                            $("#Thursday").prop('checked', true);
                        }
                        else if( $("#Friday").val()=== value){

                            $("#Friday").prop('checked', true);
                        }
                        else if( $("#Saturday").val()=== value){

                            $("#Saturday").prop('checked', true);
                        }
                    });


                }
            });
        });

        // update employee ajax request
        $("#update").on('submit',function(e) {

            e.preventDefault();
            $("#update_employee_btn").text('Updating...');
            $('#update_employee_btn').attr("disabled", true);
            var frm = this;

            $.ajax({

                url:$(frm).attr('action'),
                method:$(frm).attr('method'),
                data: new FormData(frm),
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function(){
                    //Before Sending The Form
                    $(frm).find('span.error-text').text('')
                },

                success: function(response) {
                    if (response.code == 0)
                    {
                        $('#update_employee_btn').removeAttr("disabled");
                        $.each(response.error, function(prefix, val){
                            $(frm).find('span.'+prefix+'_error').text(val[0]);
                        });
                        $('#update_employee_btn').text('Update Employee');
                    }
                    else
                    {
                        $(frm)[0].reset();
                        $("#update_employee_btn").text('Update Employee');
                        $('#update_employee_btn').removeAttr("disabled");
                        $("#EditEmployee").modal('hide');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated Successfully',
                            showConfirmButton: false,
                            timer: 1700,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top',
                            iconColor: 'white',
                            customClass: {
                                popup: 'colored-toast'
                            },
                        })
                        location.reload();
                    }
                    //To Remove error message once the mocal close and open again
                    $('#close').on('click', function () {
                        $(frm).find('span.error-text').text('')
                    });
                    
                }
            });
        });
        // end of update

        // update password
        $("#update_password").on('submit',function(e) {

            e.preventDefault();
            $("#update_password_btn").text('Updating...');
            $('#update_password_btn').attr("disabled", true);
            var pass = this;
            $.ajax({
                url:$(pass).attr('action'),
                method:$(pass).attr('method'),
                data: new FormData(pass),
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function(){
                //Before Sending The Form
                $(pass).find('span.error-text').text('')
                },

                success: function(response) {

                    if (response.code == 0)
                    {
                        $('#update_password_btn').removeAttr("disabled");
                        $.each(response.error, function(prefix, val){
                        $(pass).find('span.'+prefix+'_error').text(val[0]);
                        });
                        $('#update_password_btn').text('Submit');
                    }
                    else
                    {
                        $("#update_password_btn").text('Submit');
                        $('#update_password_btn').removeAttr("disabled");

                        $(pass)[0].reset();

                        Swal.fire({
                            icon: 'success',
                            title: 'Updated Successfully',
                            showConfirmButton: false,
                            timer: 1700,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top',
                            iconColor: 'white',
                            customClass: {
                                popup: 'colored-toast'
                            },
                        })
                    }

                }
            });
        });

    });
</script>

@endsection
@endsection