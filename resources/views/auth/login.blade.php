@extends('layouts.auth')
@section('auth-content')
    <div class="card-wpr d-flex align-items-center justify-content-center">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-6 left-pane">
                        <div class="d-flex align-items-center">
                            <div class="row">
                                <div>
                                    <i class='bx bx-category-alt'></i>
                                </div>
                                <div>
                                    <h2 class="page-heading">GLP Theorem Management System</h2>
                                </div>
                                <div class="feature-list d-flex flex-wrap">
                                    <span class="features d-flex align-items-center">
                                        <i class='bx bx-time-five'></i>
                                        Attendance
                                    </span>
                                    <span class="features d-flex align-items-center">
                                        <i class='bx bx-money-withdraw'></i>
                                        Payroll
                                    </span>
                                    <span class="features d-flex align-items-center">
                                        <i class='bx bx-group'></i>
                                        Employees
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 right-pane">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row login-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 d-flex align-items-center">
                                    <i class='bx bxs-user headingLogin-icon'></i>
                                    <h3 class="form-heading">Login</h3>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="d-flex justify-content-end">
                                        <img src="{{ asset('images/logo.png') }}" class="th-logo" alt="" srcset="">
                                    </div>
                                </div>
                            </div>
                            @if(Session('login_message'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul>
                                        <li style="text-align:center; list-style:none;">{{ Session('login_message')}}</li>
                                    </ul>
                                </div>
                            @endif
                            <div class="form-row">
                                <input type="text" class="form-control form-auth @error('username') is-invalid @enderror" name="username" placeholder="Username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-row">
                                <input type="password" class="form-control form-auth @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="row form-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-form d-flex align-items-center">
                                            <i class='bx bx-log-in btn-icon'></i>
                                            Login
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row note-row">
                                <span class="form-note d-flex">
                                    <i class='bx bx-info-circle note-icon' ></i>
                                    Having trouble Signing In? <a href="" class="auth-links">
                                        Reset password
                                    </a>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection