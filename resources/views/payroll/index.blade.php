@extends('layouts.main')
@section('main-content')
<style>
    .table-container {
      width: 100%;
      overflow-x: auto;
    }
    #records {
      width: 100%;
      /* table styles */
    }
    .dataTables_filter{
      display:none;
    }
  </style>
<div class="page-row row">
    <div class="col-xl-6">
        <h2 class="page-heading">Payroll</h2>
    </div>
    <div class="col-xl-6">
        <ul class="nav nav-tabs d-flex justify-content-end" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="tab active d-flex align-items-center" id="employee_payslip-tab" data-bs-toggle="tab" href="#employee_payslip"
                    role="tab" aria-controls="employee_payslip" aria-selected="false">
                    <i class='bx bx-money-withdraw section-icon'></i>
                     Payslip
                </a>
            </li>
            <li class="nav-item">
                <a class="tab d-flex align-items-center ms-2" id="employee_payroll-tab" data-bs-toggle="tab" href="#employee_payroll"
                    role="tab" aria-controls="employee_payroll" aria-selected="false">
                    <i class='bx bx-money'></i>
                     All Payroll
                </a>
            </li>
        </ul>
    </div>
</div>


<div class="page-container row">
    <div class="tab-content" id="myTabContent" style="margin-top: 30px;">

        {{-- payslip--}}

        <div class="tab-pane fade show active" id="employee_payslip" role="tabpanel" aria-labelledby="employee_payslip-tab">
            <div class="row">
                <div class="col-xl-8">
                    <div class="section-container">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-money-withdraw section-icon'></i>
                            <h5 class="section-header">Payroll Records</h5>
                        </div>
                        <div class="row table-row" id="show_payroll">
            
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 right-pane">
                    @if(Auth::user()->hasRole('accounting'))
                        <div class="card employee-card">
                            <div class="card-body">
                                <form action="{{ route('submit_payroll') }}" method="POST" id="payroll_submit"  enctype="multipart/form-data">
                                    @csrf
            
                                    <div class="d-flex align-items-center">
                                        <i class='bx bx-money-withdraw section-icon'></i>
                                        <h5 class="section-header">Payroll</h5>
                                    </div>
            
                                    <div class="form-row first-row">
                                        <div class="col-xl-12" hidden>
                                            <label for="txt-time">Employee Name:</label>
                                            <input type="text" name="employee_name" class="form-control" id="employee_name">
                                        </div>
                                        <div class="col-xl-12">
                                            <label for="txt-time">Employee Name:</label>
                                            <select name="" id="data" class="form-select form-control">
            
                                            <option value="">--Select an option--</option>
            
                                            @foreach($employees as $option)
                                              <option value="{{ $option->id }}">{{ $option->employee_name }}</option>
                                            @endforeach
            
                                            </select>
                                            <span class="text-danger error-text employee_name_error"></span>
                                        </div>
                                    </div>
                                     <div class="form-row row">
                                        <div class="col-xl-6">
                                            <label for="txt-time">Adjustment Addition:</label>
                                            <input type="text" name="" class="form-control">
                                            <span class="text-danger error-text "></span>
                                        </div>
                                        <div class="col-xl-6">
                                            <label for="txt-time">Adjustment Deduction:</label>
                                            <input type="text"  name="" class="form-control">
                                            <span class="text-danger error-text "></span>
                                        </div>
                                    </div>
                                    <div class="form-row row">
                                        <div class="col-xl-6">
                                            <label for="txt-time">Payment Type:</label>
                                            <input type="text" name="payment_type" class="form-control" >
                                            <span class="text-danger error-text payment_type_error"></span>
                                        </div>
                                        <div class="col-xl-6">
                                            <label for="txt-time">Pay Date:</label>
                                            <input type="date"  name="pay_date" class="form-control" >
                                            <span class="text-danger error-text pay_date_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-row row">
                                        <div class="col-xl-6">
                                            <label for="txt-time">Cash Advance:</label>
                                            <input type="number" min="0" step="any" name="cash_advance" class="form-control" >
                                            <span class="text-danger error-text cash_advance_error"></span>
                                        </div>
                                        <div class="col-xl-6">
                                            <label for="txt-time">Allowance Per Cutoff:</label>
                                            <input type="number" min="0" step="any" name="allowance" class="form-control" id="employee_allowances" readonly>
                                            <span class="text-danger error-text allowance_error"></span>
                                        </div>
                                    </div>
                                
                                    <div class="form-row row">
                                        <div class="col-xl-6">
                                            <label for="txt-time">Start Date:</label>
                                            <input type="date" name="start_date" class="form-control">
                                            <span class="text-danger error-text start_date_error"></span>
                                        </div>
                                        <div class="col-xl-6">
                                            <label for="txt-time">End Date:</label>
                                            <input type="date" name="end_date" class="form-control">
                                            <span class="text-danger error-text end_date_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-row row">
                                        <div class="col-xl-6">
                                            <label for="txt-time">Employee No.:</label>
                                            <input type="text" name="employee_number" class="form-control" id="employee_number" readonly>
                                        </div>
                                        <div class="col-xl-6">
                                            <label for="txt-time">Department:</label>
                                            <input type="text" name="" class="form-control" id="employee_dept" readonly>
                                        </div>
                                    </div>
                                    <div class="form-row row">
                                        <div class="col-xl-6">
                                            <label for="txt-time">Base Salary:</label>
                                            <input type="text" name="base_salary" class="form-control" id="base_salary" readonly>
                                        </div>
                                        <div class="col-xl-6">
                                            <label for="txt-time">Daily Rate:</label>
                                            <input type="text" name="daily_rate" class="form-control" id="employee_daily_rate" readonly>
                                        </div>
                                    </div>
                                    <div class="form-row row payroll" >
                                        <div class="col-xl-12">
                                            <label for="txt-time">Rate per Hours:</label>
                                            <input type="text" name="rate_per_hour" class="form-control" id="employee_per_hour_rate" readonly>
                                        </div>
                                    </div>
                                    <div class="form-row row payroll">
                                        <div class="col-xl-12">
                                            <label for="txt-time">Rate per Minutes:</label>
                                            <input type="text" name="rate_per_minutes" class="form-control" id="employee_per_minute_rate" readonly>
                                        </div>
                                    </div>
                                    <div class="form-row row payroll">
                                    <div class="col-xl-12">
                                            <label for="txt-time">Rate per Seconds:</label>
                                            <input type="text" name="rate_per_seconds" class="form-control" id="employee_per_seconds_rate" readonly>
                                        </div>
                                    </div>
                                    <div class="form-row row payroll">
                                        <div class="col-xl-6">
                                            <label for="txt-time">Schedule Shift:</label>
                                            <input type="text" name="schedule_shift" class="form-control" id="schedule_shift" readonly>
                                            <span class="text-danger error-text shift_error"></span>
                                        </div>
                                        <div class="col-xl-6">
                                            <label for="txt-time">Monthly Rate:</label>
                                            <input type="text" name="monthly_rate" class="form-control" id="monthly_rate" readonly>
                                            <span class="text-danger error-text shift_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-row first-row">
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn-form d-flex align-items-center" id="payroll_btn">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <h4 class="text-center text-secondary my-5">Only Accounting can create payroll of the Employee.</h4>
                    @endif
                </div>
            </div>
        </div>


        {{-- All Payroll--}}

        <div class="tab-pane fade show" id="employee_payroll" role="tabpanel" aria-labelledby="employee_payroll-tab">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-container">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-money-withdraw section-icon'></i>
                            <h5 class="section-header">All Payroll Records</h5>
                        </div>
                        
                        {{-- <div class="row table-row" id="all_record">
            
                        </div>   --}}
                        @if(Auth::user()->hasRole(['accounting','CEO','HR','assistantHR']))
                            <div class="form-row row">
                                <div class="col-xl-4">
                                    <input type="text" class="form-control" id="start_date" placeholder="Start Date" readonly>
                                </div>
                                <div class="col-xl-4">
                                    <input type="text" class="form-control" id="end_date" placeholder="End Date" readonly>
                                </div>
                                <div class="col-xl-4">
                                    <button id="filter" class="btn btn-primary btn-sm">Filter</button>
                                    <button id="reset" class="btn btn-success btn-sm">Refresh</button>
                                </div>
                            </div>

                            <div class="table-container">
                                <table class="table table-striped table-hover border border-gray" id="records" >
                                    <thead>
                                        <tr>
                                            <th  class="text-center border border-gray"></th>
                                            <th  class="text-center border border-gray"></th>
                                            <th  class="text-center border border-gray"> </th>
                                            <th  class="text-center border border-gray"></th>
                                            <th  class="text-center border border-gray"></th>
                                            <th  class="text-center border border-gray"> </th>
                                            <th  class="text-center border border-gray"> </th>
                                            <th  class="text-center border border-gray"> </th>
                                            <th  class="text-center border border-gray"> </th>
                                            <th  class="text-center border border-gray" colspan="4" style="background-color:yellow">SSS</th>
                                            <th  class="text-center border border-gray" colspan="4" style="background-color:yellow">Pag Ibig</th>
                                            <th  class="text-center border border-gray" colspan="3" style="background-color:yellow">Philhealth</th>
                                            <th  class="text-center border border-gray" style="background-color:yellow">TAX</th>
                                            <th  class="text-center border border-gray" style="background-color:rgb(34, 227, 118)">Allowance</th>
                                            <th  class="text-center border border-gray" style="background-color:rgb(93, 93, 93)">13 Month Salary</th>
                                            <th  class="text-center border border-gray" colspan="2" style="background-color:rgb(226, 248, 113)">Filled w/Pay Leaves</th>
                                            <th  class="text-center border border-gray" colspan="13" style="background-color:rgba(237, 177, 13, 0.719)">OVERTIME</th>
                                            <th  class="text-center border border-gray" colspan="13" style="background-color:rgba(255, 170, 0, 0.977)">NIGHT DIFFERENTIAL</th>
                                            <th  class="text-center border border-gray" colspan="7" style="background-color: #FFD966">RESTDAY</th>
                                            <th  class="text-center border border-gray" colspan="5" style="background-color: #FFE599">HOLIDAYS</th>
                                            <th  class="text-center border border-gray" style="background-color: #C9DAF8">ADJUSTMENTS</th>
                                            <th  class="text-center border border-gray" style="background-color: #93C47D">TOTAL ADDIDTIONALS</th>
                                            <th  class="text-center border border-gray" style="background-color: #A4C2F4">GROSS SALARY</th>
                                            <th  class="text-center border border-gray" colspan="6" style="background-color: #6D9EEB">ATTENDANCE</th>
                                            <th  class="text-center border border-gray" style="background-color: #6D9EEB">GOV. CONTRIBUTION</th>
                                            <th  class="text-center border border-gray" style="background-color: #B4A7D6">CASH ADVANCE</th>
                                            <th  class="text-center border border-gray" style="background-color: #BA91A5">ADJUSTMENT</th>
                                            <th  class="text-center border border-gray" style="background-color: #DD7E6B">TOTAL DEDUCTION</th>
                                            <th  class="text-center border border-gray" style="background-color: #FF9900">NET SALARY</th>
                                            <th  class="text-center border border-gray" style="background-color: #D9D9D9">DOLLAR VALUE</th>
                                        </tr>
                                        <tr>
                                            <th  class="text-center border border-gray">ID</th>
                                            <th  class="text-center border border-gray">Name</th>
                                            <th  class="text-center border border-gray">Date Hired</th>
                                            <th  class="text-center border border-gray">Department</th>
                                            <th  class="text-center border border-gray">Positions</th>
                                            <th  class="text-center border border-gray">Monthly Base</th>
                                            <th  class="text-center border border-gray">Daily Rate</th>
                                            <th  class="text-center border border-gray">Hourly Rate</th>
                                            <th  class="text-center border border-gray">Base Salary</th>
                                            <th  class="text-center border border-gray">EE</th>
                                            <th  class="text-center border border-gray">ER</th>
                                            <th  class="text-center border border-gray" >SSS Loan</th>
                                            <th  class="text-center border border-gray" >Total</th>
                                            <th  class="text-center border border-gray">EE</th>
                                            <th  class="text-center border border-gray">ER</th>
                                            <th  class="text-center border border-gray">Pag ibig Loan</th>
                                            <th  class="text-center border border-gray">Total</th>
                                            <th  class="text-center border border-gray">EE</th>
                                            <th  class="text-center border border-gray">ER</th>
                                            <th  class="text-center border border-gray">Total</th>
                                            <th  class="text-center border border-gray"></th>
                                            <th  class="text-center border border-gray"></th>
                                            <th  class="text-center border border-gray"></th>
                                            <th  class="text-center border border-gray">VL</th>
                                            <th  class="text-center border border-gray">SL</th>
                                            <th  class="text-center border border-gray">OT</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">RDOT</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">SHOT</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">RDSHOT</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">RHOT</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">RDRHOT</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">Total Amount</th>
                                            <th  class="text-center border border-gray">ND</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">RDND</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">SHND</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">RDSHND</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">RHND</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">RDRHND</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">Total Amount</th>
                                            <th  class="text-center border border-gray">RD</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">RDSH</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">RDRH</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">Total Amount</th>
                                            <th  class="text-center border border-gray">SH</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">RH</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">Total Amount</th>
                                            <th  class="text-center border border-gray">Total Amount</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">Total Amount</th>
                                            <th  class="text-center border border-gray">L/UT</th>
                                            <th  class="text-center border border-gray">A</th>
                                            <th  class="text-center border border-gray">VTO</th>
                                            <th  class="text-center border border-gray">LWP</th>
                                            <th  class="text-center border border-gray">S</th>
                                            <th  class="text-center border border-gray">Total Amount</th>
                                            <th  class="text-center border border-gray">Total Amount</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">Total Amount</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                            <th  class="text-center border border-gray">Amount</th>
                                
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <h4 class="text-center text-secondary my-5">Only Accounting can access this page.</h4>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

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


