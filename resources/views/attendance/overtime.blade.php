@extends('layouts.main')
@section('main-content')

<div class="page-row row">
    <div class="col-xl-6">
        <h2 class="page-heading">Request Overtime</h2>
    </div>
    <div class="col-xl-6">
        <ul class="nav nav-tabs d-flex justify-content-end" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="tab active d-flex align-items-center" id="all_attendance_record-tab" data-bs-toggle="tab" href="#all_attendance_record"
                    role="tab" aria-controls="" aria-selected="false">
                    <i class='bx bx-time-five'></i>
                    All Attendance Record
                </a>
            </li>
            <li class="nav-item">
                <a class="tab d-flex align-items-center ms-2" id="all_overtime_request-tab" data-bs-toggle="tab" href="#all_overtime_request"
                    role="tab" aria-controls="overtime" aria-selected="false">
                    <i class='bx bx-list-ul'></i>
                   All Overtime Request
                </a>
            </li>
          
        </ul>
    </div>
</div>

<div class="page-container row">
    <div class="tab-content" id="myTabContent" style="margin-top: 30px;">

        {{-- All User Attendance--}}
        
        <div class="tab-pane fade show active" id="all_attendance_record" role="tabpanel" aria-labelledby="all_attendance_record-tab">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-container">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class='bx bx-book section-icon'></i>
                                <h5 class="section-header">Attendance Record</h5>
                            </div>
                       
                        </div>
                        <div class="row table-row" id="show_all_request">
              
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        {{-- All Overtime Request--}}

        <div class="tab-pane fade show" id="all_overtime_request" role="tabpanel" aria-labelledby="all_overtime_request-tab">
            <div class="row">
                <div class="col-xl-12">
                    @if(Auth::user()->hasRole(['HR','administrator','teamleader','COO','VPO','assistantHR','SVPT']))
                    <div class="section-container">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-book section-icon'></i>
                            <h5 class="section-header">All Overtime Request</h5>
                        </div>
                        <div class="row table-row" id="all_request">
                             <!-- All Overtime Request here -->
                        </div>
                    </div>
                    @else
                         <h4 class="text-center text-secondary my-5">This Page can only be accessed by the Senior Vice President for Technology, Team Leader, Chief Operating Officer, Vice President For Operation and Human Resources.</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{--------------------------------------------------------REQUESTING MODAL ------------------------------------------------------------------}}

{{-- MODAL FOR REQUEST OVERTIME --}}

    <div class="modal fade modal-dialog-scrollable" id="request_overtime" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; padding: 10px;">
                <div class="modal-body">
                    <form action="{{ route('submit_overtime_request') }}" method="POST" id="submit_overtime_request"  enctype="multipart/form-data">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-edit section-icon'></i>
                            <h5 class="section-header">Request Overtime</h5>
                        </div>
                        <input type="hidden" name="atten_id" id="atten_id">
                        
                        <div class="form-row row">
                            <div class="col-xl-12">
                                <label for="txt">Employeee Name:</label>
                                <input type="text" name="emp_name" class="form-control" id="emp_name" readonly>
                                <span class="text-danger error-text emp_no_error"></span>
                            </div>
                        </div>
                    
                        <div class="form-row row">
                            <div class="col-xl-12">
                                <label for="txt">Employeee Number:</label>
                                <input type="text" name="emp_no" class="form-control" id="emp_no" readonly>
                                <span class="text-danger error-text emp_no_error"></span>
                            </div>
                        </div>
                        
                    
                        <div class="form-row row">
                            <div class="col-xl-12">
                                <label for="">Date:</label>
                                <input type="date" name="date" class="form-control" id="date" readonly>
                            </div>
                        </div>
            
                        <div class="row form-row">
                            <div class="col-xl-12">
                                <label for="">Reason:</label>
                                <textarea name="reason" class="form-control reason" id="exampleFormControlTextarea1" rows="8"></textarea>
                                <span class="text-danger error-text reason_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer mt-5 gap-2">
                            <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                            <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnSubmit">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

{{-- END OF MODAL --}}



{{--------------------------------------------------------APPROVAL MODAL ------------------------------------------------------------------}}

