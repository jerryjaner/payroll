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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('emp_no')->nullable();
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->time('work_hours')->nullable();
            $table->time('late_hours')->nullable();
            $table->time('undertime_hours')->nullable();
            $table->string('status')->nullable();
            $table->boolean('absent')->default(false);
            // $table->boolean('restday')->default(false);
            $table->boolean('onleave')->default(false);
            $table->boolean('RD')->nullable()->default(false);
            $table->boolean('RDSH')->nullable()->default(false);
            $table->boolean('RDRH')->nullable()->default(false);
            $table->boolean('SH')->nullable()->default(false);
            $table->boolean('RH')->nullable()->default(false);
            $table->date('date')->nullable();
            $table->datetime('night_shift_date')->nullable();
            $table->time('night_diff_hours')->nullable();
            $table->boolean('RDND')->nullable()->default(false);
            $table->boolean('SHND')->nullable()->default(false);
            $table->boolean('RHND')->nullable()->default(false);
            $table->boolean('RDSHND')->nullable()->default(false);
            $table->boolean('RDRHND')->nullable()->default(false);
            // $table->string('overtime_approval')->nullable();

            $table->foreign('emp_no')
                  ->references('employee_no')
                  ->on('employees') 
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
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
        Schema::dropIfExists('attendances');
    }
};
