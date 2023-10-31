@extends('layouts.main')
@section('main-content')

<div class="page-row row">
    <div class="col-xl-6">
        <h2 class="page-heading">Deduction</h2>
    </div>
    <div class="col-xl-6">
        <ul class="nav nav-tabs d-flex justify-content-end" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="tab active d-flex align-items-center" id="sss-tab" data-bs-toggle="tab" href="#sss"
                    role="tab" aria-controls="sss" aria-selected="false">
                    <i class='bx bx-list-ul'></i>
                   Social Security System
                </a>
            </li>
            <li class="nav-item">
                <a class="tab d-flex align-items-center ms-2" id="pag_ibig-tab" data-bs-toggle="tab" href="#pag_ibig"
                    role="tab" aria-controls="pag_ibig" aria-selected="false">
                    <i class='bx bx-list-ul'></i>
                  Pag Ibig
                </a>
            </li>
            <li class="nav-item">
                <a class="tab d-flex align-items-center ms-2" id="phil_health-tab" data-bs-toggle="tab" href="#phil_health"
                    role="tab" aria-controls="phil_health" aria-selected="false">
                    <i class='bx bx-list-ul'></i>
                  Phil Health
                </a>
            </li>

        </ul>
    </div>
</div>

<div class="page-container row">
    <div class="tab-content" id="myTabContent" style="margin-top: 30px;">

        {{-- SSS--}}

        <div class="tab-pane fade show active" id="sss" role="tabpanel" aria-labelledby="sss-tab">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-container">

                        <div class="row">
                            <div class="col-sm-12 col-md-7 d-flex justify-content-start m-auto">
                                <i class='bx bx-book section-icon'></i>
                                <h5 class="section-header">SSS Deduction</h5>
                            </div>
                            <div class="col-sm-12 col-md-5 d-flex justify-content-end m-auto">
                                <div class="col-sm-12 col-md-6 d-flex justify-content-end">

                                    @if(Auth::user()->hasRole('accounting'))
                                    <a href="#" type="button" class="btn btn-success btn-sm float-right" data-bs-toggle="modal" data-bs-target="#add_sss">
                                        <i class="bx bx-plus"></i>
                                        Add SSS Deduction
                                    </a>
                                    @endif

                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="m-auto card employee-card p-4 table-responsive-xl" id="social_security_system" style="width:100%;">

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- Pag Ibig--}}

        <div class="tab-pane fade show" id="pag_ibig" role="tabpanel" aria-labelledby="pag_ibig-tab">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-container">
                        <div class="row">
                            <div class="col-sm-12 col-md-7 d-flex justify-content-start m-auto">
                                <i class='bx bx-book section-icon'></i>
                                <h5 class="section-header">Pag-Ibig Deduction</h5>
                            </div>
                            <div class="col-sm-12 col-md-5 d-flex justify-content-end m-auto">
                                <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                                    @if(Auth::user()->hasRole('accounting'))
                                        <a href="#" type="button" class="btn btn-success btn-sm float-right" data-bs-toggle="modal" data-bs-target="#add_pagibig">
                                            <i class="bx bx-plus"></i>
                                            Add Pag-ibig Deduction
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row table-row" id="pagibig_deduction">

                        </div>
                    </div>
                </div>
            </div>
        </div>


         {{-- Phil health--}}
        <div class="tab-pane fade show" id="phil_health" role="tabpanel" aria-labelledby="phil_health-tab">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-container">
                        <div class="row">
                            <div class="col-sm-12 col-md-7 d-flex justify-content-start m-auto">
                                <i class='bx bx-book section-icon'></i>
                                <h5 class="section-header">Philhealth Deduction</h5>
                            </div>
                            <div class="col-sm-12 col-md-5 d-flex justify-content-end m-auto">
                                <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                                    @if(Auth::user()->hasRole('accounting'))
                                        <a href="#" type="button" class="btn btn-success btn-sm float-right" data-bs-toggle="modal" data-bs-target="#submit_philhealth">
                                            <i class="bx bx-plus"></i>
                                            Add Philhealth Deduction
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row table-row" id="philhealth_deduction">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add deduction for SSS --}}
<div class="modal fade" id="add_sss" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('add_sss_deduction') }}" method="POST" id="add_sss_deduction"  enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex align-items-center">
                        <i class='bx bx-plus section-icon'></i>
                        <h5 class="section-header">Add SSS Deduction</h5>
                    </div>

                    <div class="row form-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center">

                                Range of Compension
                            </span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">From:</label>
                            <input type="number" name="range_from" class="form-control" id="" min="0" step="any">
                            <span class="text-danger error-text range_from_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">To:</label>
                            <input type="number" name="range_to" class="form-control" id="" min="0" step="any">
                            <span class="text-danger error-text range_to_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center ">
                               Montly Salary Credit
                            </span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Regular / EC:</label>
                            <input type="number" name="regular_ec" class="form-control" id="" min="0" step="any">
                            <span class="text-danger error-text regular_ec_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">WISP:</label>
                            <input type="number" name="wisp" class="form-control" id="" min="0" step="any">
                            <span class="text-danger error-text wisp_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center">
                              Regular
                            </span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">ER:</label>
                            <input type="number" name="regular_er" class="form-control" id="" min="0" step="any">
                            <span class="text-danger error-text regular_er_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">EE:</label>
                            <input type="number" name="regular_ee" class="form-control" id="" min="0" step="any">
                            <span class="text-danger error-text regular_ee_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center ">
                              EC
                            </span>
                        </div>
                        <div class="col-xl-12">
                            <label for="">ECC:</label>
                            <input type="number" name="ecc" class="form-control" id="" min="0" step="any">
                            <span class="text-danger error-text ecc_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center">
                              WISP
                            </span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">ER:</label>
                            <input type="number" name="wisp_er" class="form-control" id="" min="0" step="any">
                            <span class="text-danger error-text wisp_er_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">EE:</label>
                            <input type="number" name="wisp_ee" class="form-control" id="" min="0" step="any">
                            <span class="text-danger error-text wisp_ee_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close_sss_modal" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="add_deduction">Submit </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End of SSS add Deducation --}}

