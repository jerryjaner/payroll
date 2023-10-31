<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Theorem-MS</title>

        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

        {{-- Google Font --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
            rel="stylesheet">

        {{-- Bootstrap --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        {{-- Icons --}}
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">

        <!-- FullCalendar -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.css" />

        {{-- Datatables --}}
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" type="text/css"
            ref="https://cdn.datatables.net/fixedcolumns/4.0.0/css/fixedColumns.bootstrap5.min.css">
        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">

        {{-- Select2 --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        {{-- TinyMCE --}}
        {{-- <script src="https://cdn.tiny.cloud/1/0gi9ii1xyute0e1jx10sm5n1v3g8fqz86psea8bldtq24ojk/tinymce/6/tinymce.min.js"
            referrerpolicy="origin"></script> --}}

        {{-- Local CSS --}}
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">

        {{-- toaster --}}
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.0.0/html2canvas.min.js"></script>
    </head>
    <body>

        <nav class="sidebar close">
            <header>
                <div class="image-text">
                    <span class="image">
                        <i class='bx bx-category-alt'></i>
                    </span>

                    <div class="text logo-text">
                        <span class="name">GLP Theorem</span>
                        <span class="profession">Management System</span>
                    </div>
                </div>

                <i class='bx bx-chevron-right toggle'></i>
            </header>

            <div class="menu-bar">
                <div class="menu">

                    {{-- <li class="search-box">
                        <i class='bx bx-search icon'></i>
                        <input type="text" placeholder="Search...">
                    </li> --}}

                 
                    
                    <ul class="menu-links">
                    @if(Auth::user()->hasRole(['HR','assistantHR','CEO']))

                        <li class="nav-link {{ request()->segment(1) == 'dashboard' ? 'nav-active' : '' }}"
                            data-tippy-content="Dashboard" data-tippy-arrow="false">
                            <a href="{{ route('dashboard') }}">
                                <i class='bx bx-grid-alt icon'></i>
                                <span class="text nav-text">Dashboard</span>
                            </a>
                        </li>

                    @endif
                       

                        @if(Auth::user()->hasRole(['accounting','HR','assistantHR','attendance','teamleader']))

                        <li class="nav-link {{ request()->segment(1) == 'attendance' ? 'nav-active' : '' }}"
                            data-tippy-content="Attendance" data-tippy-arrow="false">
                            <a href="{{ route('attendance') }}">
                                <i class='bx bx-time-five icon'></i>
                                <span class="text nav-text">Attendance</span>
                            </a>
                        </li>
                        

                        @endif

                        @if(Auth::user()->hasRole(['accounting','HR', 'assistantHR','CEO']))

                        <li class="nav-link {{ request()->segment(1) == 'holiday' ? 'nav-active' : '' }}"
                            data-tippy-content="Holiday" data-tippy-arrow="false">
                            <a href="{{ route('holiday') }}">
                                <i class='bx bx-calendar-check icon'></i>
                                <span class="text nav-text">Holiday</span>  
                            </a>
                        </li>

                        <li class="nav-link {{ request()->segment(1) == 'payroll' ? 'nav-active' : '' }}"
                            data-tippy-content="Payroll" data-tippy-arrow="false">
                            <a href="{{ route('payroll') }}">
                                <i class='bx bx-money-withdraw icon'></i>
                                <span class="text nav-text">Payroll</span>
                            </a>
                        </li>

                        <li class="nav-link {{ request()->segment(1) == 'deduction' ? 'nav-active' : '' }}"
                            data-tippy-content="Deduction" data-tippy-arrow="false">
                            <a href="{{ route('deduction') }}">
                                <i class='bx bx-money icon'></i>
                                <span class="text nav-text">Deduction</span>
                            </a>
                        </li>

                        @endif

                        @if(Auth::user()->hasRole(['CEO','administrator','HR','assistantHR']))

                        <li class="nav-link {{ request()->segment(1) == 'employees' ? 'nav-active' : '' }}"
                            data-tippy-content="Employees" data-tippy-arrow="false">
                            <a href="{{ route('employees') }}">
                                <i class='bx bx-group icon'></i>
                                <span class="text nav-text">Employees</span>
                            </a>
                        </li>

                        @endif


                        <li class="nav-link {{ request()->segment(1) == 'leave-request' ? 'nav-active' : '' }}"
                            data-tippy-content="Request Leave" data-tippy-arrow="false">
                            <a href="{{ route('leaveRequest') }}">
                                <i class='bx bx-file icon'></i>
                                <span class="text nav-text">Request Leave</span>
                            </a>
                        </li>

                        <li class="nav-link {{ request()->segment(1) == 'overtime-request' ? 'nav-active' : '' }}"
                            data-tippy-content="Request Overtime" data-tippy-arrow="false">
                            <a href="{{ route('overtimeRequest') }}">
                                <i class='bx bx-file-blank icon'></i>
                                <span class="text nav-text">Request Overtime</span>
                            </a>
                        </li>

                        @if(Auth::user()->hasRole(['CEO','HR','assistantHR']))
                        <li class="nav-link {{ request()->segment(1) == 'users' ? 'nav-active' : '' }}"
                            data-tippy-content="Users" data-tippy-arrow="false">
                            <a href="{{ route('users') }}">
                                <i class='bx bx-user-circle icon'></i>
                                <span class="text nav-text">Users</span>
                            </a>
                        </li>
                        @endif

                    </ul>
                </div>

                <div class="bottom-content">
                    <div class="sidebar-card">

                        <li class="nav-link {{ request()->segment(1) == 'account' ? 'nav-active' : '' }}" data-tippy-content="User Profile" data-tippy-arrow="false">
                            <a href="{{ route('account') }}">
                                @if(Auth::user()->profile_image != null)

                                <img src="{{asset('storage/user/images/'. Auth::user()->profile_image)}}" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:35px; height: 35px; margin-left: 12px;">
                                @else

                                <i class='bx bx-user icon' id="profile-pic"></i>

                                @endif


                                <span class="text nav-text">
                                    <span class="user-name"><strong>{{ Auth::user()->username }}</strong></span>
                                    <br>
                                    <span class="user-name">

                                        @if(Auth::user()->hasRole('administrator'))

                                            Administrator

                                        @elseif(Auth::user()->hasRole('HR'))

                                            Human Resources

                                        @elseif(Auth::user()->hasRole('accounting'))

                                            Accounting

                                        @elseif(Auth::user()->hasRole('employee'))

                                            Employee

                                        @elseif(Auth::user()->hasRole('attendance'))

                                            Attendance

                                        @elseif(Auth::user()->hasRole('manager'))

                                            Manager

                                        @elseif(Auth::user()->hasRole('COO'))

                                            Chief Operating Officer
                                        
                                        @elseif(Auth::user()->hasRole('VPO'))

                                            Vice President For Operation

                                        @elseif(Auth::user()->hasRole('CEO'))

                                            CEO / President

                                        @elseif(Auth::user()->hasRole('SVPT'))

                                           Senior Vice President For <br> &nbsp &nbsp Technology 

                                        @elseif(Auth::user()->hasRole('legal'))

                                           Legal / Account Manager
                                        
                                        @elseif(Auth::user()->hasRole('assistantHR'))

                                           Assistant HR Manager

                                        @else

                                            Team Leader

                                        @endif
                                    </span>

                                </span>
                            </a>
                        </li>

                        {{-- <li class="more_options t_link"
                            data-tippy-content="More Options" data-tippy-arrow="false">
                            <a href="">
                                <i class='bx bx-cog icon'></i>
                                <span class="text nav-text">More Options</span>
                            </a>
                        </li> --}}

                        <li class="t_link" data-tippy-content="Logout" data-tippy-arrow="false">
                            <a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class='bx bx-log-out icon'></i>
                                <span class="text nav-text">Logout</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </div>  
                </div>
            </div>

        </nav>

        <section class="home">
            <nav>
                <div class="nav">
                    <div class="container">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('images/logo.png') }}" class="th-logo" alt="">
                                {{-- <div class="search-wrapper ms-4">
                                    <span class="search-input"  data-bs-toggle="modal" data-bs-target="#exampleModal">Search by NPI</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" class="feather feather-search" viewBox="0 0 24 24">
                                        <defs></defs>
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="M21 21l-4.35-4.35"></path>
                                    </svg>
                                </div> --}}
                            </div>

                            {{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-custom modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                <i class='bx bx-x nav-icon'></i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="d-flex align-items-center">
                                                    <i class='bx bx-search' style="font-size: 2.5rem; margin-top: 5px;"></i>
                                                    <input type="text" placeholder="Search provider by NPI" id="search-in" class="search-in ms-3" maxlength="10" autofocus>
                                                </div>
                                            </div>
                                            <div id="search_items"></div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="d-flex justify-content-end align-items-center">
                                <div class="d-flex align-items-center">
                                    @if(Auth::user()->profile_image != null)
                                       <img src="{{asset('storage/user/images/'. Auth::user()->profile_image)}}" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:50px; height: 50px; margin-right: 10px;">
                                    @else
                                      <i class='bx bx-user profile-picture-sm'></i>
                                    @endif

                                    <div>
                                        <h6 class="account-fullname">{{ Auth::User()->name }}</h6>

                                        @if(Auth::user()->hasRole('administrator'))

                                            <p class="account-email">Administrator</p>

                                        @elseif(Auth::user()->hasRole('HR'))

                                            <p class="account-email">Human Resources</p>

                                        @elseif(Auth::user()->hasRole('accounting'))

                                            <p class="account-email">Accounting</p>

                                        @elseif(Auth::user()->hasRole('employee'))

                                            <p class="account-email">Employee</p>

                                        @elseif(Auth::user()->hasRole('attendance'))

                                            <p class="account-email">Attendance</p>

                                        @elseif(Auth::user()->hasRole('manager'))

                                            <p class="account-email">Manager</p>

                                        @elseif(Auth::user()->hasRole('COO'))

                                            <p class="account-email">Chief Operating Officer</p>

                                        @elseif(Auth::user()->hasRole('VPO'))

                                            <p class="account-email">Vice President For Operation</p>
                                        
                                        @elseif(Auth::user()->hasRole('CEO'))

                                            <p class="account-email">CEO / President</p>

                                        @elseif(Auth::user()->hasRole('SVPT'))

                                            <p class="account-email">Senior Vice President For Technology</p>
                                           
                                        @elseif(Auth::user()->hasRole('legal'))

                                            <p class="account-email">Legal / Account Manager</p>
                                        
                                        @elseif(Auth::user()->hasRole('assistantHR'))

                                            <p class="account-email">Assistant HR Manager</p>

                                        @else

                                            <p class="account-email">Team Leader</p>

                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <main>
                <div class="container">
                    @yield('main-content')
                </div>
            </main>
        </section>

        <h6 class="copyright">Copyright 2023 <strong>GLP Theorem Ventures Corporation</strong> All rights reserved.</h6>
        {{-- Sweet Alert --}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Vue.js --}}
        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

        {{-- JQuery --}}
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
            crossorigin="anonymous"></script>

        

        {{-- Bootstrap JS --}}
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
            integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
            integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
        </script>

        {{-- Tippy --}}
        <script src="https://unpkg.com/tippy.js@6"></script>

        {{-- Datatables Js --}}
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js">
        </script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js">
        </script>
        <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/fixedcolumns/4.0.0/js/dataTables.fixedColumns.min.js"></script>
        <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
        <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>

            {{-- Button --}}
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

  
        {{-- FullCalendar --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.js"></script>

        {{-- Chart.js --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"
            integrity="sha512-sW/w8s4RWTdFFSduOTGtk4isV1+190E/GghVffMA9XczdJ2MDzSzLEubKAs5h0wzgSJOQTRYyaz73L3d6RtJSg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        {{-- Select2 --}}
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        {{-- Local JS --}}
        <script type="javascript" src="{{ asset('js/main.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>

        <!-- toastr script -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        {{-- DatePicker --}}
        {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
        {{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> this causes problem --}}
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> --}}

        <script>

            @if (Session::has('message'))

            toastr.options.progressBar = true;
            var type = "{{Session::get('alert-type','info')}}"
            switch(type){
              case 'info':
                toastr.info("{{Session::get('message')}}");
                break;
               case 'success':
                toastr.success("{{Session::get('message')}}");
                break;
               case 'warning':
                toastr.warning("{{Session::get('message')}}");
                break;
                case 'error':
                toastr.error("{{Session::get('message')}}");
                break;
            }
            @endif

        </script>
        <script>
            $(document).ready(function(){
                //Sidebar
                const body = document.querySelector('body'),
                    sidebar = body.querySelector('nav'),
                    toggle = body.querySelector(".toggle"),
                    searchBtn = body.querySelector(".search-box"),
                    modeSwitch = body.querySelector(".toggle-switch"),
                    modeText = body.querySelector(".mode-text");


                toggle.addEventListener("click", () => {
                    sidebar.classList.toggle("close");
                });

                searchBtn.addEventListener("click", () => {
                    sidebar.classList.remove("close");
                });

                // DataTable

                $('.time-tbl').DataTable({

                    scrollX: true,
                    pagingType: 'full_numbers',
                    "oLanguage": {
                        "oPaginate": {
                            "sNext": '<i class="bx bx-chevron-right pagination-icon"></i>',
                            "sPrevious": '<i class="bx bx-chevron-left pagination-icon"></i>',
                            "sFirst": '<i class="bx bx-chevrons-left pagination-icon"></i>',
                            "sLast": '<i class="bx bx-chevrons-right pagination-icon"></i>'
                        }
                    }
                });
                $('.employee-tbl').DataTable({

                    scrollX: true,
                    pagingType: 'full_numbers',
                    "oLanguage": {
                        "oPaginate": {
                            "sNext": '<i class="bx bx-chevron-right pagination-icon"></i>',
                            "sPrevious": '<i class="bx bx-chevron-left pagination-icon"></i>',
                            "sFirst": '<i class="bx bx-chevrons-left pagination-icon"></i>',
                            "sLast": '<i class="bx bx-chevrons-right pagination-icon"></i>'
                        }
                    },


                });

                // $('.employee-tbl').DataTable({
                //     scrollX: true,
                //     pagingType: 'full_numbers',
                //     "oLanguage": {
                //         "oPaginate": {
                //             "sNext": '<i class="bx bx-chevron-right pagination-icon"></i>',
                //             "sPrevious": '<i class="bx bx-chevron-left pagination-icon"></i>',
                //             "sFirst": '<i class="bx bx-chevrons-left pagination-icon"></i>',
                //             "sLast": '<i class="bx bx-chevrons-right pagination-icon"></i>'
                //         }
                //     }
                // });

            })
        </script>

        <script>
            tippy('.nav-link', {
                content: 'Global content',
                theme: 'myTheme',
                animation: 'myAnimation',
                placement: 'right',
            });
            tippy('.t_link', {
                content: 'Global content',
                theme: 'myTheme',
                animation: 'myAnimation',
                placement: 'right',
            });

            tippy('.btn-view', {
                content: 'Global content',
                theme: 'myTheme',
                animation: 'myAnimation2',
            });

            tippy('.mode-link', {
                content: 'Global content',
                theme: 'myTheme2',
                animation: 'myAnimation2',
            });
        </script>



        @yield('page-scripts')

    </body>
</html>


