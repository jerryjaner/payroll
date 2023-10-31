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
        Schema::create('pagibig_deductions', function (Blueprint $table) {
            $table->id();
            $table->decimal('monthly_salary_from', 20, 2)->nullable();
            $table->decimal('monthly_salary_to', 20, 2)->nullable();
            $table->decimal('employees_share', 20, 2)->nullable();
            $table->decimal('employer_share', 20, 2)->nullable();
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
        Schema::dropIfExists('pagibig_deductions');
    }
};