{{-- EDIT deduction for SSS --}}
<div class="modal fade" id="sss_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <form action="{{ route('update_sss') }}" method="POST" id="update_sss"  enctype="multipart/form-data">
                    @csrf

                    <div class="d-flex align-items-center">
                        <i class='bx bx-edit section-icon'></i>
                        <h5 class="section-header">Edit SSS Deduction</h5>
                    </div>

                    <input type="hidden" name="sss_id" id="sss_id">
                    <div class="row form-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center">

                                Range of Compension
                            </span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">From:</label>
                            <input type="number" name="range_from" class="form-control" id="range_from" min="0" step="any">
                            <span class="text-danger error-text range_from_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">To:</label>
                            <input type="number" name="range_to" class="form-control" id="range_to" min="0" step="any">
                            <span class="text-danger error-text range_to_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center ">
                               Montly Salary Credit
                            </span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Regular / EC:</label>
                            <input type="number" name="regular_ec" class="form-control" id="regular_ec" min="0" step="any">
                            <span class="text-danger error-text regular_ec_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">WISP:</label>
                            <input type="number" name="wisp" class="form-control" id="wisp" min="0" step="any">
                            <span class="text-danger error-text wisp_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center">
                              Regular
                            </span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">ER:</label>
                            <input type="number" name="regular_er" class="form-control" id="regular_er" min="0" step="any">
                            <span class="text-danger error-text regular_er_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">EE:</label>
                            <input type="number" name="regular_ee" class="form-control" id="regular_ee" min="0" step="any">
                            <span class="text-danger error-text regular_ee_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center ">
                              EC
                            </span>
                        </div>
                        <div class="col-xl-12">
                            <label for="">ECC:</label>
                            <input type="number" name="ecc" class="form-control" id="ecc" min="0" step="any">
                            <span class="text-danger error-text ecc_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="d-flex">
                            <span class="section-subheader d-flex align-items-center">
                              WISP
                            </span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">ER:</label>
                            <input type="number" name="wisp_er" class="form-control" id="wisp_er" min="0" step="any">
                            <span class="text-danger error-text wisp_er_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">EE:</label>
                            <input type="number" name="wisp_ee" class="form-control" id="wisp_ee" min="0" step="any">
                            <span class="text-danger error-text wisp_ee_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close_edit_modal" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="edit_deduction">Submit </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End edit deduction for SSS --}}

