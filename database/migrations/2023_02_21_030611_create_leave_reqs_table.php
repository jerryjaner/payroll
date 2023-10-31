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
        Schema::create('leave_reqs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('name')->nullable();
            $table->string('leave_day')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('leave_type');
            $table->string('department');
            $table->string('reason')->nullable();
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->string('person1')->nullable();
            $table->string('person2')->nullable();
            $table->boolean('is_TL_Approved')->nullable()->default(false);
            $table->boolean('is_HR_Approved')->nullable()->default(false);
            $table->boolean('is_SVP_Approved')->nullable()->default(false);
            $table->boolean('is_VPO_Approved')->nullable()->default(false);
            $table->boolean('is_COO_Approved')->nullable()->default(false);
            $table->boolean('is_TL_Decline')->nullable()->default(false);
            $table->boolean('is_HR_Decline')->nullable()->default(false);
            $table->boolean('is_SVP_Decline')->nullable()->default(false);
            $table->boolean('is_VPO_Decline')->nullable()->default(false);
            $table->boolean('is_COO_Decline')->nullable()->default(false);
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
        Schema::dropIfExists('leave_reqs');
    }
};
