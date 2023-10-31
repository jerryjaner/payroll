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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name')->nullable();
            $table->string('employee_number')->nullable();
            $table->string('payment_type')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('pay_date')->nullable();
            $table->decimal('employee_base_salary', 15,2)->nullable();
            $table->decimal('allowance',total:15, places:2)->nullable();
            $table->decimal('rate_per_hour',total:15, places:2)->nullable();
            $table->decimal('late_total', 15,2)->default(0.00);
            $table->decimal('undertime_total', 15,2)->default(0.00);
            $table->decimal('night_diff',total:15, places:2)->nullable();
            $table->decimal('restday', 15,2)->default(0.00);
            $table->decimal('special_holiday', 15,2)->default(0.00);
            $table->decimal('regular_holiday', 15,2)->default(0.00);
            $table->decimal('restday_special_holiday', 15,2)->default(0.00);
            $table->decimal('restday_regular_holiday', 15,2)->default(0.00);

            $table->decimal('overtime', 15,2)->default(0.00);
            $table->decimal('restday_overtime', 15,2)->default(0.00);
            $table->decimal('special_holiday_overtime', 15,2)->default(0.00);
            $table->decimal('regular_holiday_overtime', 15,2)->default(0.00);
            $table->decimal('restday_special_holiday_overtime', 15,2)->default(0.00);
            $table->decimal('restday_regular_holiday_overtime', 15,2)->default(0.00);
            
           
            // $table->decimal('total_workhour', 15,2)->nullable();
            $table->decimal('employee_absent', 15,2)->default(0.00);
            $table->decimal('total_overtime', 15,2)->default(0.00);
            $table->decimal('late_undertime', 15,2)->default(0.00);
            $table->decimal('sss_deduction',total:15, places:2)->nullable();
            $table->decimal('pag_ibig_deduction',total:15, places:2)->nullable();
            $table->decimal('philhealth_deduction',total:15, places:2)->nullable();
            $table->decimal('gov_contribution',total:15, places:2)->nullable();
            $table->decimal('total_deduction',total:10, places:2)->nullable();
            $table->decimal('cash_advance',total:10, places:2)->nullable();
            $table->decimal('gross', 15,2)->nullable();
            $table->decimal('net_pay',total:10, places:2)->nullable();
            $table->timestamps();

            $table->foreign('employee_number')
                  ->references('employee_no')
                  ->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
};
