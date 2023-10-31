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
        Schema::create('philhealth_deductions', function (Blueprint $table) {
            $table->id();
            $table->decimal('monthly_basic_salary_from', 20, 2)->nullable();
            $table->decimal('monthly_basic_salary_to', 20, 2)->nullable();
            $table->decimal('premium_rate', 20, 3)->nullable();
            $table->string('monthly_premium')->nullable();
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
        Schema::dropIfExists('philhealth_deductions');
    }
};