{{-- Add deduction for Pag-ibig --}}
<div class="modal fade" id="add_pagibig" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <div class="d-flex align-items-center">
                    <i class='bx bx-plus section-icon'></i>
                    <h5 class="section-header">Add Pagibig Deduction</h5>
                </div>
                <form action="{{route('submit_pagibig')}}" method="POST" id="submit_pagibig"  enctype="multipart/form-data">
                    @csrf
                    <div class="row form-row">
                        <div class="col-xl-6">
                            <label for="">Monthly salary From:</label>
                            <input type="number" max="1000000" name="monthly_salary_from" class="form-control">
                            <span class="text-danger error-text monthly_salary_from_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Monthly salary To:</label>
                            <input type="number" max="1000000" name="monthly_salary_to" class="form-control">
                            <span class="text-danger error-text monthly_salary_to_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="col-xl-6">
                            <label for="">Employee's share:</label>
                            <input type="number"  step="any" name="employees_share" class="form-control">
                            <span class="text-danger error-text employees_share_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Employer's share:</label>
                            <input type="number"     step="any" name="employer_share" class="form-control">
                            <span class="text-danger error-text employer_share_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="close" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="pagibig_btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End deduction for Pag-ibig --}}

{{-- Edit deduction for Pag-ibig --}}
<div class="modal fade" id="pagibig_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <div class="d-flex align-items-center">
                    <i class='bx bx-plus section-icon'></i>
                    <h5 class="section-header">Edit Pagibig Deduction</h5>
                </div>
                <form action="{{route('update_pagibig')}}" method="POST" id="update_pagibig"  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_deducation_philhealth" id="id_deducation_philhealth">
                    <div class="row form-row">
                        <div class="col-xl-6">
                            <label for="">Monthly salary From:</label>
                            <input type="number" max="" name="monthly_salary_from" class="form-control" id="pagibig_monthly_salary_from">
                            <span class="text-danger error-text monthly_salary_from_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Monthly salary To:</label>
                            <input type="number" max="" name="monthly_salary_to" class="form-control" id="pagibig_monthly_salary_to">
                            <span class="text-danger error-text monthly_salary_to_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="col-xl-6">
                            <label for="">Employee's share:</label>
                            <input type="number"   step="any"  name="employees_share" class="form-control" id="pagibig_employees_share">
                            <span class="text-danger error-text employees_share_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Employer's share:</label>
                            <input type="number"  step="any"  name="employer_share"  class="form-control" id="pagibig_employer_share">
                            <span class="text-danger error-text employer_share_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="closemodal" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="pagibig_update_btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End deduction for Pag-ibig --}}

