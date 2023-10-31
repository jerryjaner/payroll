<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Payroll;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\SSS_deduction;
use Illuminate\Support\Facades\DB;
use App\Models\Philhealth_deduction;
use Illuminate\Support\Facades\Auth;

use App\person;

class TestController extends Controller
{

  public function test(){

    $user = Auth::user();
    return view('test');

  }

}