@extends('layouts.main')
@section('main-content')

    <div class="page-row row">
        <div class="col-xl-6">
            <h2 class="page-heading">Attendance</h2>
        </div>
        <div class="col-xl-6">
            <ul class="nav nav-tabs d-flex justify-content-end" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="tab active d-flex align-items-center" id="tracker-tab" data-bs-toggle="tab" href="#tracker"
                        role="tab" aria-controls="tracker" aria-selected="false">
                        <i class='bx bx-time-five'></i>
                        Time Tracker
                    </a>
                </li>

                <li class="nav-item">
                    <a class="tab d-flex align-items-center ms-2" id="all-tab" data-bs-toggle="tab" href="#all"
                        role="tab" aria-controls="all" aria-selected="false">
                        <i class='bx bx-list-ul'></i>
                        All Attendance
                    </a>
                </li>

                <li class="nav-item">
                    <a class="tab d-flex align-items-center ms-2" id="leave-tab" data-bs-toggle="tab" href="#leave"
                        role="tab" aria-controls="leave" aria-selected="false">
                        <i class='bx bx-calendar-x'></i>
                        On Leave
                    </a>
                </li>

                <li class="nav-item">
                    <a class="tab d-flex align-items-center ms-2" id="absent-tab" data-bs-toggle="tab" href="#absent"
                        role="tab" aria-controls="absent" aria-selected="false">
                        <i class='bx bx-time'></i>
                       Absent/Onleave
                    </a>
                </li>

            </ul>

        </div>
    </div>

    <div class="page-container row">
        <div class="tab-content" id="myTabContent" style="margin-top: 30px;">

                {{-- Time Tracker Tab --}}
                <div class="tab-pane fade show active" id="tracker" role="tabpanel" aria-labelledby="tracker-tab">
                    <div class="row">
                        @if(Auth::user()->hasRole(['attendance']))
                            <div class="col-xl-7">

                                <div class="section-container">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class='bx bx-book section-icon'></i>
                                            <h5 class="section-header">Attendance Record</h5>
                                        </div>

                                        <div class="time-date">
                                            <p>Manila, PH (GMT+8)</p>
                                            <div id="MyClockDisplay" class="clock" onload="showTime()"></div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 id="dateNow" class="dateNow"></h6>
                                                <p id="day" class="day"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row table-row" id="show_all_tracking">
                                        <!-- Attendance Record -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-5 right-pane">
                                <div class="card mode-card">
                                    <div class="card-body">
                                        <ul class="nav nav-tabs d-flex justify-content-end align-items-center" id="mode" role="tablist">
                                            <li class="nav-item">
                                                <a class="tab d-flex align-items-center mode-link" id="rfid-tab" data-bs-toggle="tab" href="#rfid"
                                                    role="tab" aria-controls="rfid" aria-selected="false" data-tippy-content="Use RFID" data-tippy-arrow="false">
                                                    <i class='bx bx-id-card'></i>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="tab active d-flex align-items-center mode-link ms-2" id="qr-tab" data-bs-toggle="tab" href="#qr"
                                                    role="tab" aria-controls="qr" aria-selected="false" data-tippy-content="Use QR" data-tippy-arrow="false">
                                                    <i class='bx bx-qr' ></i>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="row">
                                            <div class="tab-content" id="modeContent" style="margin-top: 30px;">
                                                <div class="tab-pane fade show" id="rfid" role="tabpanel" aria-labelledby="rfid-tab">
                                                    <div class="d-flex align-items-center scanner-header">
                                                        <i class='bx bx-rfid section-icon'></i>
                                                        <h5 class="employee-header">RFID Scanner</h5>
                                                    </div>
                                                    <div id="rfid-note">
                                                        <div class="text-center">
                                                            <i class='bx bx-rss'></i>
                                                            <h5>Place the RFID Tag on the scanner</h5>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade show active" id="qr" role="tabpanel" aria-labelledby="qr-tab">
                                                    <div class="d-flex align-items-center scanner-header">
                                                        <i class='bx bx-qr-scan section-icon'></i>
                                                        <h5 class="employee-header">QR Scanner</h5>
                                                    </div>
                                                    <video id="preview"></video>


                                                    <ul id="save_msgList" style="list-style: none; text-align:center" ></ul>
                                                    <div id="employee_attendance" class="QR">
                                                        <input type="hidden" name="attendance_id" id="attendance_id">

                                                        <input type="text" class="form-control  scanned" name="scanned" id="empID" style="text-align: center; font-size:16px;" placeholder="Employee Number"  >
                                                        <span class="text-danger error-text scanned_error"></span>

                                                        {{-- <div class="col-xl-12">
                                                            <label for="txt-time" class="QR">Time-In:</label>
                                                            <input type="time" value="now" name="time_in QR" class="form-control QR" id="time_in" >
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <label for="txt-time" class="QR">Time-Out:</label>
                                                            <input type="time" value="now" name="time_out" class="form-control QR" id="time_out" >
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <label for="txt-time" class="QR">Date</label>
                                                            <input type="date" name="date_work" class="form-control QR" id="theDate" >
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <label for="txt-time" class="QR">Date Time:</label>
                                                            <input type="datetime-local" value="" name="night_shift_date" class="form-control QR" id="cal" >
                                                        </div> --}}
                                                        
                                                        <div class="form-row first-row">
                                                            <div class="d-flex justify-content-end">
                                                                 <button id="my-btn" class="btn btn-primary btn-sm mt-1 float-end  add_attendance" type="submit">Submit</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="d-flex align-items-center mt-5">
                                                <i class='bx bx-scan section-icon'></i>
                                                <h5 class="employee-header">Last Scanned</h5>
                                            </div>
                                            <div class="employee-details">
                                                <div class="d-flex justify-content-between align-items-start"  id="show_first_scan_employee">
                                                    {{-- For the employee details --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <h4 class="text-center text-secondary my-5">You have no permission to access this page.</h4>
                        @endif
                    </div>
                </div>


                {{-- All Attendance Tab --}}
                <div class="tab-pane fade show" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <div class="row">
                        <div class="col-xl-12">
                            @if(Auth::user()->hasRole(['HR','accounting','administrator','assistantHR','CEO']))
                                <div class="section-container">
                                    <div class="d-flex align-items-center">
                                        <i class='bx bx-book section-icon'></i>
                                        <h5 class="section-header">All Attendance</h5>
                                    </div>
                                    <div class="row-table-row" id="show_attendance_all">

                                    </div>
                                </div>
                            @else
                                <h4 class="text-center text-secondary my-5">Only Accounting and Human Resources have access to this Website.</h4>

                            @endif
                        </div>
                        {{-- <div class="col-xl-5 right-pane">
                            <div class="card modify-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class='bx bx-edit section-icon'></i>
                                        <h5 class="section-header">Modify Record</h5>
                                    </div>
                                    <div class="employee-details">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="d-flex align-items-center">
                                                <i class='bx bx-user icon profile-picture-details'></i>
                                                <div class="ms-3">
                                                    <h5 class="emp-name">Pedro Santiago</h5>
                                                    <p class="emp-no">EMP NO: 0002</p>
                                                    <span class="title">
                                                        <i class='bx bxs-user-badge'></i>
                                                        Medical Credentialist
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <button class="btn-view" data-tippy-content="View Profile" data-tippy-arrow="false">
                                                    <i class='bx bx-user-circle'></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row row first-row">
                                        <div class="col-xl-6">
                                            <label for="txt-time">Time:</label>
                                            <input type="text" name="" class="form-control" id="txt-time">
                                        </div>
                                        <div class="col-xl-6">
                                            <label for="txt-type">Type:</label>
                                            <select name="" class="form-select" id="txt-type">
                                                <option value="">Select Type</option>
                                                <option value="">Time In</option>
                                                <option value="">Time Out</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>


                {{-- On Leave Tab --}}

                <div class="tab-pane fade show" id="leave" role="tabpanel" aria-labelledby="leave-tab">
                    <div class="row">
                        @if(Auth::user()->hasRole(['HR','administrator','assistantHR','CEO']))
                            <div class="col-xl-7">

                                <div class="card calendar-card">
                                    <div class="card-body">
                                        <div id="calendar-leave"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-5 right-pane">
                                <div class="section-container">
                                    <div class="d-flex align-items-center">
                                        <i class='bx bx-file section-icon'></i>
                                        <h5 class="section-header">Leave Record</h5>
                                    </div>
                                    <div class="row table-row">
                                        <table class="time-tbl table" style="width: 100%">
                                            <thead>
                                                <th></th>
                                            </thead>

                                            <tbody>

                                                @foreach($allReq as $emp)

                                                <tr>
                                                    <td>
                                                        <div class="card time-card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="d-flex justify-content-between align-items-start">
                                                                        <div class="d-flex align-items-center">
                                                                            <i class='bx bx-user icon profile-picture-time'></i>
                                                                            <div class="ms-3">

                                                                                <h5 class="emp-name">{{$emp -> name }}</h5>
                                                                                <p class="emp-no">{{$emp -> employee_no}}</p>

                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex">
                                                                            <button class="btn-view" data-tippy-content="View Profile" data-tippy-arrow="false">
                                                                                <i class='bx bx-user-circle'></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row leave-row">
                                                                        <div class="col-xl-8 d-flex">

                                                                            <span class="status leave-date d-flex align-items-center">
                                                                                <i class='bx bx-calendar'></i>
                                                                                <p class="emp-no">
                                                                                {{ \Carbon\Carbon::parse($emp -> start_date)->toFormattedDateString('F j, Y') }} -
                                                                                {{ \Carbon\Carbon::parse($emp -> end_date)->toFormattedDateString('F j, Y') }}
                                                                                </p>
                                                                            </span>

                                                                        </div>
                                                                        <div class="col-xl-4 d-flex justify-content-end">
                                                                            <span class="status leave-vl d-flex align-items-center">
                                                                                <i class='bx bx-file'></i>
                                                                                VL
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                             <h4 class="text-center text-secondary my-5">Only Human Resources have access to this Website.</h4>
                        @endif
                    </div>
                </div>

                   {{-- absent  Tab --}}

                <div class="tab-pane fade show" id="absent" role="tabpanel" aria-labelledby="absent-tab">
                    <div class="row">
                        @if(Auth::user()->hasRole(['HR','teamleader','assistantHR','CEO']))
                            <div class="col-xl-7">
                                <div class="section-container">
                                    <div class="d-flex align-items-center">
                                        <i class='bx bx-group section-icon'></i>
                                        <h5 class="section-header">Absent/Onleave Records</h5>
                                    </div>
                                    <div class="row table-row" id="absent_onleave">
                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-5 right-pane">
                                <div class="card employee-card">
                                    <div class="card-body">
                                        <form action="{{ route('absent_onleave') }}" method="POST" id="add_absent_onleave"  enctype="multipart/form-data">
                                            @csrf
                                            <div class="d-flex align-items-center">
                                                <i class='bx bx-user-plus section-icon'></i>
                                                <h5 class="section-header">Absent / Onleave</h5>
                                            </div>
                                            <div class="form-row row first-row">
                                                <div class="col-xl-12">
                                                    <label for="txt-time">Employee Number:</label>
                                                    <input type="text" name="employee_number" class="form-control" >
                                                    <span class="text-danger error-text employee_number_error"></span>
                                                </div>
                                            </div>
                                            <div class="form-row row">
                                                <div class="col-xl-12">
                                                    <label for="txt-time">Status:</label><br>
                                                    <input type="radio"  name="status" value="absent"> Absent <br>
                                                    <input type="radio" name="status" value="onleave"> Onleave
                                                </div>
                                                <span class="text-danger error-text status_error"></span>
                                            </div>

                                             <div class="form-row row">
                                                <div class="col-xl-12">
                                                    <label for="txt-time">Status:</label><br>
                                                    <input type="radio"  name="RH"  value="1"> Regular Holiday <br>
                                                    <input type="radio"  name="SH" value="0"> Special Holiday
                                                </div>
                                                
                                            </div>
                                        
                                            <div class="form-row first-row">
                                                <div class="d-flex justify-content-center">
                                                    <button type="submit" class="btn-form d-flex align-items-center" id="submit_absent_onleave">
                                                        Submit
                                                    </button>
                        
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <h4 class="text-center text-secondary my-5">Only Human Resources and Team Leader have access to this Website.</h4>
                        @endif
                    </div>
                </div>


        </div>
    </div>

{{-- MODAL FOR EDIT --}}

<div class="modal fade modal-dialog-scrollable" id="ModifyTime" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{route('update_mod')}}" method="POST" id="update_mod"  enctype="multipart/form-data">
                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">Modify Record</h5>
                    </div>

                    <input type="text" name="attt_id" id="attt_id" hidden>

                    <div class="form-row row mt-2">
                        <div class="col-xl-12">
                            <label for="txt-time">Employee Name:</label>
                            <input type="text" name="emp_names" class="form-control" id="emp_names" readonly>
                            <span class="text-danger error-text emp_names_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="txt-time">Time In:</label>
                            <input type="time" name="time_in" class="form-control" id="in">
                            <span class="text-danger error-text time_in_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="txt-time">Time Out:</label>
                            <input type="time" name="time_out" class="form-control" id="out">
                            <span class="text-danger error-text time_out_error"></span>
                        </div>
                    </div>

                    <div class="mt-3 gap-2 d-flex justify-content-end">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="modify_rec">Modify</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- END OF MODAL --}}
    @section('page-scripts')

        <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js" rel="nofollow"></script>
     
        <script>
          
           

        </script>
        <script>

             document.addEventListener('DOMContentLoaded', function() {

                var calendarEl = document.getElementById('calendar-leave');
                var leaveRequest = @json($alleave_reqs);
                var calendar = new FullCalendar.Calendar(calendarEl, {

                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'title',
                        center: 'dayGridMonth,listMonth',
                        right: 'prev,next'
                    },
                    // timeFormat: 'H(:mm)',
                    events: leaveRequest,


                });

                calendar.render();

                var calendarInit = false;

                $('#myTab a').on('shown.bs.tab', function (e) {
                    if($(e.target).attr('href') == '#leave' && !calendarInit) {
                        calendar.render();
                    }
                });

        
            });

               function showTime() {
                var options = {
                    timeZone: 'Asia/Manila',
                    hour12: true,
                    hour: '2-digit',
                    minute: '2-digit'
                };

                var time = new Date().toLocaleString('en-US', options);
                
                document.getElementById("MyClockDisplay").innerText = time;
                document.getElementById("MyClockDisplay").textContent = time;

                setTimeout(showTime, 1000);
            }

            showTime();


            $(document).ready(function() {
                var dateNow = moment().format('MMM DD, YYYY');
                var day = moment().day();
                // var output = '';

                if (day === 1) {
                    var output = "Mon";
                } else if (day === 2) {
                    var output = "Tue";
                } else if (day === 3) {
                    var output = "Wed";
                } else if (day === 4) {
                    var output = "Thu";
                } else if (day === 5) {
                    var output = "Fri";
                } else if (day === 6) {
                    var output = "Sat";
                } else  {
                    var output = "Sun";
                }

                $('#dateNow').html(dateNow);
                $('#day').html(output);
            });
        </script>

        {{-- scanner --}}
        <script type="text/javascript">

            var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false, refractoryPeriod: 5000,   continuous: true, });
            scanner.addListener('scan',function(content){

                // alert(content);

                    const myBtn = document.getElementById('my-btn')
                    scanner.addListener('scan', function(c){
                    document.getElementById('empID').value = c;

                    myBtn.click();

                  });
            });
            Instascan.Camera.getCameras().then(function (cameras){
                if(cameras.length>0){
                    scanner.start(cameras[0]);
                    $('[name="options"]').on('change',function(){
                        if($(this).val()==1){
                            if(cameras[0]!=""){
                                scanner.start(cameras[0]);
                            }else{
                                alert('No Front camera found!');
                            }
                        }else if($(this).val()==2){
                            if(cameras[1]!=""){
                                scanner.start(cameras[1]);
                            }else{
                                alert('No Back camera found!');
                            }
                        }
                    });
                }else{
                    console.error('No cameras found.');
                    alert('No cameras found.');
                }
            }).catch(function(e){
                console.error(e);
                alert(e);
            });
        </script>


        {{-- ajax here --}}
        <script>
        $(document).ready(function () {

            //CSRF TOKEN
            $.ajaxSetup({

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });

           

            // for the employee details in attendance who scanned
            get_first_scanned();
            function get_first_scanned(){
                $.ajax({
                    url: '{{ route('first_scanned') }}',
                    method: 'GET',
                    success: function(response) {
                        $("#show_first_scan_employee").html(response);
                    }
                });
            }
            // end of employee details in attendance

            //TO FETCH ALL THE DATA IN THE TABLE
            // Attendance Record
            fetchAttendance();
            function fetchAttendance(){
                $.ajax({
                    url: '{{ route('get_attendance') }}',
                    method: 'GET',
                    success: function(response) {
                        $("#show_all_tracking").html(response);
                        $("#tracking_table").DataTable({
                            "order": [[ 0, "desc" ]]
                        });
                    }
                });
            }
            // All Attendance
            AllAttendance();
            function AllAttendance(){
                $.ajax({
                    url: '{{ route('all_attendance') }}',
                    method: 'GET',
                    success: function(response) {
                        $("#show_attendance_all").html(response);
                        $("#attendance_table").DataTable({
                            "order": [[ 0, "desc" ]]
                        });
                    }
                });
            }

            absent_onleave();
            function absent_onleave(){
                $.ajax({
                    url: '{{ route('absent_onleave_attendance') }}',
                    method: 'GET',
                    success: function(response) {
                        $("#absent_onleave").html(response);
                        $("#onleave_absent").DataTable({
                            "order": [[ 0, "desc" ]]
                        });
                    }
                });
            }


            //For attedance using scanner
            $('.add_attendance').on('click', function (e) {
                e.preventDefault();
                $("#my-btn").text('Submitting . . . ');
                $('#my-btn').attr("disabled", true);


                var data = {

                    'scanned': $('#empID').val(),
                    // 'time_in': $('#time_in').val(),
                    // 'time_out': $('#time_out').val(),
                    // 'date_work': $('#theDate').val(),
                    // 'night_shift_date': $('#cal').val(),
                }

                setTimeout(function() {

                    $.ajax({
                        type: "POST",
                        url: "/attendance",
                        data:  data,
                        dataType: "json",
                        beforeSend: function(){

                                //Before Sending The Form
                                $('#save_msgList').html("");
                                $('#save_msgList').removeClass('alert alert-danger');
                            },
                        success: function (response) {
                            // console.log(response);
                            if (response.status == 400) {
                                $('#my-btn').removeAttr("disabled");
                                $('#save_msgList').html("");
                                $('#save_msgList').addClass('alert alert-danger');
                                $.each(response.errors, function (key, err_value) {
                                    $('#save_msgList').append('<li>' + err_value + '</li>');
                                });
                                $('#empID').val('');
                                $('#my-btn').text('Submit');
                            }
                            //if the user scan again for the 3rd time which he already completed time in time out
                            else if (response.status == 0) {

                                $('#my-btn').removeAttr("disabled");
                                $('#save_msgList').html("");
                                $('#save_msgList').addClass('alert alert-danger');
                                $('#save_msgList').append('<li>' + 'Your attendance for today is already completed.' + '</li>');
                                $('#empID').val('');
                                $('#my-btn').text('Submit');
                            }

                            else {

                                $('#my-btn').removeAttr("disabled");
                                $('#my-btn').text('Submit');
                                $('#save_msgList').html("");
                                $('#success_message').addClass('alert alert-success');
                                $('#empID').val('');

                                fetchAttendance();
                                AllAttendance();
                                get_first_scanned();
                                    // SWEETALERT
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Attendance Recorded Successfully',
                                        // text: 'Attendance Recorded',
                                        showConfirmButton: false,
                                        timer: 3000,
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

                },1000);

            });

            // // edit employee ajax request
            // $(document).on('click', '.modify', function(e) {
            //     e.preventDefault();
            //     let id = $(this).attr('id');
            //     $.ajax({
            //         url: '{{ route('edit_mod') }}',
            //         method: 'get',
            //         data: {
            //             id: id,
            //             _token: '{{ csrf_token() }}'
            //         },

            //         success: function(response)
            //         {
            //             $("#attendance_id").val(response.id);
            //             $("#employee_name").val(response.employee.employee_name);
            //             $("#emp_no").val(response.emp_no);
            //             $("#time_in").val(response.time_in);
            //             $("#time_out").val(response.time_out);
            //         }
            //     });
            // });

            // edit time
            $(document).on('click', '.modify', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                $.ajax({
                url: '{{ route('edit_mod') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#attt_id").val(response.id);
                    $("#emp_names").val(response.employee.employee_name);
                    $("#emp_num").val(response.emp_no);
                    $("#in").val(response.time_in);
                    $("#out").val(response.time_out);
                }
                });
            });

            // update time ajax request
            $("#update_mod").on('submit',function(e) {

                e.preventDefault();
                $("#modify_rec").text('Modifying...');
                $('#modify_rec').attr("disabled", true);
                var mod = this;

                $.ajax({

                    url:$(mod).attr('action'),
                    method:$(mod).attr('method'),
                    data: new FormData(mod),
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function(){
                        //Before Sending The Form
                        $(mod).find('span.error-text').text('')
                    },

                    success: function(response) {
                        if (response.code == 0)
                        {
                            $('#modify_rec').removeAttr("disabled");
                            $.each(response.error, function(prefix, val){
                                $(mod).find('span.'+prefix+'_error').text(val[0]);
                            });
                            $('#modify_rec').text('Modify');
                        }
                        else
                        {
                            $(mod)[0].reset();
                            $("#modify_rec").text('Modify');
                            $('#modify_rec').removeAttr("disabled");
                            $("#ModifyTime").modal('hide');
                            fetchAttendance();
                            AllAttendance();
                            Swal.fire({
                                icon: 'success',
                                title: 'Modified Successfully',
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
                            $(mod).find('span.error-text').text('')
                        });
                    }
                });
            });
            // end of update


            $("#add_absent_onleave").on('submit',function (e) {
                e.preventDefault();
                $("#submit_absent_onleave").text('Submitting . . .');
                $('#submit_absent_onleave').attr("disabled", true);

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
                            $('#submit_absent_onleave').removeAttr("disabled"); // removing disabled button
                            //The Error Message Will Append
                            $.each(response.error, function(prefix, val){
                                $(form).find('span.'+prefix+'_error').text(val[0]);
                            });
                            $('#submit_absent_onleave').text('Submit');
                        }
                        else
                        {

                            $(form)[0].reset(); // TO REST FORM
                            $('#submit_absent_onleave').removeAttr("disabled"); // removing disabled button
                            $('#submit_absent_onleave').text('Submit');   //change the text to normal
                            absent_onleave();
                            AllAttendance();

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
        });
        </script>

    @endsection

@endsection