{{-- Add deduction for Philhealth --}}
<div class="modal fade" id="submit_philhealth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <div class="d-flex align-items-center">
                    <i class='bx bx-plus section-icon'></i>
                    <h5 class="section-header">Add Philhealth Deduction</h5>
                </div>
                <form action="{{route('add_philhealth')}}" method="POST" id="add_philhealth"  enctype="multipart/form-data">
                    @csrf
                    <div class="row form-row">
                        <div class="col-xl-6">
                            <label for="">Monthly Basic Salary From:</label>
                            <input type="number"  step="any" name="monthly_basic_salary_from" class="form-control">
                            <span class="text-danger error-text monthly_basic_salary_from_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Monthly Basic Salary To:</label>
                            <input type="number"  name="monthly_basic_salary_to" step="any" class="form-control">
                            <span class="text-danger error-text monthly_basic_salary_to_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="col-xl-6">
                            <label for="">Premium Rate:</label>
                            <input type="number" step="any" name="premium_rate" class="form-control">
                            <span class="text-danger error-text premium_rate_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Monthly Premium:</label>
                            <input type="text" name="monthly_premium" class="form-control" placeholder="ex. 450 to 4,050">
                            <span class="text-danger error-text monthly_premium_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="closePhilhealth" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="philhealth_btn">Submit </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End deduction for Philhealth --}}

{{-- Edit deduction for Philhealth --}}
<div class="modal fade" id="philhealth_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; padding: 10px;">
            <div class="modal-body">
                <div class="d-flex align-items-center">
                    <i class='bx bx-plus section-icon'></i>
                    <h5 class="section-header">Edit Philhealth Deduction</h5>
                </div>
                <form action="{{route('update_philhealth')}}" method="POST" id="update_philhealth"  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_philhealth" id="id_philhealth">
                    <div class="row form-row">
                        <div class="col-xl-6">
                            <label for="">Monthly Basic Salary From:</label>
                            <input type="number" step="any" name="monthly_basic_salary_from" class="form-control" id="philhealth_monthly_basic_salary_from">
                            <span class="text-danger error-text monthly_basic_salary_from_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Monthly Basic Salary Tso:</label>
                            <input type="number" step="any" name="monthly_basic_salary_to" class="form-control" id="philhealth_monthly_basic_salary_to">
                            <span class="text-danger error-text monthly_basic_salary_to_error"></span>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="col-xl-6">
                            <label for="">Premium Rate:</label>
                            <input type="number"   step="any" name="premium_rate" class="form-control" id="philhealth_premium_rate">
                            <span class="text-danger error-text premium_rate_error"></span>
                        </div>
                        <div class="col-xl-6">
                            <label for="">Monthly Premium:</label>
                            <input type="text" name="monthly_premium" class="form-control" id="philhealth_monthly_premium">
                            <span class="text-danger error-text monthly_premium_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer mt-5 gap-2">
                        <button type="button"  class="btn btn-form btn-sm modal-btn" data-bs-dismiss="modal" aria-label="Close" id="closemode" style="background-color:#1e1e1e;">Close</button>
                        <button type="submit" class="btn btn-form  btn-sm modal-btn" id="philhealth_update_btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End deduction for Philhealth --}}

@section('page-scripts')

