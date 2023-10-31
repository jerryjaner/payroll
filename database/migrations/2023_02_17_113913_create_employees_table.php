<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('employee_name')->nullable();
            $table->string('employee_address')->nullable();
            $table->string('image')->nullable();
            $table->float('daily_rate',total:10, places:2);
            $table->decimal('base_salary',10, 2)->nullable();
            $table->string('employee_no')->unique()->nullable();
            $table->string('employee_position')->nullable();
            $table->string('employee_department')->nullable();
            $table->string('monthly_rate')->nullable();
            $table->time('sched_start')->nullable();
            $table->time('sched_end')->nullable();
            $table->time('breaktime_start')->nullable();
            $table->time('breaktime_end')->nullable();
            $table->string('employee_shift')->nullable();
            $table->string('work_days')->nullable();
            $table->date('date_hired')->nullable();
            $table->date('employee_birthday')->nullable();
            $table->string('qr')->nullable();
            $table->string('employee_allowance')->nullable();
            $table->string('philhealth_number')->nullable();
            $table->string('sss_number')->nullable();
            $table->string('pagibig_number')->nullable();
            $table->string('employee_contact_number')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
