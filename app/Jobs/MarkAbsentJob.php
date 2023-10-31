<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class MarkAbsentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         $date = Carbon::now()->toDateString();
         $employees = Employee::get('employee_no');

        foreach ($employees as $employee) {
            $attendance = Attendance::where('emp_no', $employee->employee_no)
                ->where('date', $date)
                ->first();

            if (!$attendance) {
                Attendance::create([
                    'emp_no' => $employee->employee_no,
                    'date' => $date,
                    'absent' => true,
                ]);
            }
        }
    }
}
