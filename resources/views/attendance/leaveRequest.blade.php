@extends('layouts.main')

@section('main-content')
    
<div class="page-row row">
    <div class="col-xl-6">
        <h2 class="page-heading">Request Leave</h2>
    </div>
    <div class="col-xl-6">
        <ul class="nav nav-tabs d-flex justify-content-end" id="" role="tablist">
            <li class="nav-item">
                <a class="tab active d-flex align-items-center" id="form-ot" data-bs-toggle="tab" href="#form-OT"
                    role="tab" aria-controls="tracker" aria-selected="false">
                    <i class='bx bx-time-five'></i>
                    Leave Request Form
                </a>
            </li>

            
            <li class="nav-item">
                <a class="tab d-flex align-items-center ms-2" id="all-ot" data-bs-toggle="tab" href="#ot"
                    role="tab" aria-controls="all" aria-selected="false">
                    <i class='bx bx-list-ul'></i>
                    All Leave Request
                </a>
            </li>
            
            <!-- <li class="nav-item">
                <a class="tab d-flex align-items-center ms-2" id="leave-tab" data-bs-toggle="tab" href="#leave"
                    role="tab" aria-controls="leave" aria-selected="false">
                    <i class='bx bx-calendar-x'></i>
                    On Leave
                </a>
            </li> -->
        </ul>
    </div>
</div>
<div class="page-container row">
    <div class="tab-content" id="myTabContent" style="margin-top: 30px;">
        <div class="tab-pane fade show active" id="form-OT" role="tabpanel" aria-labelledby="form-ot">
            <div class="row">
                <div class="col-xl-8">
                    <div class="section-container">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-book section-icon'></i>
                            <h5 class="section-header">All Leave Request</h5>
                        </div>
                        <div class="row table-row" id="all_leaveReq">
                            
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card employee-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class='bx bx-file section-icon'></i>
                                <h5 class="section-header">Request Form</h5>
                            </div>
                            <form action="{{route('add_leave')}}" method="POST" id="add_leave"> 
                                @csrf
                                <!-- <div class="row form-row">
                                    <div class="col-xl-6">
                                        <label for="">Days:</label>
                                        <input type="text" maxlength="3" onkeypress="return event.charCode >= 8 && event.charCode <= 57" name="leave_day" class="form-control" id="">
                                        <span class="text-danger error-text leave_day_error"></span>
                                    </div>
                                </div> -->
                                <div class="row form-row">
                                    <div class="col-xl-6">
                                        <label for="">Start Date:</label>
                                        <input type="date" name="start_date" min=<?php echo date("Y-m-d") ?> class="form-control" id="">
                                        <span class="text-danger error-text start_date_error"></span>
                                    </div>
                                    <div class="col-xl-6">
                                        <label for="">End Date:</label>
                                        <input type="date" name="end_date" min=<?php echo date("Y-m-d") ?> class="form-control" id="">
                                        <span class="text-danger error-text end_date_error"></span>
                                    </div>
                                </div>
                                <div class="row form-row">
                                    <div class="col-xl-6">
                                        <label for="">Leave Type:</label>
                                        <select name="leave_type" class="form-select" id="txt-type">
                                            <option value="">Select Type</option>
                                            <option value="Vacation Leave">Vacation Leave</option>
                                            <option value="Sick Leave">Sick Leave</option>
                                            <option value="Paternity Leave">Paternity Leave</option>
                                            <option value="Maternity Leave">Maternity Leave</option>
                                            <option value="Emergency Leave">Emergency Leave</option>
                                        </select>
                                        <span class="text-danger error-text leave_type_error"></span>
                                    </div>
                                    <div class="col-xl-6">
                                        <label for="">Department:</label>
                                        <select name="department" class="form-select" id="txt-type">
                                            <option value="{{Auth::user()->department}}">{{Auth::user()->department}}</option>    
                                        <!-- <option value="">Select Department</option>
                                            <option value="Administration">Administration</option>
                                            <option value="App Intake">App Intake</option>
                                            <option value="Audit">Audit</option>
                                            <option value="Verification">Verification</option> -->
                                            <!-- <option value="Orenda">Orenda</option> -->
                                            <!-- <option value="Returns">Returns</option>
                                            <option value="IT">IT</option>
                                            <option value="Project Management">Project Management</option>
                                            <option value="Provider Relation">Provider Relation</option>
                                            <option value="Provider Enrollment">Provider Enrollment</option> -->
                                        </select>
                                        <span class="text-danger error-text department_error"></span>
                                    </div>
                                </div>
                                <div class="row form-row">
                                    <div class="col-xl-12">
                                        <label for="">Reason:</label>
                                        <textarea name="reason" class="form-control" id="exampleFormControlTextarea1" rows="8"></textarea>
                                        <span class="text-danger error-text reason_error"></span>

                                    </div>
                                </div>
                                <div class="form-row first-row">
                                    <div class="d-flex">
                                        <span class="section-subheader d-flex align-items-center">
                                            <i class='bx bxs-user-detail'></i>
                                            Contact while on leave
                                        </span>
                                    </div>
                                </div>
                                <div class="row form-row">
                                    <div class="col-xl-12">
                                        <label for="">Address:</label>
                                        <input type="text" name="address" class="form-control" id="">
                                        <span class="text-danger error-text address_error"></span>
                                    </div>
                                </div>
                                <div class="row form-row">
                                    <div class="col-xl-12">
                                        <label for="">Contact Number:</label> 
                                        <input type="tel" pattern="[0-9]{11}"  min="11" max="11" name="contact" class="form-control" 
                                               oninvalid="this.setCustomValidity('Make sure to follow the pattern EX: 099055*****')"
                                               oninput="this.setCustomValidity('')">

                                        <span class="text-danger error-text contact_error"></span>
                                    </div>
                                </div>
                                <div class="row form-row">
                                    <div class="col-xl-6">
                                        <label for="">Person 1:</label>
                                        <input type="text"  name="person1" class="form-control" id="">
                                    </div>
                                    <div class="col-xl-6">
                                        <label for="">Person 2:</label>
                                        <input type="text"  name="person2" class="form-control" id="">
                                    </div>
                                </div>
                                <div class="form-row first-row">
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn-form d-flex align-items-center" id="btnSubmitLeave">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show" id="ot" role="tabpanel" aria-labelledby="all-ot">
            <div class="row">

                @if(Auth::user()->hasRole(['teamleader','administrator','HR','assistantHR','COO','VPO','SVPT']))
                <div class="col-xl-12">
                    <div class="section-container">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-book section-icon'></i>
                            <h5 class="section-header">All Leave Request</h5>
                        </div>
                        <div class="row-table-row" id="all_leaveReqEmp">
                        </div>
                    </div>
                </div> 
                @else
                     <h4 class="text-center text-secondary my-5">This Page can only be accessed by the SVPT, COO, Team Leader, and Human Resources.</h4>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL FOR Approve TL --}}
