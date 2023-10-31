@extends('layouts.main')
@section('main-content')
<div class="page-row row">
    <div class="col-xl-6">
        <h2 class="page-heading">Dashboard</h2>
    </div>
</div>

<div class="container" style="word-wrap: break-word; width:100%; padding: 0; margin:0; min-width: 350px" >

    <div class="row d-flex justify-content-center">
        {{--  all employee  --}}
        <div class="row d-flex justify-content-center">

            <div class="col-xl-6 d-flex justify-content-center p-0">
                {{--  total emp  --}}
                <div class="container p-0 m-1">
                    <div class="card employee-card p-1 m-1" style="background: linear-gradient(230deg, rgba(238,62,62,1) 0%, rgba(39,56,131,1) 100%);">
                        <div class="row p-2 d-flex justify-content-center align-items-center m-1">
                          <div class="col-xl-12 d-flex justify-content-center align-items-center p-0 mb-4">
                            <div id="emp_count" class="col time fs-1 d-flex" style="color:#fff;">0</div>
                            <div class="col d-flex justify-content-end"><i class='bx bx-group bx-lg section-icon' style="color:#fff;"></i></div>
                          </div>
                          <div class="col-xl-12 d-flex justify-content-center align-items-center p-0">
                            <div class="col emp-no fs-6 col d-flex" style="color: #fff">Total Employees</div>
                            <div class="emp-no fs-6 d-flex justify-content-end" style="color: #fff">

                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                {{--  present emp  --}}
                <div class="container p-0 m-1">
                    <div class="card employee-card p-1 m-1" style="background: linear-gradient(230deg, rgba(238,62,62,1) 0%, rgba(39,56,131,1) 100%);">
                        <div class="row p-2 d-flex justify-content-center align-items-center m-1">
                          <div class="col-xl-12 d-flex justify-content-center align-items-center p-0 mb-4">
                            <div id="present_count" class="col time fs-1 d-flex" style="color:#fff;">0</div>
                            <div class="col d-flex justify-content-end"><i class='bx bx-group bx-lg section-icon' style="color:#fff;"></i></div>
                          </div>
                          <div class="col-xl-12 d-flex justify-content-center align-items-center p-0">
                            <div class="col emp-no fs-6 col d-flex" style="color: #fff">Present Employees</div>
                            <div class="emp-no fs-6 d-flex justify-content-end" style="color: #fff">

                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 d-flex justify-content-center p-0">
                {{--  total emp  --}}
                <div class="container p-0 m-1">
                    <div class="card employee-card p-1 m-1" style="background: linear-gradient(230deg, rgba(238,62,62,1) 0%, rgba(39,56,131,1) 100%);">
                        <div class="row p-2 d-flex justify-content-center align-items-center m-1">
                          <div class="col-xl-12 d-flex justify-content-center align-items-center p-0 mb-4">
                            <div id="late_count" class="col time fs-1 d-flex" style="color:#fff;">0</div>
                            <div class="col d-flex justify-content-end"><i class='bx bx-group bx-lg section-icon' style="color:#fff;"></i></div>
                          </div>
                          <div class="col-xl-12 d-flex justify-content-center align-items-center p-0">
                            <div class="col emp-no fs-6 col d-flex" style="color: #fff">Late Employees</div>
                            <div class="emp-no fs-6 d-flex justify-content-end" style="color: #fff">

                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                {{--  present emp  --}}
                <div class="container p-0 m-1">
                    <div class="card employee-card p-1 m-1" style="background: linear-gradient(230deg, rgba(238,62,62,1) 0%, rgba(39,56,131,1) 100%);">
                        <div class="row p-2 d-flex justify-content-center align-items-center m-1">
                          <div class="col-xl-12 d-flex justify-content-center align-items-center p-0 mb-4">
                            <div id="ot_count" class="col time fs-1 d-flex" style="color:#fff;">0</div>
                            <div class="col d-flex justify-content-end"><i class='bx bx-group bx-lg section-icon' style="color:#fff;"></i></div>
                          </div>
                          <div class="col-xl-12 d-flex justify-content-center align-items-center p-0">
                            <div class="col emp-no fs-6 col d-flex" style="color: #fff">Request OT</div>
                            <div class="emp-no fs-6 d-flex justify-content-end" style="color: #fff">

                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>


    <div class="row d-flex justify-content-center">

        <div class="container row d-flex justify-content-between p-0 m-0">
            <div class="col-xl-8">
                <div class="card employee-card m-2" style="border-bottom: 3px solid #bc3d4f;">
                    <div class="col-xl-12 d-flex justify-content-center align-items-center p-0 m-3 mb-0">

                        <div class="d-flex justify-content-start"><i class='bx bxs-group section-icon' style="color:#bc3d4f;"></i></div>
                        <div id="emp_count" class="col time d-flex" style="color:#bc3d4f;">Total Employee by Department</div>

                    </div>
                    <div class="p-3" id="chart4" style="margin-right: 20px auto;"></div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card employee-card d-flex m-2" style="border-bottom: 3px solid #bc3d4f;">
                    <div class="row d-flex justify-content-between p-2">
                        <div class="col d-flex align-items-center">
                            <i class='bx bxs-calendar section-icon' style="color:#bc3d4f; font-size: 30px;"></i>
                            <h5 class="time" style="color:#bc3d4f; font-size: 15px;">Upcoming Birthdays</h5>
                        </div>

                        <div class="col d-flex align-items-center justify-content-end">
                            <i class='bx bxs-user section-icon m-0' style="color:#bc3d4f; font-size: 20px;"></i>
                            <h5 id="bday-count" class="emp-no m-0 fs-6" style="color:#bc3d4f; ">0</h5>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <h5 class="emp-no" style="color:#bc3d4f;">This month of {{ date('F ,Y') }}</h5>
                    </div>
                    <div class="row d-flex justify-content-center p-3" >
                        <div class="row section-container d-flex justify-content-center p-0 align-items-center" style="word-wrap: break-word;  border-radius: 10px; height:316px;">
                            <div class="col p-0" style="word-wrap: break-word;  overflow-y: auto; height:300px;">

                                <div id="employee-1" class="p-1 m-2 align-items-center justify-content-center">
                                    <div class="col d-flex justify-content-center align-items-center">
                                        <h2 class="text-center text-secondary my-5">No record present in the database!</h2>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>




    <div class="row d-flex justify-content-center">

        <div class="container row d-flex justify-content-between p-0 m-0">
            <div class="col-xl-6">
                <div class="col card employee-card d-flex m-2" style="background: linear-gradient(230deg, rgba(238,62,62,1) 0%, rgba(39,56,131,1) 100%);">
                    <div class="row d-flex justify-content-between mt-3">
                        <div class="col d-flex align-items-center">
                            <i class='bx bxs-time section-icon' style="color:#fff; font-size: 30px;"></i>
                            <h5 class="time" style="color:#ffffff; font-size: 15px;">Present Today</h5>
                        </div>
                        <div class="col d-flex align-items-center justify-content-end">
                            <i class='bx bxs-user section-icon' style="color:#fff; font-size: 30px;"></i>
                            <h5 class="emp-no fs-6" style="color:#ffffff; "></h5>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center p-3" >
                        <div class="row section-container d-flex justify-content-center p-0 align-items-center" style="word-wrap: break-word;  border-radius: 10px; height:250px;">
                            <div class="col p-0" style="word-wrap: break-word;  overflow-y: auto; height:225px;">

                                <div id="present_emp" class="row p-1 m-2 align-items-center justify-content-around" style="word-wrap: break-word;">
                                    <div class="col d-flex justify-content-center align-items-center">
                                        <h1 class="text-center text-secondary my-5">No record present in the database!</h1>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{-- <table class="table table-striped" style="color: white;">
                            <thead>
                                <tr>
                                    <th>Profile Pic</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><img src="profile1.jpg" alt="Profile Pic" class="img-thumbnail"></td>
                                    <td>John Doe</td>
                                    <td>Marketing</td>
                                </tr>
                                <tr>
                                    <td><img src="profile2.jpg" alt="Profile Pic" class="img-thumbnail"></td>
                                    <td>Jane Smith</td>
                                    <td>Finance</td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table> --}}
                    </div>
                    <div class="row d-flex justify-content-start pt-1">
                        <h5 class="emp-no" style="color:#fff;">Base on todays attendance ({{ date('l, F j, Y') }})</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6">
                <div class="col card employee-card d-flex m-2" style="background: linear-gradient(230deg, rgba(238,62,62,1) 0%, rgba(39,56,131,1) 100%);">
                    <div class="row d-flex justify-content-between mt-3">
                        <div class="col d-flex align-items-center">
                            <i class='bx bxs-time section-icon' style="color:#fff; font-size: 30px;"></i>
                            <h5 class="time" style="color:#ffffff; font-size: 15px;">Late Today</h5>
                        </div>
                        <div class="col d-flex align-items-center justify-content-end">
                            <i class='bx bxs-user section-icon' style="color:#fff; font-size: 30px;"></i>
                            <h5 class="emp-no fs-6" style="color:#ffffff; "></h5>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center p-3" >
                        <div class="row section-container d-flex justify-content-center p-0 align-items-center" style="word-wrap: break-word;  border-radius: 10px; height:250px;">
                            <div class="col p-0" style="word-wrap: break-word;  overflow-y: auto; height:225px;">

                                <div id="late_emp" class="row p-1 m-2 align-items-center justify-content-evenly" style="word-wrap: break-word;">
                                    <div class="col d-flex justify-content-center align-items-center">
                                        <h1 class="text-center text-secondary my-5">No record present in the database!</h1>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-start pt-1">
                        <h5 class="emp-no" style="color:#fff;">Base on todays attendance ({{ date('l, F j, Y') }})</h5>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

