@extends('layouts.main')
@section('main-content')
<div class="page-row row">
    <div class="col-xl-6">
        <h2 class="page-heading">Employees</h2>
    </div>
</div>

<div class="page-container row">
    <div class="col-xl-7">
        <div class="section-container">
            <div class="d-flex align-items-center">
                <i class='bx bx-group section-icon'></i>
                <h5 class="section-header">Employee Records</h5>
            </div>
            <div class="row table-row" id="show_all_employees">

            </div>
        </div>
    </div>



        <div class="col-xl-5 right-pane">
            @if(Auth::user()->hasRole(['HR','assistantHR']))
                <div class="card employee-card">
                    <div class="card-body">
                        <form action="{{ route('add_employees_user') }}" method="POST" id="add_user_employeee"  enctype="multipart/form-data">
                            @csrf

                            <div class="d-flex align-items-center">
                                <i class='bx bx-user-plus section-icon'></i>
                                <h5 class="section-header">Add Employee</h5>
                            </div>
                            <div class="form-row row first-row">
                                <div class="col-xl-12">
                                    <label for="txt-time">Name:</label>
                                    <input type="text" name="employee_name" class="form-control" >
                                    <span class="text-danger error-text employee_name_error"></span>
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="col-xl-12">
                                    <label for="txt-time">Email</label>
                                    <input type="text" name="email" class="form-control" >
                                    <span class="text-danger error-text email_error"></span>
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="col-xl-6">
                                    <label for="txt-time">Username</label>
                                    <input type="text" name="username" class="form-control" >
                                    <span class="text-danger error-text username_error"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label for="txt-time">Roles</label>
                                    <select name="role" id="role" class="form-select form-control">
                                        <option value="">Select a role</option>
                                        <option value="CEO">CEO / President</option>
                                        {{-- <option value="administrator">Administrator</option> --}}
                                        <option value="HR">HR</option>
                                        <option value="assistantHR">Assistant HR</option>
                                        <option value="accounting">Accounting</option>
                                        <option value="employee">Employee</option>
                                        <option value="attendance">Attendance</option>
                                        {{-- <option value="manager">Manager</option> --}}
                                        <option value="COO">Chief Operating Officer</option>
                                        <option value="VPO">Vice President for Operation</option>
                                        <option value="SVPT">Senior Vice President for Technology</option>
                                        <option value="teamleader">Team Leader</option>
                                    </select>
                                    <span class="text-danger error-text role_error"></span>
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="col-xl-6">
                                    <label for="txt-time">Password</label>
                                    <input type="password" name="password" class="form-control" >
                                    <span class="text-danger error-text password_error"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label for="txt-time">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" >
                                    <span class="text-danger error-text password_confirmation_error"></span>
                                </div>
                            </div>
                            <div class="form-row first-row">
                                <div class="d-flex">
                                    <span class="section-subheader d-flex align-items-center">
                                        <i class='bx bxs-info-circle'></i>
                                        Other Information
                                    </span>
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="col-xl-6">
                                    <label for="txt-time">Employee Picture</label>
                                    <input type="file" name="image" class="form-control" >
                                    <span class="text-danger error-text image_error"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label for="txt-time">Employee No:</label>
                                    <input type="text" name="employee_no" class="form-control" >
                                    <span class="text-danger error-text employee_no_error"></span>
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="col-xl-6">
                                    <label for="txt-time">Base Salary:</label>
                                    <input type="number" name="base_salary" min="0" class="form-control" >
                                    <span class="text-danger error-text base_salary_error"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label for="">Monthly Rate:</label>
                                    <select name="monthly_rate" class="form-select" id="txt-type">
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
                                    <input type="text" name="position" class="form-control" >
                                    <span class="text-danger error-text position_error"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label for="">Department:</label>
                                    <select name="employee_department" class="form-select" id="txt-type">
                                        <option value="">Select Department</option>
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
                                    <input type="date" name="date_hired" class="form-control">
                                    <span class="text-danger error-text date_hired_error"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label for="txt-time">Allowance:</label>
                                    <input type="number" step="any" name="employee_allowance" class="form-control">
                                    <span class="text-danger error-text employee_allowance_error"></span>
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="col-xl-6">
                                    <label for="txt-time">SSS Number:</label>
                                    <input type="text" name="sss" class="form-control">
                                    <span class="text-danger error-text sss_error"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label for="txt-time">Pagibig Number:</label>
                                    <input type="text" name="pagibig" class="form-control">
                                    <span class="text-danger error-text pagibig_error"></span>
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="col-xl-12">
                                    <label for="txt-time">Philhealth Number:</label>
                                    <input type="text" name="philhealth" class="form-control">
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
                                    <input type="time" name="sched_start" class="form-control">
                                    <span class="text-danger error-text sched_start_error"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label for="txt-time">End:</label>
                                    <input type="time" name="sched_end" class="form-control">
                                    <span class="text-danger error-text sched_end_error"></span>
                                </div>
                            </div>

                           

                            <div class="form-row row ">
                                <div class="col-xl-12">
                                    <label for="">Employee Shift:</label>
                                    <select name="employee_shift" class="form-select" id="txt-type">
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
                                        <input type="checkbox" name="work_days[]" value="Sunday"><span>S</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="work_days[]" value="Monday"><span>M</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="work_days[]" value="Tuesday"><span>T</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="work_days[]" value="Wednesday"><span>W</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="work_days[]" value="Thursday"><span>TH</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="work_days[]" value="Friday"><span>F</span>
                                    </label>
                                    <label>
                                        <input type="checkbox" name="work_days[]" value="Saturday"><span>S</span>
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
                                    <input type="time" name="breaktime_start" class="form-control">
                                    <span class="text-danger error-text breaktime_start_error"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label for="txt-time">End:</label>
                                    <input type="time" name="breaktime_end" class="form-control">
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
                                    <input type="date" name="employee_birthday" class="form-control">
                                    <span class="text-danger error-text employee_birthday_error"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label for="txt-time">Address:</label>
                                    <input type="text" name="employee_address" class="form-control">
                                    <span class="text-danger error-text employee_address_error"></span>
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="col-xl-12">
                                    <label for="txt-time">Contact Number:</label>
                                    <input type="tel" name="employee_contact_number" class="form-control"
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
                                    <input type="text" name="emergency_contact_name" class="form-control">
                                    <span class="text-danger error-text emergency_contact_name_error"></span>
                                </div>
                                <div class="col-xl-6">
                                    <label for="txt-time">Contact Number:</label>
                                    <input type="text" name="emergency_contact_number" class="form-control"
                                            pattern="[0-9]{11}"
                                            oninvalid="this.setCustomValidity('Make sure to follow the pattern EX: 099055*****')"
                                            oninput="this.setCustomValidity('')">
                                    <span class="text-danger error-text emergency_contact_number_error"></span>
                                </div>
                            </div>
                            <div class="form-row first-row">
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn-form d-flex align-items-center" id="btnSubmit">
                                        Add Employee
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @else
            <h4 class="text-center text-secondary my-5">Only HR or Assistant HR can add Employee.</h4>
        @endif
        </div>