<div class="modal fade" id="TL_approve" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="TLleaveLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_leave_TL') }}" method="POST" id="submit_leave_TL"  enctype="multipart/form-data">
                    @csrf 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">TL Approval</h5>
                    </div>
                    <input type="hidden" name="id_tl" id="id_tl">
                        <h5 class="text-center mt-4">Do you really want to accept this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnTL_Approval">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}

{{-- MODAL FOR Decline TL --}}
<div class="modal fade" id="TL_decline" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="TLdeclineLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_decline_TL') }}" method="POST" id="submit_decline_TL"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">TL Approval</h5>
                    </div>
                    <input type="hidden" name="id_decline_tl" id="id_decline_tl">
                        <h5 class="text-center mt-4">Do you really want to decline this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnTL_Decline">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}

{{-- MODAL FOR Approve HR --}}
<div class="modal fade" id="HR_approve" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="HRleaveLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_leave_HR') }}" method="POST" id="submit_leave_HR"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">HR Approval</h5>
                    </div>
                    <input type="hidden" name="id_hr" id="id_hr">
                        <h5 class="text-center mt-4">Do you really want to accept this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnHR_Approval">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}

{{-- MODAL FOR Decline HR --}}
<div class="modal fade" id="HR_decline" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="HRdeclineLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_decline_HR') }}" method="POST" id="submit_decline_HR"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">HR Approval</h5>
                    </div>
                    <input type="hidden" name="id_decline_hr" id="id_decline_hr">
                        <h5 class="text-center mt-4">Do you really want to decline this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnHR_Decline">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}

{{-- MODAL FOR Approve SVP --}}
<div class="modal fade" id="SVP_approve" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="SVPleaveLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_leave_SVP') }}" method="POST" id="submit_leave_SVP"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">SVP Approval</h5>
                    </div>
                    <input type="hidden" name="id_svp" id="id_svp">
                        <h5 class="text-center mt-4">Do you really want to accept this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnSVP_Approval">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}