{{-- MODAL FOR Approve HR --}}
<div class="modal fade" id="approve_ot_HR" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ApproveHRLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_approve_HR') }}" method="POST" id="submit_approve_HR"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">HR Approval</h5>
                    </div>
                    <input type="text" name="hr_id" id="hr_id">
           

                    <h5 class="text-center mt-4">Do you really want to accept this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnApprove">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}

{{-- MODAL FOR Approve SVP --}}
<div class="modal fade" id="approve_ot_SVP" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ApproveSVPLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_approve_SVP') }}" method="POST" id="submit_approve_SVP"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">SVP Approval</h5>
                    </div>
                    <input type="hidden" name="svp_id" id="svp_id">
                    {{-- <input  name="emp_svp" id="emp_svp" hidden> --}}

                    <h5 class="text-center mt-4">Do you really want to accept this request?</h5>

                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnApproveSVP">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}

{{-- MODAL FOR Approve TL --}}
<div class="modal fade" id="approve_ot_TL" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ApproveTLLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_approve_TL') }}" method="POST" id="submit_approve_TL"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">Team Leader Approval</h5>
                    </div>
                    <input type="hidden" name="tl_id" id="tl_id">
           

                    <h5 class="text-center mt-4">Do you really want to accept this request?</h5>

                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnApproveTL">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}


{{-- MODAL FOR Approve VPO --}}
<div class="modal fade" id="approve_ot_VPO" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ApproveVPOLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_approve_VPO') }}" method="POST" id="submit_approve_VPO"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">VPO Approval</h5>
                    </div>
                    <input type="" name="VPO_id" id="VPO_id" hidden>
                
                    <h5 class="text-center mt-4">Do you really want to accept this request?</h5>

                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnApproveVPO">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}



{{-- MODAL FOR Approve COO --}}
<div class="modal fade" id="approve_ot_COO" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ApproveCOOLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('submit_approve_COO') }}" method="POST" id="submit_approve_COO"  enctype="multipart/form-data"> 
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">COO Approval</h5>
                    </div>
                    <input type="" name="COO_id" id="COO_id" hidden>
                
                    <h5 class="text-center mt-4">Do you really want to accept this request?</h5>

                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="btnApproveCOO">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END OF MODAL --}}
















{{--------------------------------------------------------DECLINE MODAL ------------------------------------------------------------------}}

{{-- HR decline request --}}
<div class="modal fade" id="decline_ot_HR" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ApproveSVPLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('HR_submit_decline') }}" method="POST" id="HR_submit_decline_request"  enctype="multipart/form-data"> 
                    @csrf
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">Decline</h5>
                    </div>

                    <input type="hidden" name="hr_decline_id" id="hr_decline_id">
            
                    <h5 class="text-center mt-4">Do you really want to reject this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="HR_confirm_btn">Confirm </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End of HR decline --}}


{{-- TL decline request --}}
<div class="modal fade" id="decline_ot_TL" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ApproveSVPLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('TL_submit_decline') }}" method="POST" id="TL_submit_decline_request"  enctype="multipart/form-data"> 
                    @csrf
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">Decline</h5>
                    </div>

                    <input type="hidden" name="tl_decline_id" id="tl_decline_id">
            
                    <h5 class="text-center mt-4">Do you really want to reject this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="TL_confirm_btn">Confirm </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End of TL decline --}}

{{-- SVP decline request --}}
<div class="modal fade" id="decline_ot_SVP" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ApproveSVPLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('SVP_submit_decline') }}" method="POST" id="SVP_submit_decline_request"  enctype="multipart/form-data"> 
                    @csrf
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">Decline</h5>
                    </div>

                    <input type="hidden" name="svp_decline_id" id="svp_decline_id">
            
                    <h5 class="text-center mt-4">Do you really want to reject this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="SVP_confirm_btn">Confirm </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End of SVP decline --}}

{{-- VPO decline request --}}
<div class="modal fade" id="decline_ot_VPO" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ApproveVPOLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('VPO_submit_decline') }}" method="POST" id="VPO_submit_decline_request"  enctype="multipart/form-data"> 
                    @csrf
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">Decline</h5>
                    </div>

                    <input type="" name="VPO_decline_id" id="VPO_decline_id" hidden>
            
                    <h5 class="text-center mt-4">Do you really want to reject this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="VPO_confirm_btn">Confirm </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End of VPO decline --}}