{{--  <div class="page-container row">
    <div class="col-xl-6">
        <div class="section-container">
            <div class="card employee-card" id="piechart">
            </div>
            <div class="card employee-card" id="shift">
            </div>
        </div>
    </div>
</div>  --}}


@section('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.39.0/apexcharts.min.js"></script>
<script>

    setInterval(function() {
        $.ajax({
            url: '/employee/count',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var count = response.count;
                var clock_in = response.clock_in;
                var late2 = response.late2;
                var out = response.out;
                var overtime = response.overtime;
                var now = new Date();

                document.getElementById("emp_count").innerHTML = count;
                document.getElementById("present_count").innerHTML = clock_in - out;
                document.getElementById("late_count").innerHTML = late2;
                // document.getElementById("ot_count").innerHTML = out;
                  document.getElementById("ot_count").innerHTML = overtime;
            }
        });
    }, 1000);

</script>

{{--  GRAPH  --}}
<script>

    var options = {
        series: [],
        chart: {
            type: 'bar',
            height: 350,
            toolbar:{
                show: false,
            },
          },
          grid: {
            show: true,
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
              },
          plotOptions: {
            bar: {
              borderRadius: 5,
              horizontal: false,
              barHeight: '80%',
              barWidth: 30,
            },

          },
          fill: {
              type: 'gradient',
              gradient: {
                shade: 'dark',
                gradientToColors: ['#bc3d4f'],
                shadeIntensity: 1,
                type: 'vertical',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
              },
            },
          colors:['#273883'],
          dataLabels: {
            enabled: true,
            style: {
                colors: ['#fff'], // set the text color to white
            },
          },
        xaxis: {
            categories: [],
            labels: {
                style: {
                  colors: '#bc3d4f',
                },
              },
        },
        yaxis: {
            labels: {
                style: {
                  colors: '#bc3d4f',
                },

            },
        }
    };

      var chart4 = new ApexCharts(document.querySelector("#chart4"), options);
      chart4.render();

      setInterval(function() {
        $.ajax({
            url: '/employee/count',
              type: 'GET',
              dataType: 'json',
            success: function(response) {
                // Update the categories and series data for the chart
                var categories = [];
                var seriesData = [];

                response.departments.forEach(function(count) {
                    categories.push(count.employee_department);
                    seriesData.push(count.count);
                });

                chart4.updateOptions({
                    xaxis: {
                        categories: categories
                    },
                    series: [{
                        name: 'Employee Count',
                        data: seriesData
                    }]
                });
            }
        });
    }, 1000);