{{-- MODAL FOR Decline SVP --}}
<div class="modal fade" id="SVP_decline" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="SVPdeclineLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_decline_SVP') }}" method="POST" id="submit_decline_SVP"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">SVP Approval</h5>
                    </div>
                    <input type="hidden" name="id_decline_svp" id="id_decline_svp">
                        <h5 class="text-center mt-4">Do you really want to decline this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnSVP_Decline">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}

{{-- MODAL FOR Approve VPO --}}
<div class="modal fade" id="VPO_approve" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="VPOleaveLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_leave_VPO') }}" method="POST" id="submit_leave_VPO"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">VPO Approval</h5>
                    </div>
                    <input type="hidden" name="id_vpo" id="id_vpo">
                        <h5 class="text-center mt-4">Do you really want to accept this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnVPO_Approval">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}

{{-- MODAL FOR Decline VPO --}}
<div class="modal fade" id="VPO_decline" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="VPOdeclineLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_decline_VPO') }}" method="POST" id="submit_decline_VPO"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">VPO Approval</h5>
                    </div>
                    <input type="hidden" name="id_decline_vpo" id="id_decline_vpo">
                        <h5 class="text-center mt-4">Do you really want to decline this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnVPO_Decline">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}

{{-- MODAL FOR Approve COO --}}
<div class="modal fade" id="COO_approve" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="COOleaveLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_leave_COO') }}" method="POST" id="submit_leave_COO"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">COO Approval</h5>
                    </div>
                    <input type="hidden" name="id_coo" id="id_coo">
                        <h5 class="text-center mt-4">Do you really want to accept this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnCOO_Approval">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}

{{-- MODAL FOR Decline COO --}}
<div class="modal fade" id="COO_decline" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="COOdeclineLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_decline_COO') }}" method="POST" id="submit_decline_COO"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">COO Approval</h5>
                    </div>
                    <input type="hidden" name="id_decline_coo" id="id_decline_coo">
                        <h5 class="text-center mt-4">Do you really want to decline this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnCOO_Decline">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}


