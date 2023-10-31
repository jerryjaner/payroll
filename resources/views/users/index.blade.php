@extends('layouts.main')
@section('main-content')

<div class="page-row row">
    <div class="col-xl-6">
        <h2 class="page-heading">Users</h2>
    </div>
</div>

<div class="page-container row">
    <div class="col-xl-12">
        <div class="section-container">
            <div class="d-flex align-items-center">
                <i class='bx bx-user-circle section-icon'></i>
                <h5 class="section-header">User Records</h5>
            </div>
            <div class="row table-row" id="show_all_users"></div>
        </div>
    </div>
    {{-- <div class="col-xl-5 right-pane">
        <div class="card employee-card">
            <div class="card-body">
                <form action="{{route('add_users')}}" method="POST" id="AddUser"  enctype="multipart/form-data">
                    @csrf

                    <div class="d-flex align-items-center">
                        <i class='bx bx-user-plus section-icon'></i>
                        <h5 class="section-header">Add User</h5>
                    </div>
                    <div class="form-row row first-row">
                        <div class="col-xl-12">
                            <label for="txt-time">Name:</label>
                            <input type="text" name="employee_name" class="form-control" id="">
                            <span class="text-danger error-text employee_name_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-12">
                            <label for="txt-time">Email</label>
                            <input type="text" name="email" class="form-control" id="">
                            <span class="text-danger error-text email_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Username</label>
                            <input type="text" name="username" class="form-control" id="">
                            <span class="text-danger error-text username_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="txt-time">Roles</label>
                            <select name="role" id="" class="form-select form-control">
                                <option value="">Select a role</option>
                                <option value="1">Administrator</option>
                                <option value="2">HR</option>
                                <option value="3">Accounting</option>
                                <option value="4">Employee</option>
                                <option value="5">Attendance</option>
                                <option value="6">Manager</option>
                                <option value="7">Team Leader</option>
                            </select>
                            <span class="text-danger error-text role_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Password</label>
                            <input type="password" name="password" class="form-control" id="">
                            <span class="text-danger error-text password_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="txt-time">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" id="">
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
                        <div class="col-xl-12">
                            <label for="txt-time">Employee Picture</label>
                            <input type="file" name="image" class="form-control" id="">
                            <span class="text-danger error-text image_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Employee No:</label>
                            <input type="text" name="employee_no" class="form-control" id="">
                            <span class="text-danger error-text employee_no_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Position:</label>
                            <input type="text" name="position" class="form-control" id="">
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
                                <option value="Orenda">Orenda</option>
                                <option value="IT">IT</option>
                                <option value="Project Management">Project Management</option>
                                <option value="Provider Relation">Provider Relation</option>
                                <option value="Provider Enrollment">Provider Enrollment</option>
                            </select>
                            <span class="text-danger error-text employee_department_error"></span>
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
                            <input type="time" name="sched_start" class="form-control" id="">
                            <span class="text-danger error-text sched_start_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="txt-time">End:</label>
                            <input type="time" name="sched_end" class="form-control" id="">
                            <span class="text-danger error-text sched_end_error"></span>
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
                                <i class='bx bxs-user-detail'></i>
                                Other Information
                            </span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Date Hired:</label>
                            <input type="date" name="date_hired" class="form-control" id="">
                            <span class="text-danger error-text date_hired_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="txt-time">Birthday:</label>
                            <input type="date" name="employee_birthday" class="form-control" id="">
                            <span class="text-danger error-text employee_birthday_error"></span>
                        </div>
                    </div>
                    <div class="form-row first-row">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn-form d-flex align-items-center" id="btnSubmit">
                                <i class='bx bxs-user-plus'></i>
                                Add User
                            </button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
</div>

{{-- MODAL FOR EDIT --}}

<div class="modal fade modal-dialog-scrollable" id="EditUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body ">
                <form action="{{route('update_user')}}" method="POST" id="update_user"  enctype="multipart/form-data">
                    @csrf

                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">Edit User</h5>
                    </div>

                    <input type="hidden" name="user_id" id="user_id">
               
                    <div class="mt-2" id="profile_image" >

                    </div>
                    <div class="form-row row first-row">
                        <div class="col-xl-12">
                            <label for="txt-time">Employee Picture</label>
                            <input type="file" name="profile_image" class="form-control" id="profile_image">
                            <span class="text-danger error-text profile_image_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-12">
                            <label for="txt-time">Name:</label>
                            <input type="text" name="name" class="form-control" id="name" readonly>
                            <span class="text-danger error-text name_error"></span>
                        </div>
                    </div>
                    <div class="form-row row">
                        <div class="col-xl-12">
                            <label for="txt-time">Email</label>
                            <input type="text" name="email" class="form-control" id="email">
                            <span class="text-danger error-text email_error"></span>
                        </div>
                    </div>
        
                    <div class="form-row row">
                        <div class="col-xl-6">
                            <label for="txt-time">Username</label>
                            <input type="text" name="username" class="form-control" id="username">
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
                    <div class="mt-4 gap-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="closed" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form btn-sm modal-btn" id="update_user_btn" >Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- END OF MODAL --}}