{{-- COO decline request --}}
<div class="modal fade" id="decline_ot_COO" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ApproveCOOLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('COO_submit_decline') }}" method="POST" id="COO_submit_decline_request"  enctype="multipart/form-data"> 
                    @csrf
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">Decline</h5>
                    </div>

                    <input type="" name="COO_decline_id" id="COO_decline_id" hidden>
            
                    <h5 class="text-center mt-4">Do you really want to reject this request?</h5>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="COO_confirm_btn">Confirm </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End of COO decline --}}










@section('page-scripts')
<script>
    $(document).ready(function () {

        //CSRF TOKEN
        $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
        });

       

        //use to reload table 
        get_all_employee_attendance();

        //Get  employee atttendance in database
        function get_all_employee_attendance(){
            $.ajax({
                url: '{{ route('employee_all_attendance') }}', 
                method: 'GET',
                success: function(response) {
                $("#show_all_request").html(response);
                $("#attendance_table").DataTable({

                    "order": [[ 0, "desc" ]]
                });
                }
            });
        }  

         // All Attendance
         all_overtime();
            function all_overtime(){
                $.ajax({
                    url: '{{ route('all_overtime_request') }}',
                    method: 'GET',
                    success: function(response) {
                        $("#all_request").html(response);
                        $("#overtime_table").DataTable({
                            "order": [[ 0, "desc" ]]
                        }); 
                    }
                });
            }

       
       
       
        //get the value of clicked modal 
        $(document).on('click', '.request_ot', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('overtime_request') }}', 
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#atten_id").val(response.id);
                    $("#emp_name").val(response.employee.employee_name);
                    $("#emp_no").val(response.emp_no);
                    $("#date").val(response.date);
                    // $("#out").val(response.time_out);
                }
            });
        });


        //submit the request overtime
        $('#submit_overtime_request').on('submit',function (e) {

            e.preventDefault();
            $("#btnSubmit").text('Submitting . . . ');
            $('#btnSubmit').attr("disabled", true);

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
                        $('#btnSubmit').removeAttr("disabled");
                        //The Error Message Will Append
                        $.each(response.error, function(prefix, val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });
                        $('#btnSubmit').text('Submit Request');
                    }
                    else
                    {
                        $(form)[0].reset(); // TO REST FORM
                        $('#btnSubmit').removeAttr("disabled");
                        $('#btnSubmit').text('Submit Request');
                        
                        get_all_employee_attendance(); // reload table 
                        all_overtime();
                        $("#request_overtime").modal('hide'); //hide the modal
                            
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
        

        // HR Approve Request
        $(document).on('click', '.approve_hr', function(e) 
        {
                e.preventDefault();
                let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('approve_HR') }}', 
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#hr_id").val(response.id);
                    $("#emp_hr").val(response.emp_no);
                    $("#date_hr").val(response.date);
                    // $("#out").val(response.time_out);
                }
            });
        });
        //submit the request overtime HR
        $('#submit_approve_HR').on('submit',function (e) {

            e.preventDefault();
            $("#btnApprove").text('Submitting . . . ');
            $('#btnApprove').attr("disabled", true);

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
                        $('#btnApprove').removeAttr("disabled");
                        $('#btnApprove').text('Submit Request');

                        all_overtime();
                        get_all_employee_attendance(); // reload table 
                       
                        $("#approve_ot_HR").modal('hide'); //hide the modal
                            
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

        // SVP Approve Request
        $(document).on('click', '.approve_svp', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('approve_SVP') }}', 
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#svp_id").val(response.id);
                    $("#emp_svp").val(response.emp_no);
                    $("#date_svp").val(response.date);
                    // $("#out").val(response.time_out);
                }
            });
        });

        //submit the request overtime SVP
        $('#submit_approve_SVP').on('submit',function (e) {

            e.preventDefault();
            $("#btnApproveSVP").text('Submitting . . . ');
            $('#btnApproveSVP').attr("disabled", true);

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
                        $('#btnApproveSVP').removeAttr("disabled");
                        $('#btnApproveSVP').text('Submit Request');

                        all_overtime();
                        get_all_employee_attendance(); // reload table 
                        
                        $("#approve_ot_SVP").modal('hide'); //hide the modal
                            
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

         // TL Approve Request
        $(document).on('click', '.approve_tl', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('approve_TL') }}', 
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#tl_id").val(response.id);
                    $("#emp_tl").val(response.emp_no);
                    $("#date_tl").val(response.date);
                    // $("#out").val(response.time_out);
                }
            });
        });
        // submit the request overtime TL
        $('#submit_approve_TL').on('submit',function (e) {

            e.preventDefault();
            $("#btnApproveTL").text('Submitting . . . ');
            $('#btnApproveTL').attr("disabled", true);

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
                        $('#btnApproveTL').removeAttr("disabled");
                        $('#btnApproveTL').text('Submit Request');
                         all_overtime();
                        get_all_employee_attendance(); // reload table 
                        $("#approve_ot_TL").modal('hide'); //hide the modal
                            
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

        // VPO Approve Request
        $(document).on('click', '.approve_vpo', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('approve_VPO') }}', 
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#VPO_id").val(response.id);
                }
            });
        });

          // submit the request overtime TL
        $('#submit_approve_VPO').on('submit',function (e) {

            e.preventDefault();
            $("#btnApproveVPO").text('Submitting . . . ');
            $('#btnApproveVPO').attr("disabled", true);

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
                        $('#btnApproveVPO').removeAttr("disabled");
                        $('#btnApproveVPO').text('Submit Request');
                        all_overtime();
                        get_all_employee_attendance(); // reload table 
                        $("#approve_ot_VPO").modal('hide'); //hide the modal
                            
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

         // COO VIEW ID NUMBER
        $(document).on('click', '.approve_coo', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('approve_COO') }}', 
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#COO_id").val(response.id);
                }
            });
        });

        //Approve COO
        $('#submit_approve_COO').on('submit',function (e) {

            e.preventDefault();
            $("#btnApproveCOO").text('Submitting . . . ');
            $('#btnApproveCOO').attr("disabled", true);

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
                        $('#btnApproveCOO').removeAttr("disabled");
                        $('#btnApproveCOO').text('Submit Request');
                        all_overtime();
                        get_all_employee_attendance(); // reload table 
                        $("#approve_ot_COO").modal('hide'); //hide the modal
                            
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








        // FOR DECLINE OF REQUEST

        //get the value of modal 
        $(document).on('click', '.hr_decline', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('HR_decline') }}', 
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#hr_decline_id").val(response.id);
                    
                }
            });
        });

        //submit request 
        $('#HR_submit_decline_request').on('submit',function (e) {
            e.preventDefault();
           // alert('submitting');

               $('#HR_confirm_btn').text('Submitting');
               $('#HR_confirm_btn').attr("disabled", true);
        
           var DeclineForm = this;

            $.ajax({
            
                url:$(DeclineForm).attr('action'),
                method:$(DeclineForm).attr('method'),
                data: new FormData(DeclineForm),
                processData: false,
                dataType: "json",
                contentType:false,
                success: function (response) {
                    
                $(DeclineForm)[0].reset(); // TO REST FORM
                $('#HR_confirm_btn').removeAttr("disabled");
                $('#HR_confirm_btn').text('Confirm');
                 all_overtime();
                 get_all_employee_attendance(); // reload table 
                $("#decline_ot_HR").modal('hide');
                        
                    // SWEETALERT
                    Swal.fire({
                        icon: 'success',
                        title: 'Decline Request Succesfully',
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



        //get the value 
        $(document).on('click', '.tl_decline', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('TL_decline') }}', 
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#tl_decline_id").val(response.id);
                    
                }
            });
        });

         //submit request 
        $('#TL_submit_decline_request').on('submit',function (e) {
            e.preventDefault();
           // alert('submitting');

               $('#TL_confirm_btn').text('Submitting');
               $('#TL_confirm_btn').attr("disabled", true);
        
           var TLDeclineForm = this;

            $.ajax({
            
                url:$(TLDeclineForm).attr('action'),
                method:$(TLDeclineForm).attr('method'),
                data: new FormData(TLDeclineForm),
                processData: false,
                dataType: "json",
                contentType:false,
                success: function (response) {
                    
                $(TLDeclineForm)[0].reset(); // TO REST FORM
                $('#TL_confirm_btn').removeAttr("disabled");
                $('#TL_confirm_btn').text('Confirm');
                 all_overtime();
                 get_all_employee_attendance(); // reload table 
                $("#decline_ot_TL").modal('hide');
                        
                    // SWEETALERT
                    Swal.fire({
                        icon: 'success',
                        title: 'Decline Request Succesfully',
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


        //get the value 
        $(document).on('click', '.svp_decline', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('SVP_decline') }}', 
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#svp_decline_id").val(response.id);
                    
                }
            });
        });

        //submit request 
        $('#SVP_submit_decline_request').on('submit',function (e) {
            e.preventDefault();
           // alert('submitting');

            $('#SVP_confirm_btn').text('Submitting');
            $('#SVP_confirm_btn').attr("disabled", true);
        
           var SVPDeclineForm = this;

            $.ajax({
            
                url:$(SVPDeclineForm).attr('action'),
                method:$(SVPDeclineForm).attr('method'),
                data: new FormData(SVPDeclineForm),
                processData: false,
                dataType: "json",
                contentType:false,
                success: function (response) {
                    
                $(SVPDeclineForm)[0].reset(); // TO REST FORM
                $('#SVP_confirm_btn').removeAttr("disabled");
                $('#SVP_confirm_btn').text('Confirm');
                 all_overtime();
                 get_all_employee_attendance(); // reload table 
                $("#decline_ot_SVP").modal('hide');
                        
                    // SWEETALERT
                    Swal.fire({
                        icon: 'success',
                        title: 'Decline Request Succesfully',
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

        //get the value VPO
         $(document).on('click', '.vpo_decline', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('VPO_decline') }}', 
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#VPO_decline_id").val(response.id);
                    
                }
            });
        });

         //submit request  VPO
         $('#VPO_submit_decline_request').on('submit',function (e) {
            e.preventDefault();
           // alert('submitting');

            $('#VPO_confirm_btn').text('Submitting');
            $('#VPO_confirm_btn').attr("disabled", true);
        
           var VPODeclineForm = this;

            $.ajax({
            
                url:$(VPODeclineForm).attr('action'),
                method:$(VPODeclineForm).attr('method'),
                data: new FormData(VPODeclineForm),
                processData: false,
                dataType: "json",
                contentType:false,
                success: function (response) {
                    
                $(VPODeclineForm)[0].reset(); // TO REST FORM
                $('#VPO_confirm_btn').removeAttr("disabled");
                $('#VPO_confirm_btn').text('Confirm');
                 all_overtime();
                 get_all_employee_attendance(); // reload table 
                $("#decline_ot_VPO").modal('hide');
                        
                    // SWEETALERT
                    Swal.fire({
                        icon: 'success',
                        title: 'Decline Request Succesfully',
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
       

         //get the value COO
         $(document).on('click', '.coo_decline', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('COO_decline') }}', 
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#COO_decline_id").val(response.id);
                    
                }
            });
        });

         //submit request  COO
         $('#COO_submit_decline_request').on('submit',function (e) {
            e.preventDefault();
           // alert('submitting');

            $('#COO_confirm_btn').text('Submitting');
            $('#COO_confirm_btn').attr("disabled", true);
        
           var COODeclineForm = this;

            $.ajax({
            
                url:$(COODeclineForm).attr('action'),
                method:$(COODeclineForm).attr('method'),
                data: new FormData(COODeclineForm),
                processData: false,
                dataType: "json",
                contentType:false,
                success: function (response) {
                    
                $(COODeclineForm)[0].reset(); // TO REST FORM
                $('#COO_confirm_btn').removeAttr("disabled");
                $('#COO_confirm_btn').text('Confirm');
                 all_overtime();
                 get_all_employee_attendance(); // reload table 
                $("#decline_ot_COO").modal('hide');
                        
                    // SWEETALERT
                    Swal.fire({
                        icon: 'success',
                        title: 'Decline Request Succesfully',
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

        })

       
    });
</script>
@endsection
@endsection