</script>

<script>
    var options = {
        series: [{
        name: 'Series 1',
        data: [80, 50, 30, 40, 100, 20],
      }],
        chart: {
        height: 350,
        type: 'radar',
        toolbar:{
            show: false,
        },
      },
      xaxis: {
        categories: ['January', 'February', 'March', 'April', 'May', 'June']
      }
      };

      var chart5 = new ApexCharts(document.querySelector("#chart5"), options);
      chart5.render();

</script>

{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        setInterval(function() {
            $.ajax({
                url: '/employees1',
                  type: 'GET',
                  dataType: 'json',
                  success: function(data) {
                    var html1 = '';

                    $.each(data, function (key, value) {

                        var date = new Date(value.employee_birthday);

                        var options = {
                        timeZone: 'Asia/Manila',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                        };

                        var formattedDate = date.toLocaleDateString('en-PH', options);
                        var image1 = value.image;

                        if(image1 !=  null)
                        {
                            html1 +='<div class="card employee-card">' +
                                '<div class="col d-flex justify-content-center align-items-center p-2">' +
                                    '<div class="col d-flex justify-content-center p-1" >' +

                                        '<img src="storage/employee/images/'+image1+'" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:40px; height: 40px;">' +

                                    '</div>' +

                                    '<div class="col d-flex justify-content-start p-1 align-items-center" >' +

                                            '<p class="time">' + value.employee_name + '</p>' +

                                    '</div>' +

                                    '<div class="col d-flex justify-content-end p-1 align-items-center">' +

                                        '<p class="time" style="color:#bc3d4f;">' + formattedDate + '</p>' +

                                    '</div>' +
                                '</div>'+
                            '</div>';
                        }
                        else
                        {
                            html1 +='<div class="card employee-card">' +
                                '<div class="col d-flex justify-content-center align-items-center p-2">' +
                                    '<div class="col d-flex justify-content-center p-1" >' +

                                        '<i class="bx bx-user align-items-center d-flex justify-content-center text-center" style="background: linear-gradient(230deg, rgba(238,62,62,1) 0%, rgba(39,56,131,1) 100%); color:#fff; border-radius: 100%; padding: 1px; width:40px; height: 40px;"> </i>' +

                                    '</div>' +

                                    '<div class="col d-flex justify-content-start p-1 align-items-center" >' +

                                            '<p class="time">' + value.employee_name + '</p>' +

                                    '</div>' +

                                    '<div class="col d-flex justify-content-end p-1 align-items-center">' +

                                        '<p class="time" style="color:#bc3d4f;">' + formattedDate + '</p>' +

                                    '</div>' +
                                '</div>'+
                            '</div>'
                        }

                    });
                    $('#employee-1').html([html1]);

                }
            });
        }, 1000);
    });

    setInterval(function() {
        $.ajax({
            url: '/employee_bday',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var bday = response.bday;

                document.getElementById("bday-count").innerHTML = bday;
            }
        });
    }, 1000);
