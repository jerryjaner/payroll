<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\SSS_deduction;
use App\Models\Philhealth_deduction;
use App\Models\Pagibig_deduction;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        //     DB::table('employees')->insert([
        //         'employee_name' => $faker->name, 
        //         'employee_no' => 'EMP_'.$faker->unique()->numberBetween(0,50000),
        //         'qr' => rand(1, 50000),
        //         'sched_start' => '08:00:00',
        //         'sched_end' => '17:00:00',
        //         'employee_birthday' => '1999-12-01',
        //         'date_hired' => '2020-03-16',
        //         'user_id' => rand(1, 50000), 
        //         'daily_rate' => '350.00',
        //         'employee_shift' => 'Day',  
        //     ]);
        // }

      
        $user = User::create([
            'name' => 'Jerry Janer ',
            'username' => 'TH_user',
            'department' => 'IT',
            'position' => 'Human Resources',
            'role' => 'HR',
            'email' => 'user@gmail.com',
            'password' => Hash::make('TH_user')
        ]);
                
        $user->attachRole('HR');
        
        Employee::create([
            'employee_name' => 'Jerry Janer ',
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


    }
}
