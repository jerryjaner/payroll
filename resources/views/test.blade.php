@extends('layouts.main')
@section('main-content')
<div class="page-row row">
    <div class="col-xl-6">
        <h2 class="page-heading">User test</h2>
    </div>
</div>


<div class="d-flex flex-md-row flex-column ">
  {{-- leftside profile --}}
    <section class="col-lg-3 p-3 flex-column d-flex justify-content-center" >
      <div class="mw-100 p-4 card employee-card" style="background-color: rgb(255, 255, 255)">
            <div class="flex-row  w-auto  justify-content-start d-flex ">
              @if(Auth::user()->employee->image != null)

                <img src="{{asset('storage/employee/images/'. Auth::user()->employee->image)}}" class="img-fluid mx-auto" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width: 5rem; height: 5rem;">
                @else

                {{-- <i class='bx bx-user icon' id="profile-pic" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:130px; height: 130px;"></i> --}}
                <img src="https://img.freepik.com/premium-vector/user-profile-login-access-authentication-icon_690577-203.jpg" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width: 7rem; height: 7rem;">

                @endif
            </div>
            {{-- <div class=" w-auto bg-info p-3">SAMPLE 1</div> --}}
            <div class="w-100 pt-2 text-start">
              <p class="m-1 name text-start">{{Auth::user()->employee->employee_name}}</p>
              <p class="m-1 emp-no text-start text-danger">{{Auth::user()->employee->employee_position}}</p>
              
            </div>
            <br>
            <p class="m-1 emp-no text-start  d-flex align-items-center gap-1"><i class='bx bxs-phone fs-6'></i>{{Auth::user()->employee->employee_contact_number ?? '-'}}</p>
            <p class="m-1 emp-no text-start  d-flex align-items-center gap-1"><i class='bx bxl-google-plus fs-6'></i>{{Auth::user()->email}}</p>
            <p class="m-1 emp-no text-start  d-flex align-items-center gap-1"><i class='bx bxl-google-plus fs-6'></i>{{Auth::user()->employee->employee_department}}</p>
            
      </div>
        
    </section>

  {{-- rightside profile --}}
    <section class="w-100 p-3 d-flex justify-content-center align-items-start">
      <div class="w-100 employee-card card p-3 justify-content-center d-flex align-items-center">
        <div class="bg-info w-100 flex-row  justify-content-end d-flex  gap-5" style="text-decoration: none;">
          <a href="" style="text-decoration: none;">Profile</a>
          <a href="" style="text-decoration: none;">Payslip</a>
          <a href="" style="text-decoration: none;">Settings</a>
        </div>


        <div class="d-flex flex-column ">

        </div>
      </div>
      
    </section>

    

    {{-- <section class="w-100 p-3 d-flex justify-content-center" style="background-color: #eee;">
        <div class="d-none d-lg-block w-auto bg-info p-3">SAMPLE 1</div>
        <div class="d-none d-lg-block w-auto bg-info p-3">SAMPLE 1</div>
        <div class=" w-100 bg-warning p-3">SAMPLE 2</div>
    </section> --}}
</div>

{{-- <div class="d-print-none">Screen Only (Hide on print only)</div>
<div class="d-none d-print-block">Print Only (Hide on screen only)</div>
<div class="d-none d-lg-block d-print-block">Hide up to large on screen, but always show on print</div> --}}

{{-- <div class="w-100 card d-flex justify-content-center">
    <table class="w-100 table table-bordered">
    <thead>
      <tr>
        <th  class="bg-info w-50 text-center border">Current Sallary Details</th>
        <th  class="bg-info w-50 text-center border">Year to Date</th>
      </tr>
    </thead>
    <tbody class="w-100">
      <tr>
        <td class="bg-warning">
          <div class="p-2 d-flex align-items-center justify-content-between">
            <div>Base Sallary:</div>
            <div>Base Sallary:</div>
          </div>
        </td>
        <td>Otto</td>
      </tr>
      <tr>
        <td>Jacob</td>
        <td>Thornton</td>
      </tr>
    </tbody>
</table>
</div> --}}

@endsection
