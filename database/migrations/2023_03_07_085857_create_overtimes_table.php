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
        Schema::create('overtimes', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('attendance_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('emp_number')->nullable();
            // $table->string('hours_OT')->nullable();
            $table->time('hours_OT')->nullable();
            $table->boolean('RDOT')->nullable()->default(false);
            $table->boolean('SHOT')->nullable()->default(false);
            $table->boolean('RHOT')->nullable()->default(false);
            $table->boolean('RDSHOT')->nullable()->default(false);
            $table->boolean('RDRHOT')->nullable()->default(false);
            $table->date('date')->nullable();
            $table->string('reason')->nullable();
            $table->boolean('isApproved_TL')->nullable()->default(false);
            $table->boolean('isApproved_HR')->nullable()->default(false);
            $table->boolean('isApproved_SVP')->nullable()->default(false);
            $table->boolean('isApproved_VPO')->nullable()->default(false);
            $table->boolean('isApproved_COO')->nullable()->default(false);
            $table->boolean('isDecline_TL')->nullable()->default(false);
            $table->boolean('isDecline_HR')->nullable()->default(false);
            $table->boolean('isDecline_SVP')->nullable()->default(false);
            $table->boolean('isDecline_VPO')->nullable()->default(false);
            $table->boolean('isDecline_COO')->nullable()->default(false);

            $table->foreign('emp_number')
                  ->references('employee_no')
                  ->on('employees');
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
        Schema::dropIfExists('overtimes');
    }
};