</div>

{{-- MODAL FOR EDIT --}}
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

{{-- QR code modal --}}
<div class="modal fade" id="QrCode" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">

                <input type="hidden" name="emp_id" id="emid">

                <div class="modal-header border-0">
                    <i class='bx bx-qr section-icon'></i>
                    <h5 class="section-header">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="form-row row ">
                    <div class="qrr" id="qrs" >

                    </div>
                    <div class="col-xl-12">
                        <h5 class="" id="emp-namee"></h5>
                        <center><label for="txt-time">Employee Name</label></center>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@section('page-scripts')
<script>

    $(document).ready(function () {

      //CSRF TOKEN
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //TO FETCH ALL THE DATA IN THE TABLE
        fetchAllEmployees();
        function fetchAllEmployees(){
            $.ajax({
                url: '{{ route('get_employees') }}',
                method: 'GET',
                success: function(response) {
                $("#show_all_employees").html(response);
                $("#employee_table").DataTable({

                    "order": [[ 0, "desc" ]],
                });
                }
            });
        }

        //Reset the form when the employee click close modal all the value will reset
        $('#EditEmployee').on('hidden.bs.modal', function () {
            $('#EditEmployee form')[0].reset();
        });

        //Edit the Employee
        $(document).on('click','.editIcon', function(e) {

            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('edit_employee') }}',
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


        // THIS IS FOR ADDING THE EMPLOYEE TO THE DATABASE
        $('#add_user_employeee').on('submit',function (e) {

            e.preventDefault();
            $("#btnSubmit").text('Adding Employee . . .');
            $('#btnSubmit').attr("disabled", true);

            var form = this; //FORM
            $.ajax({
                url:$(form).attr('action'),
                method:$(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: "json",
                contentType:false,
                beforeSend: function(){
                    //Before Sending The Form
                    $(form).find('span.error-text').text('')
                },
                success: function(response) {
                    if(response.code == 0)
                    {
                        $('#btnSubmit').removeAttr("disabled"); // removing disabled button
                        //The Error Message Will Append
                        $.each(response.error, function(prefix, val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });
                        $('#btnSubmit').text('Add Employee');
                    }
                    else
                    {

                        $(form)[0].reset(); // TO REST FORM
                        $('#btnSubmit').removeAttr("disabled"); // removing disabled button
                        $('#btnSubmit').text('Add Employee');   //change the text to normal
                        fetchAllEmployees();    // TO RELOAD THE TABLE

                        // SWEETALERT
                        Swal.fire({
                            icon: 'success',
                            title: 'Added Successfully',
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
                        fetchAllEmployees();
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
                    //To Remove error message once the mocal close and open again
                    $('#close').on('click', function () {
                        $(frm).find('span.error-text').text('')
                    });
                }
            });
        });
        // end of update

        // delete employee ajax request
        $(document).on('click', '.deleteIcon', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            let csrf = '{{ csrf_token() }}';
            var reader = new FileReader();
            Swal.fire({
                title: 'Are you sure?',
                text: "All the records of this employee will be permanently deleted!",
                icon: 'warning',
                iconColor: 'rgb(188 61 79)',
                showCancelButton: true,
                confirmButtonColor: '#bc3d4f',
                confirmButtonText: 'Confirm delete!',
                confirmButtonColor: '#bc3d4f',
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('delete') }}',
                        method: 'delete',
                        data: {
                            id: id,
                            _token: csrf
                        },
                        success: function(response) {
                            console.log(response);
                            fetchAllEmployees();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted Successfully.',
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
                    });
                }
            })
        });

        $(document).on('click','.editQr', function(e) {

            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('edit_qr') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },

                success: function(response) {

                    $("#emid").val(response.id);
                    var avatar = response.qr;
                    if(avatar != null)
                    {
                        $("#qrs").html( `<center><img  src="http://chart.apis.google.com/chart?cht=qr&chs=500x500&chl=${response.qr}" width="100%" height="auto" ></center>`);
                        $("#emp-namee").html( `<center><h5>${response.employee_name}</h5></center>`);
                        $("#ww").html( `<center><h5>${response.employee_name}</h5></center>`);
                    }
                }
            });
        });
    });
</script>
@endsection
@endsection