</script>

<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //present_employee_table
        setInterval(function() {
            $.ajax({
                url: '/employees3',
                  type: 'GET',
                  dataType: 'json',
                  success: function(clock_in2) {
                    var html2 = '';

                    $.each(clock_in2, function (key, employee) {

                        var image11 = employee.employee.image;

                        if(image11 !=  null)
                        {
                            html2 +='<div class="card employee-card d-flex align-items-center">' +
                                // '<div class="col d-flex justify-content-center align-items-center p-3">' +
                                //     '<div class="col d-flex justify-content-center p-1" >' +

                                //         '<img src="storage/employee/images/'+image11+'" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:40px; height: 40px;">' +

                                //     '</div>' +

                                //     '<div class="col d-flex justify-content-start p-1 align-items-center" >' +

                                //             '<p class="time">' + employee.employee.employee_name + '</p>' +

                                //     '</div>' +

                                //     '<div class="col d-flex justify-content-start p-1 align-items-center">' +

                                //         '<p class="time" style="color:#bc3d4f;">' + employee.employee.employee_department + '</p>' +

                                //     '</div>' +
                                // '</div>'+
                                '<table class="table">'+
                            // '<thead>'+
                            //     '<tr>'+
                            //         '<th>' + 'Profile Pic' + '</th>'+
                            //         '<th>' + 'Name' + '</th>'+
                            //         '<th>' + 'Department' + '</th>'+
                            //     '</tr>'+
                            // '</thead>'+
                            '<tbody>'+
                                '<tr class="d-flex justify-content-around align-items-center ">'+
                                    '<td>'+ '<img src="storage/employee/images/'+ image11 +'" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:40px; height: 40px;">' + '</td>'+
                                    '<td>' + employee.employee.employee_name + '</td>'+
                                    '<td>' + employee.employee.employee_department + '</td>'+
                                '</tr>'+
                            '</tbody>'+
                        '</table>'+
                            '</div>';
                        }
                        else
                        {
                            html2 +='<div class="card employee-card">' +
                                '<div class="col d-flex justify-content-center align-items-center p-3">' +
                                    '<div class="col d-flex justify-content-center p-1" >' +

                                        '<i class="bx bx-user align-items-center d-flex justify-content-center text-center" style="background: linear-gradient(230deg, rgba(238,62,62,1) 0%, rgba(39,56,131,1) 100%); color:#fff; border-radius: 100%; padding: 1px; width:40px; height: 40px;"> </i>' +

                                    '</div>' +

                                    '<div class="col d-flex justify-content-start p-1 align-items-center" >' +

                                            '<p class="time">' + employee.employee.employee_name + '</p>' +

                                    '</div>' +

                                    '<div class="col d-flex justify-content-start p-1 align-items-center">' +

                                        '<p class="time" style="color:#bc3d4f;">' + employee.employee.employee_department + '</p>' +

                                    '</div>' +
                                '</div>'+
                            '</div>';
                        }
                    });

                    $('#present_emp').html([html2]);
                }
            });
        }, 1000);
    });

