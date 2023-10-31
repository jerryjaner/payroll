@extends('layouts.main')
@section('main-content')
<div class="page-row row">
    <div class="col-xl-6">
        <h2 class="page-heading">Holiday</h2>
    </div>
</div>
<div class="page-container row">
    <div class="col-xl-12">
        <div class="section-container">
            <div class="row">
                <div class="col-sm-12 col-md-7 d-flex justify-content-start m-auto">
                    <i class='bx bx-book section-icon'></i>
                    <h5 class="section-header">Holidays in Philippines</h5>
                </div>
                <div class="col-sm-12 col-md-5 d-flex justify-content-end m-auto">
                    <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                        @if(Auth::user()->hasRole(['HR','assistantHR']))
                            <a href="#" type="button" class="btn btn-success btn-sm float-right" data-bs-toggle="modal" data-bs-target="#add_holidays">
                                <i class="bx bx-plus"></i>
                                Add Holiday
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row table-row" id="show_all_holidays">

            </div>
        </div>
    </div>
</div>

{{-- Add Holiday--}}
<div class="modal fade" id="add_holidays" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('add_holiday') }}" method="POST" id="add_holiday"  enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex align-items-center">
                        <i class='bx bx-plus section-icon'></i>
                        <h5 class="section-header">Add Holiday</h5>
                    </div>

                    <div class="row form-row">
                        <!-- <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center">

                                Range of Compension
                            </span>
                        </div> -->
                        <div class="col-xl-12">
                            <label for="">Holiday Name:</label>
                            <input type="text" name="holiday_name" class="form-control">
                            <span class="text-danger error-text holiday_name_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="col-xl-6">
                            <label for="">Holiday Date:</label>
                            <input type="date" name="holiday_date" class="form-control">
                            <span class="text-danger error-text holiday_date_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Holiday Type:</label>
                            <select name="holiday_type" class="form-select" id="txt-type">
                                <option value="">Select Type</option>
                                <option value="Regular">Regular Holiday</option>
                                <option value="Special">Special Holiday</option>
                            </select>
                            <span class="text-danger error-text holiday_type_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close_holiday" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit"  class="btn btn-form btn-sm modal-btn" id="add_holiday_submit">Submit </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Add Holiday --}}
{{-- EDIT Holiday --}}
<div class="modal fade" id="edit_holidays" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('update_holiday') }}" method="POST" id="update_holiday"  enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex align-items-center">
                        <i class='bx bx-plus section-icon'></i>
                        <h5 class="section-header">Add Holiday</h5>
                    </div>
                    <input type="hidden" name="holiday_id" id="holiday_id">
                    <div class="row form-row">
                        <!-- <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center">

                                Range of Compension
                            </span>
                        </div> -->
                        <div class="col-xl-12">
                            <label for="">Holiday Name:</label>
                            <input type="text" name="holiday_name" class="form-control" id="holiday_name">
                            <span class="text-danger error-text holiday_name_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="col-xl-6">
                            <label for="">Holiday Date:</label>
                            <input type="date" name="holiday_date" class="form-control" id="holiday_date">
                            <span class="text-danger error-text holiday_date_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Holiday Type:</label>
                            <input type="text" name="holiday_type" class="form-control" id="holiday_type">
                            <span class="text-danger error-text holiday_type_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close_edit_holiday" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit"  class="btn btn-form btn-sm modal-btn" id="edit_holiday_submit">Submit </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End edit Holiday --}}


@section('page-scripts')

<script>
    $(document).ready(function () {

        $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
        });

        holiday();
            function holiday(){
                $.ajax({
                    url: '{{ route('holiday_list') }}',
                    method: 'GET',
                    success: function(response) {
                        $("#show_all_holidays").html(response);
                        $("#holiday_table").DataTable({
                            "order": [[ 0, "asc" ]]
                        });
                    }
                });
            }

        $("#add_holiday").on('submit', function (e) {
            e.preventDefault();
            $("#add_holiday_submit").text('Submitting . . . ');
            $('#add_holiday_submit').attr("disabled", true);
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
                        $('#add_holiday_submit').removeAttr("disabled");

                        $.each(response.error, function(prefix, val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });

                        $("#add_holiday_submit").text('Submit');

                    }
                    else{

                        $(form)[0].reset();
                        $('#add_holiday_submit').removeAttr("disabled");
                        $('#add_holiday_submit').text('Submit');
                        holiday();
                        $("#add_holidays").modal('hide'); //hide the modal

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

                    $('#close_holiday').on('click', function () {
                        $(form).find('span.error-text').text('');
                    });

                }
            });
        });

        $(document).on('click', '.holiday_edit_icon', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('edit_holiday') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },

                success: function(response){

                    $("#holiday_id").val(response.id);
                    $("#holiday_name").val(response.holiday_name);
                    $("#holiday_date").val(response.holiday_date);
                    $("#holiday_type").val(response.holiday_type);

                }
            });
        });

        $("#update_holiday").on('submit', function (e) {
            e.preventDefault();
            $("#edit_holiday").text('Submitting . . . ');
            $('#edit_holiday').attr("disabled", true);
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
                        $('#edit_holiday').removeAttr("disabled");

                        $.each(response.error, function(prefix, val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });

                        $("#edit_holiday").text('Submit');

                    }
                    else{

                        $(form)[0].reset();
                        $('#edit_holiday').removeAttr("disabled");
                        $('#edit_holiday').text('Submit');
                        holiday();
                        $("#edit_holidays").modal('hide');


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

                    $('#close_edit_holiday').on('click', function () {
                        $(form).find('span.error-text').text('');
                    });

                }
            });
        });

        $(document).on('click', '.holiday_delete_icon', function(e) {
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
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('delete_holiday') }}',
                        method: 'delete',
                        data: {
                            id: id,
                            _token: csrf
                        },
                        success: function(response) {
                            holiday();
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
    });
</script>

@endsection
@endsection