{{-- END OF MODAL --}}
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
                    url: '{{ route('get_payroll') }}',
                    method: 'GET',
                    success: function(response) {
                        $("#show_payroll").html(response);
                        $("#show_all_payroll").DataTable({
                            order: [[ 0, 'desc' ]]
                        });
                    }
                });
            }

            // All_payroll_record();
            // function All_payroll_record(){
            //     $.ajax({
            //         url: '{{ route('get_payroll_record') }}',
            //         method: 'GET',
            //         success: function(response) {
            //             $("#all_record").html(response);
            //             $("#all_payroll_record").DataTable({
            //                 dom: 'Bfrtip',
                           
            //                 buttons: {
            //                     buttons: [
                                    
            //                         {   extend: 'excel',  
            //                             text: 'Export to excel',
            //                             className: 'btn btn-primary btn-sm',
            //                             title: 'Theorem Management System',
            //                             header: true,
            //                             footer: false,
                                        
            //                          }
            //                     ]
            //                 },
                           
            //                 scrollX: true,
            //                 order: [[ 0, 'desc' ]]
            //             });
            //         }
            //     });
            // }

            $(function() {
            $("#start_date").datepicker({
                "dateFormat": "yy-mm-dd"
            });
            $("#end_date").datepicker({
                "dateFormat": "yy-mm-dd"
            });
        });


        function fetch(start_date, end_date) {
            
            $.ajax({
                url: "{{ route('get_payroll_record') }}",
                type: "GET",
                data: {
                    start_date: start_date,
                    end_date: end_date
                },
                dataType: "json",
                success: function(data) {
                    
                    // Datatables
                    var i = 1;

                    $('#records').DataTable({
                       
        
                        // dom: 'Bfrtip',
                        dom: 'Blfrtip',
                        buttons: [

                            { 
                              extend: 'excelHtml5',
                              footer: true ,
                              text: 'Export to excel',
                              className: 'btn btn-primary btn-sm mb-2',
                              title: 'Theorem Management System',
                              header: true,
                              
                              
                            },
                            
                        ],
                        
                        "data": data.payrolls,
                        "columns": [
                            {
                                "data": "id",
                                "render": function(data, type, row, meta) {
                                    return i++;
                                }
                            },
                            {
                                "data": "name",
                                "render": function(data, type, row, meta) {
                                    return `${row.employee_name}`;
                                }
                            },
                            {
                                "data": "date_hired",
                                "render": function(data, type, row, meta) {
                                    return moment(row.date_hired).format('MMMM-DD-YYYY');
                                }
                            },
                            {
                                "data": "Department",
                                "render": function(data, type, row, meta) {
                                    return `${row.employee.employee_department}`;
                                }
                            },
                            {
                                "data": "Position",
                                "render": function(data, type, row, meta) {
                                    return `${row.employee.employee_position}`;
                                }
                            },
                            {
                                "data": "monthlysalary",
                                "render": function(data, type, row, meta) {
                                    return `${row.employee.base_salary}`;
                                }
                            },
                            {
                                "data": "dailyrate",
                                "render": function(data, type, row, meta) {
                                    return `${row.employee.daily_rate}`;
                                }
                            },
                            {
                                "data": "rate_per_hour",
                                "render": function(data, type, row, meta) {
                                    return `${row.sss_deduction}`;
                                }
                            },
                            {
                                "data": "basesalary",
                                "render": function(data, type, row, meta) {
                                    return `${row.employee.base_salary / 2}`;
                                }
                            },
                            {
                                "data": "sss_deduction",
                                "render": function(data, type, row, meta) {
                                    return `${row.sss_deduction}`; //EE
                                }
                            },
                            {
                                "data": "sss_deduction",
                                "render": function(data, type, row, meta) {
                                    return ''; // ER
                                }
                            },
                            {
                                "data": "sss_loan",
                                "render": function(data, type, row, meta) {
                                    return ''; // LOAN
                                }
                            },
                            {
                                "data": "sss_deduction",
                                "render": function(data, type, row, meta) {
                                    return `${row.sss_deduction}`; // TOTAL
                                }
                            },
                            {
                                "data": "pag_ibig_deduction",
                                "render": function(data, type, row, meta) {
                                    return `${row.pag_ibig_deduction}`; // EE
                                }
                            },
                            {
                                "data": "pag_ibig_deduction",
                                "render": function(data, type, row, meta) {
                                    return ''; // ER
                                }
                            },
                            {
                                "data": "pag_ibig_deduction",
                                "render": function(data, type, row, meta) {
                                    return ''; // loan
                                }
                            },
                            {
                                "data": "pag_ibig_deduction",
                                "render": function(data, type, row, meta) {
                                    return `${row.pag_ibig_deduction}`; // TOTAL
                                }
                            },
                            {
                                "data": "philhealth_deduction",
                                "render": function(data, type, row, meta) {
                                    return `${row.philhealth_deduction}`; // EE
                                }
                            },
                            {
                                "data": "philhealth_deduction",
                                "render": function(data, type, row, meta) {
                                    return ''; // ER
                                }
                            },
                            {
                                "data": "philhealth_deduction",
                                "render": function(data, type, row, meta) {
                                    return `${row.philhealth_deduction}`; // TOTAL
                                }
                            },
                            {
                                "data": "tax",
                                "render": function(data, type, row, meta) {
                                    return ''; // tax
                                
                                }
                            },
                            {
                                "data": "allowance",
                                "render": function(data, type, row, meta) {
                                    return `${row.allowance}`; // TOTAL
                                
                                }
                            },
                            {
                                "data": "13month",
                                "render": function(data, type, row, meta) {
                                    return ''; // 13month
                                
                                }
                            },
                            {
                                "data": "VL",
                                "render": function(data, type, row, meta) {
                                    return ''; // VL
                                
                                }
                            },
                            {
                                "data": "SL",
                                "render": function(data, type, row, meta) {
                                    return ''; // SL
                                
                                }
                            },
                            {
                                "data": "overtime",
                                "render": function(data, type, row, meta) {
                                    return ''; //overtime
                                
                                }
                            },
                            {
                                "data": "overtime",
                                "render": function(data, type, row, meta) {
                                    return `${row.overtime}`; //amount overtime
                                
                                }
                            },
                            {
                                "data": "restday_overtime",
                                "render": function(data, type, row, meta) {
                                    return ''; //rdot hrs
                                
                                }
                            },
                            {
                                "data": "restday_overtime",
                                "render": function(data, type, row, meta) {
                                    return `${row.restday_overtime}`; //amount overtime
                                
                                }
                            },
                            {
                                "data": "special_holiday_overtime",
                                "render": function(data, type, row, meta) {
                                    return ''; //shot hrs
                                
                                }
                            },
                            {
                                "data": "special_holiday_overtime",
                                "render": function(data, type, row, meta) {
                                    return `${row.special_holiday_overtime}`; //amount overtime
                                
                                }
                            },
                            {
                                "data": "restday_special_holiday_overtime",
                                "render": function(data, type, row, meta) {
                                    return ''; //rdshot hrs
                                
                                }
                            },
                            {
                                "data": "restday_special_holiday_overtime",
                                "render": function(data, type, row, meta) {
                                    return `${row.restday_special_holiday_overtime}`; //amount overtime
                                
                                }
                            },
                            {
                                "data": "regular_holiday_overtime",
                                "render": function(data, type, row, meta) {
                                    return ''; //rhot hrs
                                
                                }
                            },
                            {
                                "data": "regular_holiday_overtime",
                                "render": function(data, type, row, meta) {
                                    return `${row.regular_holiday_overtime}`; //amount overtime
                                
                                }
                            },
                            {
                                "data": "restday_regular_holiday_overtime",
                                "render": function(data, type, row, meta) {
                                    return ''; //rdrhot hrs
                                
                                }
                            },
                            {
                                "data": "restday_regular_holiday_overtime",
                                "render": function(data, type, row, meta) {
                                    return `${row.restday_regular_holiday_overtime}`; //amount overtime
                                
                                }
                            },
                            {
                                "data": "total_overtime",
                                "render": function(data, type, row, meta) {
                                    
                                    return ''; //over all total
                                
                                }
                            },
                            {
                                "data": "night_differential",
                                "render": function(data, type, row, meta) {
                                      return `${row.night_diff}`; 
                                
                                }
                            },
                            {
                                "data": "night_differential",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "night_differential",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "night_differential",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                            
                                }
                            },
                            {
                                "data": "night_differential",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "night_differential",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "night_differential",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "night_differential",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "night_differential",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "night_differential",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "night_differential",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "night_differential",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "night_differential",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "restday",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "restday",
                                "render": function(data, type, row, meta) {
                                    return `${row.restday}`; //total
                                
                                }
                            },
                            {
                                "data": "restday_special_holiday",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "restday",
                                "render": function(data, type, row, meta) {
                                    return `${row.restday_special_holiday}`; //total
                                
                                }
                            },
                            {
                                "data": "restday_regular_holiday",
                                "render": function(data, type, row, meta) {
                                    return ''; 
                                
                                }
                            },
                            {
                                "data": "restday",
                                "render": function(data, type, row, meta) {
                                    return `${row.restday_regular_holiday}`; //total
                                
                                }
                            },
                            {
                                "data": "restday_regular_holiday",
                                "render": function(data, type, row, meta) {
                                    return '';  // overall total
                                
                                }
                            },
                            {
                                "data": "special_holiday",
                                "render": function(data, type, row, meta) {
                                    return ''; //hrstotal
                                
                                }
                            },
                            {
                                "data": "special_holiday",
                                "render": function(data, type, row, meta) {
                                    return `${row.special_holiday}`  // amount total
                                
                                }
                            },
                            {
                                "data": "regular_holiday",
                                "render": function(data, type, row, meta) {
                                    return ''; //hrstotal
                                
                                }
                            },
                            {
                                "data": "regular_holiday",
                                "render": function(data, type, row, meta) {
                                    return `${row.regular_holiday}`  // amount total
                                
                                
                                }
                            },
                            {
                                "data": "regular_holiday",
                                "render": function(data, type, row, meta) {
                                    return ''; //over all total
                                
                                }
                            },
                            {
                                "data": "adjustment",
                                "render": function(data, type, row, meta) {
                                    return ''; // total
                                
                                }
                            },
                            {
                                "data": "total_addidtional",
                                "render": function(data, type, row, meta) {
                                    return ''; //total
                                
                                }
                            },
                            {
                                "data": "gross",
                                "render": function(data, type, row, meta) {
                                    return `${row.gross}`;  // amount total
                                
                                
                                }
                            },
                            {
                                "data": "late_undertime",
                                "render": function(data, type, row, meta) {
                                    return `${row.late_undertime}`;  // amount total
                                
                                
                                }
                            },
                            {
                                "data": "absent",
                                "render": function(data, type, row, meta) {
                                    return `${row.employee_absent}`;  // amount total
                                
                                
                                }
                            },
                            {
                                "data": "VTO",
                                "render": function(data, type, row, meta) {
                                    return ''; // total
                                
                                }
                            },
                            {
                                "data": "LWP",
                                "render": function(data, type, row, meta) {
                                    return ''; // total
                                
                                }
                            },
                            {
                                "data": "S",
                                "render": function(data, type, row, meta) {
                                    return ''; // total
                                
                                }
                            },
                            {
                                "data": "total_amount",
                                "render": function(data, type, row, meta) {
                                    return ''; // total
                                
                                }
                            },
                            {
                                "data": "gov_contribution",
                                "render": function(data, type, row, meta) {
                                    // var sss = row.sss_deduction;
                                    // var philhealth = row.philhealth_deduction;
                                    // var pagibig = row.pag_ibig_deduction;
                                    // var total = parseFloat(sss) + parseFloat(philhealth) + parseFloat(pagibig)
                                    // return `${total}`  // total
                                    return `${row.gov_contribution}`;
                                
                                }
                            },
                            {
                                "data": "cash_advance",
                                "render": function(data, type, row, meta) {
                                    return `${row.cash_advance}`;  // amount total
                                
                                
                                }
                            },
                            {
                                "data": "adjustment",
                                "render": function(data, type, row, meta) {
                                    return ''; // total
                                
                                }
                            },
                            {
                                "data": "total_deduction",
                                "render": function(data, type, row, meta) {
                                    return `${row.total_deduction}`;  // amount total
                                
                                
                                }
                            },
                            {
                                "data": "net_pay",
                                "render": function(data, type, row, meta) {
                                    return `${row.net_pay}`;  // amount total
                                
                                
                                }
                            },
                            {
                                "data": "dollar_value",
                                "render": function(data, type, row, meta) {
                                    return ''; // total
                                
                                }
                            },
                            // {
                            //     "data": "created_at",
                            //     "render": function(data, type, row, meta) {
                            //         return moment(row.created_at).format('MM-DD-YYYY');
                            //     }
                            // }
                        ],
                       
                       
                        "footerCallback": function(row, data, start, end, display) {
                            
                            var api = this.api();
                            var total_rate = api.column(12).data().reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b);
                            }, 0);
                           
                            $(api.column(12).footer()).html(`Total Rate: ${total_rate.toFixed(2)}`);
                           

                            var total = api.column(16).data().reduce(function(c, d) {
                                return parseFloat(c) + parseFloat(d);
                            }, 0);

                            $(api.column(16).footer()).html(`Total: ${total.toFixed(2)}`);

                            //TOTAL OF PHILHEALTH DEDUCTION
                            var philhealth_total_deduction = api.column(19).data().reduce(function(c, d) {
                                return parseFloat(c) + parseFloat(d);
                            }, 0);

                            $(api.column(19).footer()).html(`Total: ${philhealth_total_deduction.toFixed(2)}`);


                            //TOTAL OF GOV CONTRIBUTION
                            var Gov_Contribution = api.column(72).data().reduce(function(c, d) {
                                return parseFloat(c) + parseFloat(d);
                            }, 0);

                            $(api.column(72).footer()).html(`Total: ${Gov_Contribution.toFixed(2)}`);



                            //FOR THE TOTAL DEDUCTION
                            var Total_Deduction = api.column(75).data().reduce(function(c, d) {
                                return parseFloat(c) + parseFloat(d);
                            }, 0);

                            $(api.column(75).footer()).html(`Total: ${Total_Deduction.toFixed(2)}`);

                            //TOTAL OF NET SALARY
                            var Net_Salary_Total = api.column(76).data().reduce(function(c, d) {
                                return parseFloat(c) + parseFloat(d);
                            }, 0);

                            $(api.column(76).footer()).html(`Total: ${Net_Salary_Total.toFixed(2)}`);


                            
                          

                           
                        }
                    });

                }
            });
        }

       
      
        fetch();
        // Filter
        $(document).on("click", "#filter", function(e) {
            e.preventDefault();
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            if (start_date == "" || end_date == "") {
                alert("Both date required");
            } else {
                $('#records').DataTable().destroy();
                fetch(start_date, end_date);
            }
        });
        // Reset
        $(document).on("click", "#reset", function(e) {
            e.preventDefault();
            $("#start_date").val(''); // empty value
            $("#end_date").val('');
            $('#records').DataTable().destroy();
            fetch();
        });



        $("#payroll_submit").on('submit', function (e) {
            e.preventDefault();

            $("#payroll_btn").text('Submitting . . .');
            $('#payroll_btn').attr("disabled", true);
            var form = this;

            $.ajax({
                url:$(form).attr('action'),
                method:$(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: "json",
                contentType:false,
                beforeSend: function(){

                    $(form).find('span.error-text').text('');
                },
                success: function (response) {

                    if(response.code == 0){

                        $('#payroll_btn').removeAttr("disabled");
                        $.each(response.error, function(prefix, val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });
                        $("#payroll_btn").text('Submit');
                    }
                    else{

                        $(form)[0].reset();
                        $('#payroll_btn').removeAttr("disabled");
                        $('#payroll_btn').text('Submit');
                        AllPayroll();
                        // All_payroll_record();

                        Swal.fire({

                            icon: 'success',
                            title: 'Payroll Created Successfully',
                            showConfirmButton: false,
                            timer: 1700,
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

        $('#data').on('change', function() {

            var id = $(this).val();

            if(id){
                $.ajax({
                    url: "{{ route('view_employee') }}",
                    type: 'GET',
                    data: { id: id },
                    success: function(response) {

                        var dailyrate = Math.round((response.base_salary) / 22 * 100) /100;
                        var result = Math.round((dailyrate / 8)* 100) / 100;
                        var per_min = (result / 60);
                        var per_sec = (per_min / 60);

                        var allowance_per_cutoff = (response.employee_allowance) / 2;
                        // var result = (response.daily_rate) / 8;
                        // var per_min = (result / 60);
                        // var per_sec = (per_min / 60);
                        
                        $('#employee_name').val(response.employee_name);
                        $('#employee_number').val(response.employee_no);
                        $('#employee_dept').val(response.employee_department);
                        $('#employee_daily_rate').val(dailyrate);
                        // $('#start').val(response.sched_start);
                        // $('#end').val(response.sched_end);
                        $('#employee_per_hour_rate').val(result);
                        $('#employee_per_minute_rate').val(per_min);
                        $('#employee_per_seconds_rate').val(per_sec);
                        $('#schedule_shift').val(response.employee_shift);
                        $('#base_salary').val(response.base_salary);
                        $('#monthly_rate').val(response.monthly_rate);
                        $('#employee_allowances').val(allowance_per_cutoff);
                    }
                });
            }
            else{

                $('#employee_name').val('');
                $('#employee_number').val('');
                $('#employee_dept').val('');
                $('#employee_daily_rate').val('');
                $('#employee_per_hour_rate').val('');
                // $('#start').val('');
                // $('#end').val('');
                $('#employee_per_minute_rate').val('');
                $('#employee_per_seconds_rate').val('');
                $('#schedule_shift').val('');
                $('#base_salary').val('');
                $('#monthly_rate').val('');
                $('#employee_allowances').val('');
            }
        });

        $(document).on('click', '.modalpayslip', function (e) {
            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('view_payslip') }}',
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

    });
</script>

@endsection
@endsection