</script>

<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //late_employee_table
        setInterval(function() {
            $.ajax({
                url: '/employees4',
                  type: 'GET',
                  dataType: 'json',
                  success: function(late_emp) {
                    var html3 = '';

                    $.each(late_emp, function (key, late_emp) {

                        var image11 = late_emp.employee.image;

                        if(image11 !=  null)
                        {
                            html3 +='<div class="card employee-card">' +
                                '<div class="col d-flex justify-content-around align-items-center p-3">' +
                                    '<div class="col d-flex justify-content-start p-1" >' +

                                        '<img src="storage/employee/images/'+image11+'" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:40px; height: 40px;">' +

                                    '</div>' +

                                    '<div class="col d-flex justify-content-start p-1 align-items-center" >' +

                                            '<p class="time">' + late_emp.employee.employee_name + '</p>' +

                                    '</div>' +

                                    '<div class="col d-flex justify-content-end p-1 align-items-center">' +

                                        '<p class="time" style="color:#bc3d4f;">' + late_emp.employee.employee_department + '</p>' +

                                    '</div>' +
                                '</div>'+
                            '</div>';
                        }
                        else
                        {
                            html3 +='<div class="card employee-card">' +
                                '<div class="col d-flex justify-content-center align-items-center p-3">' +
                                    '<div class="col d-flex justify-content-center p-1" >' +

                                        '<i class="bx bx-user align-items-center d-flex justify-content-center text-center" style="background: linear-gradient(230deg, rgba(238,62,62,1) 0%, rgba(39,56,131,1) 100%); color:#fff; border-radius: 100%; padding: 1px; width:40px; height: 40px;"> </i>' +

                                    '</div>' +

                                    '<div class="col d-flex justify-content-start p-1 align-items-center" >' +

                                            '<p class="time">' + late_emp.employee.employee_name + '</p>' +

                                    '</div>' +

                                    '<div class="col d-flex justify-content-start p-1 align-items-center">' +

                                        '<p class="time" style="color:#bc3d4f;">' + late_emp.employee.employee_department + '</p>' +

                                    '</div>' +
                                '</div>'+
                            '</div>';
                        }

                    });
                    $('#late_emp').html([html3]);

                }
            });
        }, 1000);
    });

</script>
@endsection
@endsection
