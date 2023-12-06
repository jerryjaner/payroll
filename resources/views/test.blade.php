{{-- @extends('layouts.main')
@section('main-content')
<div class="page-row row">
    <div class="col-xl-6">
        <h2 class="page-heading">User test</h2>
    </div>
    <form>
        <label for="telephone">Telephone Number:</label>
        <input type="tel" id="telephone" name="telephone" value="09" required>
        <input type="submit" value="Submit">
    </form>
</div>


<<<<<<< HEAD
<div class="container employee-card row d-flex justify-content-center p-3">
    <div class="row d-flex">
        <div class="col-md-6 p-1">
            <div class="col d-flex align-items-center">
                <div class="d-flex align-items-center">
                    @if(Auth::user()->employee->image != null)
                    <img src="{{asset('storage/employee/images/'. Auth::user()->employee->image)}}" class="img-fluid mx-auto" style="border-radius: 10px; border: 0.5px solid gray;  padding: 1px; width: 5rem; height: 5rem;">
                    @else
                    {{-- <i class='bx bx-user icon' id="profile-pic" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:130px; height: 130px;"></i> --}}
                    <img src="https://img.freepik.com/premium-vector/user-profile-login-access-authentication-icon_690577-203.jpg" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width: 5rem; height: 5rem;">
                    @endif
                    
                    <div class="row d-flex m-3">
                        <p class="fs-3 name m-0 p-0">{{auth()->user()->name ?? 'n/a'}}</p>
                        <p class="text-muted  m-0 p-0">{{auth()->user()->employee->employee_department ?? 'n/a'}}/{{auth()->user()->employee->employee_position ?? 'n/a'}}</p>
                    </div>
                    
                </div>
            </div>
            
        </div>
        <div class="col-lg-6 bg-warning">s</div>
    </div>
    {{-- <div class="row bg-primary d-flex">
        <div class="col-md-4 bg-info">1</div>
        <div class="col-md-4 bg-info">2</div>
        <div class="col-md-4 bg-info">3</div>
    </div> --}}
</div>


@endsection
=======


@endsection --}}

>>>>>>> 5dd2fdcfda457cd5bc750f1f7379e3fdd6e43160
