<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\SSS_deduction;
use App\Models\Philhealth_deduction;
use App\Models\Pagibig_deduction;
use App\Models\Holiday;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Faker::create();
        // foreach(range(1,500) as $index){
        //     DB::table('attendances')->insert([
              
        //         'emp_no' => 'EMP_'.$faker->unique()->numberBetween(0,50000),
               
        //         'time_in' => '08:00:00',
        //         'time_out' => '06:00:00',
        //         'work_hours' => '08:00:00',
        //         'date' => '2020-03-16',
     
               
        //     ]);
        // }

        // $faker = Faker::create();
        // foreach (range(1, 500) as $index) {
        //     $randomDate = $faker->dateTimeBetween('2020-01-01', '2023-12-31')->format('Y-m-d');
        //     $randomTimeIn = $faker->time('H:i:s');
        //     $randomTimeOut = $faker->time('H:i:s');
            
        //     // You may calculate random work hours or keep it constant based on your requirements
        //     $randomWorkHours = $faker->time('H:i:s');

        //     DB::table('attendances')->insert([
        //         'emp_no' => 'EMP_001',
        //         'time_in' => $randomTimeIn,
        //         'time_out' => $randomTimeOut,
        //         'work_hours' => $randomWorkHours,
        //         'date' => $randomDate,
        //     ]);
        // }

        
        $user = User::create([
            'name' => 'Anne Condes ',
            'username' => 'TH_AnneCondes',
            'department' => 'IT',
            'position' => 'Human Resources',
            'role' => 'HR',
            'email' => 'user@gmail.com',
            'password' => Hash::make('TH_AnneCondes')
        ]);
                
        $user->attachRole('HR');
        
        Employee::create([
            'employee_name' => 'Anne Condes ',
            'base_salary' => 15000.00,
            'employee_no' => 'EMP_001',
            'daily_rate' => 681.82,
            'employee_position' => 'Human Resources',
            'employee_department' => 'IT',
            'sched_start' => '08:00:00',
            'sched_end' => '17:00:00',
            'breaktime_start' => '12:00:00',
            'breaktime_end' => '13:00:00',
            'employee_birthday' => '1999-12-01',
            'employee_shift' => 'Day',
            'date_hired' => '2020-03-16',
            'qr' => 'EMP_001',
            'user_id' => $user -> id,
        ]);

        SSS_deduction::create([
            'from' => 0,
            'to' => 4249.99,
            'regular_ec' => 4000.00,
            'wisp' => 0,
            'regular_ER' => 380.00,
            'regular_EE' => 180.00,
            'ECC' => 10,
            'wisp_ER' => 0,
            'wisp_EE' => 0,
        ]);

        Philhealth_deduction::create([

            'monthly_basic_salary_from' => 0,
            'monthly_basic_salary_to' => 10000,
            'premium_rate' => 0.045,
            'monthly_premium' => 450,
        ]);

        Pagibig_deduction::create([

            'monthly_salary_from' => 1000,
            'monthly_salary_to' =>  1500,
            'employees_share' => 100,
            'employer_share' => 100,

        ]);


        Holiday::create([

            'holiday_name' => 'New Year',
            'holiday_date' =>  '2024-01-01',
            'holiday_type' => 'Regular',

        ]);


    }
}