<script>
    $(document).ready(function () {

        $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
        });

        sss();
            function sss(){
                $.ajax({
                    url: '{{ route('sss_deduction') }}',
                    method: 'GET',
                    success: function(response) {
                        $("#social_security_system").html(response);
                        $("#sss_table").DataTable({
                            "order": [[ 0, "asc" ]]
                        });
                    }
                });
            }
        pagibig();
            function pagibig(){
                $.ajax({
                    url: '{{ route('pagibig_deduction') }}',
                    method: 'GET',
                    success: function(response) {
                        $("#pagibig_deduction").html(response);
                        $("#pagibig_table").DataTable({
                            "order": [[ 0, "asc" ]]
                        });
                    }
                });
            }
        philhealth();
            function philhealth(){
                $.ajax({
                    url: '{{ route('philhealth_deduction') }}',
                    method: 'GET',
                    success: function(response) {
                        $("#philhealth_deduction").html(response);
                        $("#philhealth_table").DataTable({
                            "order": [[ 0, "asc" ]]
                        });
                    }
                });
            }

        // SSS
        $("#add_sss_deduction").on('submit', function (e) {
            e.preventDefault();
            $("#add_deduction").text('Submitting . . . ');
            $('#add_deduction').attr("disabled", true);
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
                        $('#add_deduction').removeAttr("disabled");

                        $.each(response.error, function(prefix, val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });

                        $("#add_deduction").text('Submit');

                    }
                    else{

                        $(form)[0].reset();
                        $('#add_deduction').removeAttr("disabled");
                        $('#add_deduction').text('Submit');
                        sss();
                        $("#add_sss").modal('hide'); //hide the modal

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

                    $('#close_sss_modal').on('click', function () {
                        $(form).find('span.error-text').text('');
                    });

                }
            });
        });

        $(document).on('click', '.sss_edit_icon', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('edit_sss_deduction') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },

                success: function(response){

                    $("#sss_id").val(response.id);
                    $("#range_from").val(response.from);
                    $("#range_to").val(response.to);
                    $("#regular_ec").val(response.regular_ec);
                    $("#wisp").val(response.wisp);
                    $("#regular_er").val(response.regular_ER);
                    $("#regular_ee").val(response.regular_EE);
                    $("#ecc").val(response.ECC);
                    $("#regular_ee").val(response.regular_EE);
                    $("#wisp_er").val(response.wisp_ER);
                    $("#wisp_ee").val(response.wisp_EE);
                }
            });
        });

        $("#update_sss").on('submit', function (e) {
            e.preventDefault();
            $("#edit_deduction").text('Submitting . . . ');
            $('#edit_deduction').attr("disabled", true);
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
                        $('#edit_deduction').removeAttr("disabled");

                        $.each(response.error, function(prefix, val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });

                        $("#edit_deduction").text('Submit');

                    }
                    else{

                        $(form)[0].reset();
                        $('#edit_deduction').removeAttr("disabled");
                        $('#edit_deduction').text('Submit');
                        sss();
                        $("#sss_edit").modal('hide');


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

                    $('#close_edit_modal').on('click', function () {
                        $(form).find('span.error-text').text('');
                    });

                }
            });
        });

        $(document).on('click', '.sss_delete_icon', function(e) {
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
                        url: '{{ route('delete_sss') }}',
                        method: 'delete',
                        data: {
                            id: id,
                            _token: csrf
                        },
                        success: function(response) {
                            //console.log(response);
                            sss();
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

        // Pag-ibig
        $(document).on('click', '.pagibig_edit_icon', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('edit_pagibig') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {

                    $("#id_deducation_philhealth").val(response.id);
                    $("#pagibig_monthly_salary_from").val(response.monthly_salary_from);
                    $("#pagibig_monthly_salary_to").val(response.monthly_salary_to);
                    $("#pagibig_employees_share").val(response.employees_share);
                    $("#pagibig_employer_share").val(response.employer_share);
                }
            });
        });

        $("#submit_pagibig").on('submit',function(e) {

            e.preventDefault();
            $("#pagibig_btn").text('Submitting...');
            $('#pagibig_btn').attr("disabled", true);
            var frm = this;

            $.ajax({

                url:$(frm).attr('action'),
                method:$(frm).attr('method'),
                data: new FormData(frm),
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
                        $('#pagibig_btn').removeAttr("disabled");
                        $.each(response.error, function(prefix, val){
                            $(frm).find('span.'+prefix+'_error').text(val[0]);
                        });
                        $('#pagibig_btn').text('Submit');
                    }
                    else
                    {
                        $(frm)[0].reset();

                        $('#pagibig_btn').removeAttr("disabled");
                        $("#pagibig_btn").text('Submit');
                        $("#add_pagibig").modal('hide');
                        pagibig();
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
                    //To Remove error message once the mocal close and open again
                    $('#close').on('click', function () {
                        $(frm).find('span.error-text').text('')
                    });
                }
            });
        });

        $("#update_pagibig").on('submit',function(e) {

            e.preventDefault();
            $("#pagibig_update_btn").text('Updating...');
            $('#pagibig_update_btn').attr("disabled", true);
            var frm = this;

            $.ajax({

                url:$(frm).attr('action'),
                method:$(frm).attr('method'),
                data: new FormData(frm),
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
                        $('#pagibig_update_btn').removeAttr("disabled");
                        $.each(response.error, function(prefix, val){
                            $(frm).find('span.'+prefix+'_error').text(val[0]);
                        });
                        $('#pagibig_update_btn').text('Updated Deduction');
                    }
                    else
                    {
                        $(frm)[0].reset();
                        $('#pagibig_update_btn').removeAttr("disabled");
                        $("#pagibig_update_btn").text('Updated Deduction');
                        pagibig();
                        $("#pagibig_edit").modal('hide');
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
                    $('#closemodal').on('click', function () {
                        $(frm).find('span.error-text').text('')
                    });
                }
            });
        });

        $(document).on('click', '.pagibig_delete_icon', function(e) {
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
                        url: '{{ route('delete_pagibig') }}',
                        method: 'delete',
                        data: {
                            id: id,
                            _token: csrf
                        },
                        success: function(response) {
                            pagibig();
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

        // Philhealth
        $(document).on('click', '.philhealth_edit_icon', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('edit_philhealth') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {

                    $("#id_philhealth").val(response.id);
                    $("#philhealth_monthly_basic_salary_from").val(response.monthly_basic_salary_from);
                    $("#philhealth_monthly_basic_salary_to").val(response.monthly_basic_salary_to);
                    $("#philhealth_premium_rate").val(response.premium_rate);
                    $("#philhealth_monthly_premium").val(response.monthly_premium);
                }
            });
        });

        $("#add_philhealth").on('submit',function(e) {

            e.preventDefault();
            $("#philhealth_btn").text('Submitting...');
            $('#philhealth_btn').attr("disabled", true);
            var frm = this;

            $.ajax({

                url:$(frm).attr('action'),
                method:$(frm).attr('method'),
                data: new FormData(frm),
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
                        $('#philhealth_btn').removeAttr("disabled");
                        $.each(response.error, function(prefix, val){
                            $(frm).find('span.'+prefix+'_error').text(val[0]);
                        });
                        $('#philhealth_btn').text('Submit');
                    }
                    else
                    {
                        $(frm)[0].reset();
                        $('#philhealth_btn').removeAttr("disabled");
                        $("#philhealth_btn").text('Submit');
                        $("#submit_philhealth").modal('hide');
                        philhealth();
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
                    //To Remove error message once the mocal close and open again
                    $('#closePhilhealth').on('click', function () {
                        $(frm).find('span.error-text').text('')
                    });
                }
            });
        });

        $("#update_philhealth").on('submit',function(e) {

            e.preventDefault();
            $("#philhealth_update_btn").text('Updating...');
            $('#philhealth_update_btn').attr("disabled", true);
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
                        $('#philhealth_update_btn').removeAttr("disabled");
                        $.each(response.error, function(prefix, val){
                            $(frm).find('span.'+prefix+'_error').text(val[0]);
                        });
                        $('#philhealth_update_btn').text('Updated Deduction');
                    }
                    else
                    {
                        $(frm)[0].reset();
                        $('#philhealth_update_btn').removeAttr("disabled");
                        $("#philhealth_update_btn").text('Updated Deduction');
                        philhealth();
                        $("#philhealth_edit").modal('hide');
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
                    $('#closemode').on('click', function () {
                        $(frm).find('span.error-text').text('')
                    });
                }
            });
        });

        $(document).on('click', '.philhealth_delete_icon', function(e) {
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
                        url: '{{ route('delete_philhealth') }}',
                        method: 'delete',
                        data: {
                            id: id,
                            _token: csrf
                        },
                        success: function(response) {
                            philhealth();
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