@section('page-scripts')
<script>
    $(document).ready(function () {

        //CSRF TOKEN
        $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
        });

        // All Leave Requests 
        all_leaveRequest();
            function all_leaveRequest(){
                $.ajax({
                    url: '{{ route('all_leave_request') }}',
                    method: 'GET',
                    success: function(response) {
                        $("#all_leaveReq").html(response);
                        $("#all_leave").DataTable({
                            "order": [[ 0, "desc" ]]
                        }); 
                    }
                });
            }
        all_leaveReqEmp();
            function all_leaveReqEmp(){
                $.ajax({
                    url: '{{ route('all_leave_emp') }}',
                    method: 'GET',
                    success: function(response) {
                        $("#all_leaveReqEmp").html(response);
                        $("#all_leave_Emp").DataTable({
                            "order": [[ 0, "desc" ]],
                        }); 
                    }
                });
            }


        $('#add_leave').on('submit',function (e) {
            e.preventDefault();

            $("#btnSubmitLeave").text('Submitting . . .');
            $('#btnSubmitLeave').attr("disabled", true);
            
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
                
                success: function (response) {
                    
                    if(response.code == 0){
                        $('#btnSubmitLeave').removeAttr("disabled"); 
                        //The Error Message Will Append
                        $.each(response.error, function(prefix, val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });
                        $('#btnSubmitLeave').text('Submit');
                    }
                    else
                    {
                        $(form)[0].reset(); 
                        $('#btnSubmitLeave').removeAttr("disabled");
                        $('#btnSubmitLeave').text('Submit');
                        all_leaveRequest();
                        all_leaveReqEmp();

                        // SWEETALERT
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Success',
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

        // TL Approve Request
            $(document).on('click', '.tl_approve', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('id');
                $.ajax({
                    url: '{{ route('approve_leave_TL') }}', 
                    method: 'get',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {

                        $("#id_tl").val(response.id);
                    }
                });
            });
            //submit the request leave TL
            $('#submit_leave_TL').on('submit',function (e) {

                e.preventDefault();
                $("#btnTL_Approval").text('Submitting . . . ');
                $('#btnTL_Approval').attr("disabled", true);

                var form = this; 

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

                    success: function (response) {
                        
                        $(form)[0].reset(); // TO REST FORM
                        $('#btnTL_Approval').removeAttr("disabled");
                        $('#btnTL_Approval').text('Submit Request');

                        all_leaveRequest();
                        all_leaveReqEmp(); // reload table 
                        
                        $("#TL_approve").modal('hide'); //hide the modal
                            
                        // SWEETALERT
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Approve',
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
            });
        // .End TL Approve

        // TL Decline Request
            $(document).on('click', '.tl_decline', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('id');
                $.ajax({
                    url: '{{ route('decline_leave_TL') }}', 
                    method: 'get',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {

                        $("#id_decline_tl").val(response.id);
                    }
                });
            });
            //submit the request decline HR
            $('#submit_decline_TL').on('submit',function (e) {

                e.preventDefault();
                $("#btnTL_Decline").text('Submitting . . . ');
                $('#btnTL_Decline').attr("disabled", true);

                var form = this; 

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

                    success: function (response) {
                        
                        $(form)[0].reset(); // TO REST FORM
                        $('#btnTL_Decline').removeAttr("disabled");
                        $('#btnTL_Decline').text('Submit Request');

                        all_leaveRequest();
                        all_leaveReqEmp(); // reload table 
                        
                        $("#TL_decline").modal('hide'); //hide the modal
                            
                        // SWEETALERT
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Approve',
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
            });
        // .End TL Decline


        // HR Approve Request
            $(document).on('click', '.hr_approve', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('id');
                $.ajax({
                    url: '{{ route('approve_leave_HR') }}', 
                    method: 'get',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {

                        $("#id_hr").val(response.id);
                    }
                });
            });
            //submit the request leave HR
            $('#submit_leave_HR').on('submit',function (e) {

                e.preventDefault();
                $("#btnHR_Approval").text('Submitting . . . ');
                $('#btnHR_Approval').attr("disabled", true);

                var form = this; 

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

                    success: function (response) {
                        
                        $(form)[0].reset(); // TO REST FORM
                        $('#btnHR_Approval').removeAttr("disabled");
                        $('#btnHR_Approval').text('Submit Request');

                        all_leaveRequest();
                        all_leaveReqEmp(); // reload table 
                        
                        $("#HR_approve").modal('hide'); //hide the modal
                            
                        // SWEETALERT
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Approve',
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
            });
        // .End HR Approve

        // HR Decline Request
            $(document).on('click', '.hr_decline', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('id');
                $.ajax({
                    url: '{{ route('decline_leave_HR') }}', 
                    method: 'get',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {

                        $("#id_decline_hr").val(response.id);
                    }
                });
            });
            //submit the request decline HR
            $('#submit_decline_HR').on('submit',function (e) {

                e.preventDefault();
                $("#btnHR_Decline").text('Submitting . . . ');
                $('#btnHR_Decline').attr("disabled", true);

                var form = this; 

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

                    success: function (response) {
                        
                        $(form)[0].reset(); // TO REST FORM
                        $('#btnHR_Decline').removeAttr("disabled");
                        $('#btnHR_Decline').text('Submit Request');

                        all_leaveRequest();
                        all_leaveReqEmp(); // reload table 
                        
                        $("#HR_decline").modal('hide'); //hide the modal
                            
                        // SWEETALERT
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Approve',
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
            });
        // .End HR Decline


        // SVP Approve Request
            $(document).on('click', '.svp_approve', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('id');
                $.ajax({
                    url: '{{ route('approve_leave_SVP') }}', 
                    method: 'get',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {

                        $("#id_svp").val(response.id);
                    }
                });
            });
            //submit the request leave SVP
            $('#submit_leave_SVP').on('submit',function (e) {

                e.preventDefault();
                $("#btnSVP_Approval").text('Submitting . . . ');
                $('#btnSVP_Approval').attr("disabled", true);

                var form = this; 

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

                    success: function (response) {
                        
                        $(form)[0].reset(); // TO REST FORM
                        $('#btnSVP_Approval').removeAttr("disabled");
                        $('#btnSVP_Approval').text('Submit Request');

                        all_leaveRequest();
                        all_leaveReqEmp(); // reload table 
                        
                        $("#SVP_approve").modal('hide'); //hide the modal
                            
                        // SWEETALERT
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Approve',
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
            });
        // .End SVP Approve

        // SVP Decline Request
            $(document).on('click', '.svp_decline', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('id');
                $.ajax({
                    url: '{{ route('decline_leave_SVP') }}', 
                    method: 'get',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {

                        $("#id_decline_svp").val(response.id);
                    }
                });
            });
            //submit the request decline HR
            $('#submit_decline_SVP').on('submit',function (e) {

                e.preventDefault();
                $("#btnSVP_Decline").text('Submitting . . . ');
                $('#btnSVP_Decline').attr("disabled", true);

                var form = this; 

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

                    success: function (response) {
                        
                        $(form)[0].reset(); // TO REST FORM
                        $('#btnSVP_Decline').removeAttr("disabled");
                        $('#btnSVP_Decline').text('Submit Request');

                        all_leaveRequest();
                        all_leaveReqEmp(); // reload table 
                        
                        $("#SVP_decline").modal('hide'); //hide the modal
                            
                        // SWEETALERT
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Approve',
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
            });
        // .End SVP Decline


        // VPO Approve Request
            $(document).on('click', '.vpo_approve', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('id');
                $.ajax({
                    url: '{{ route('approve_leave_VPO') }}', 
                    method: 'get',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {

                        $("#id_vpo").val(response.id);
                    }
                });
            });
            //submit the request leave SVP
            $('#submit_leave_VPO').on('submit',function (e) {

                e.preventDefault();
                $("#btnVPO_Approval").text('Submitting . . . ');
                $('#btnVPO_Approval').attr("disabled", true);

                var form = this; 

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

                    success: function (response) {
                        
                        $(form)[0].reset(); // TO REST FORM
                        $('#btnVPO_Approval').removeAttr("disabled");
                        $('#btnVPO_Approval').text('Submit Request');

                        all_leaveRequest();
                        all_leaveReqEmp(); // reload table 
                        
                        $("#VPO_approve").modal('hide'); //hide the modal
                            
                        // SWEETALERT
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Approve',
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
            });
        // .End VPO Approve

        // VPO Decline Request
            $(document).on('click', '.vpo_decline', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('id');
                $.ajax({
                    url: '{{ route('decline_leave_VPO') }}', 
                    method: 'get',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {

                        $("#id_decline_vpo").val(response.id);
                    }
                });
            });
            //submit the request decline HR
            $('#submit_decline_VPO').on('submit',function (e) {

                e.preventDefault();
                $("#btnVPO_Decline").text('Submitting . . . ');
                $('#btnVPO_Decline').attr("disabled", true);

                var form = this; 

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

                    success: function (response) {
                        
                        $(form)[0].reset(); // TO REST FORM
                        $('#btnVPO_Decline').removeAttr("disabled");
                        $('#btnVPO_Decline').text('Submit Request');

                        all_leaveRequest();
                        all_leaveReqEmp(); // reload table 
                        
                        $("#VPO_decline").modal('hide'); //hide the modal
                            
                        // SWEETALERT
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Approve',
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
            });
        // .End VPO Decline

        // VPO Approve Request
            $(document).on('click', '.coo_approve', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('id');
                $.ajax({
                    url: '{{ route('approve_leave_COO') }}', 
                    method: 'get',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {

                        $("#id_coo").val(response.id);
                    }
                });
            });
            //submit the request leave SVP
            $('#submit_leave_COO').on('submit',function (e) {

                e.preventDefault();
                $("#btnCOO_Approval").text('Submitting . . . ');
                $('#btnCOO_Approval').attr("disabled", true);

                var form = this; 

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

                    success: function (response) {
                        
                        $(form)[0].reset(); // TO REST FORM
                        $('#btnCOO_Approval').removeAttr("disabled");
                        $('#btnCOO_Approval').text('Submit Request');

                        all_leaveRequest();
                        all_leaveReqEmp(); // reload table 
                        
                        $("#COO_approve").modal('hide'); //hide the modal
                            
                        // SWEETALERT
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Approve',
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
            });
        // .End VPO Approve

        // VPO Decline Request
            $(document).on('click', '.coo_decline', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('id');
                $.ajax({
                    url: '{{ route('decline_leave_COO') }}', 
                    method: 'get',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {

                        $("#id_decline_coo").val(response.id);
                    }
                });
            });
            //submit the request decline HR
            $('#submit_decline_COO').on('submit',function (e) {

                e.preventDefault();
                $("#btnCOO_Decline").text('Submitting . . . ');
                $('#btnCOO_Decline').attr("disabled", true);

                var form = this; 

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

                    success: function (response) {
                        
                        $(form)[0].reset(); // TO REST FORM
                        $('#btnCOO_Decline').removeAttr("disabled");
                        $('#btnCOO_Decline').text('Submit Request');

                        all_leaveRequest();
                        all_leaveReqEmp(); // reload table 
                        
                        $("#COO_decline").modal('hide'); //hide the modal
                            
                        // SWEETALERT
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Approve',
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
            });
        // .End VPO Decline

    });
</script>
@endsection
@endsection