{{-- Change password modal --}}
<div class="modal fade modal-dialog-scrollable" id="ChangePassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body ">
                
                <div class="d-flex align-items-center">
                    <i class='bx bx-key section-icon'></i>
                    <h5 class="section-header">Change Password</h5>
                </div>

                <form action="{{ route('update_password') }}" method="POST" method="POST" id="update_password"  enctype="multipart/form-data">
                @csrf
                    
                        <input type="hidden" name="usern_id" id="users_id">
                        
                        <div class="form-row row mt-2">
                            <div class="col-xl-12">
                                <label for="txt-time">Name:</label>
                                <input type="text" name="emp_name" class="form-control" id="emp_name" readonly>
                            </div>
                            <div class="col-xl-12">
                                <label for="txt-time">New Password:</label>
                                <input type="password" name="new_password" class="form-control" id="new_password">
                                <span class="text-danger error-text new_password_error"></span>
                            </div>
                            <div class="col-xl-12">
                                <label for="txt-time">Confirm New Password:</label>
                                <input type="password" name="confirm_password" class="form-control" id="confirm_password">
                                <span class="text-danger error-text confirm_password_error"></span>
                            </div>
                        </div>
                        
                    <div class="d-flex justify-content-end mt-4 gap-2">
                        <button type="button" onclick="ClearFields();" class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form btn-sm modal-btn" id="update_password_btn" >Submit</button>
                    </div>

                </form>
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

        fetch_users();
        function fetch_users(){
            $.ajax({
                url: '{{ route('get_user') }}',
                method: 'GET',
                success: function(response) {
                $("#show_all_users").html(response);
                $("#user_table").DataTable({
                    "order": [[ 0, "desc" ]]
                });
                }
            });
        }
            
        
        // edit user ajax request
        $(document).on('click', '.editIcon', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('edit_user') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },

                success: function(response) 
                {
                    $("#user_id").val(response.id);
                    $("#name").val(response.name);
                    $("#email").val(response.email);
                    $("#username").val(response.username);
                    $("#role").val(response.role);
                    $("#profile_image").val(response.profile_image);
                    var avatar = response.profile_image;
                    if(avatar != null)
                    {
                        $("#profile_image").html( `<img src="storage/user/images/${response.profile_image}" class="modal_image border border-2">`);
                    }
                    else
                    {
                        $("#profile_image").html( `<img src="storage/user/images/${response.profile_image}" style="display:none">`);
                    }
                }
            });
        });
                
        // update employee ajax request
        $("#update_user").on('submit',function(e) {

            e.preventDefault();
            $("#update_user_btn").text('Updating...');
            $('#update_user_btn').attr("disabled", true);
            var updt = this;
            $.ajax({
                url:$(updt).attr('action'),
                method:$(updt).attr('method'),
                data: new FormData(updt),
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function(){
                //Before Sending The Form
                   $(updt).find('span.error-text').text('')
                },

                success: function(response) {

                    if (response.code == 0) 
                    {
                        $('#update_user_btn').removeAttr("disabled");
                        $.each(response.error, function(prefix, val){
                              $(updt).find('span.'+prefix+'_error').text(val[0]);
                        });
                        $('#update_user_btn').text('Update User');
                    }
                    else
                    {     
                        $(updt)[0].reset();
                        $("#update_user_btn").text('Update User');
                        $('#update_user_btn').removeAttr("disabled");
                        $("#EditUser").modal('hide');
                        fetch_users();
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
                    $('#closed').on('click', function () {
                        $(updt).find('span.error-text').text('');
                    });
                }
            });
        });
        // end of update

        //delete user
        $(document).on('click', '.deleteIcon', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            let csrf = '{{ csrf_token() }}';
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to recover this!",
                icon: 'warning',
                iconColor: 'rgb(188 61 79)',
                showCancelButton: true,
                confirmButtonColor: '#bc3d4f',
                confirmButtonText: 'Confirm',
                confirmButtonColor: '#bc3d4f',
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('delete_user') }}',
                        method: 'delete',
                        data: {
                            id: id,
                            _token: csrf
                        },
                        success: function(response) {
                            console.log(response);
                            fetch_users();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted Successfully',
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

        //view password
        $(document).on('click', '.editPassword', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('edit_user') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },

                success: function(response) 
                {
                    $("#users_id").val(response.id);
                    $("#emp_name").val(response.name);
                }
            });
        });
        
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
                        $("#ChangePassword").modal('hide');
                        $(pass)[0].reset();
                        fetch_users();
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
                        $(pass).find('span.error-text').text('');
                    });
                }
            });
        });
        // end of update
    });
    //clear input in change password
    function ClearFields()
    {
        document.getElementById("new_password").value = "";
        document.getElementById("confirm_password").value = "";
    }
</script>
@endsection
@endsection