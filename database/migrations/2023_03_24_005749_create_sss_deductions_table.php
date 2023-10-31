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
        Schema::create('sss_deductions', function (Blueprint $table) {
            $table->id();
            $table->decimal('from', 20, 2)->nullable();
            $table->decimal('to', 20, 2)->nullable();
            $table->decimal('regular_ec', 20, 2)->nullable();
            $table->decimal('wisp', 20, 2)->nullable();
            $table->decimal('regular_ER',20, 2)->nullable();
            $table->decimal('regular_EE', 20, 2)->nullable();
            $table->decimal('ECC', 20, 2)->nullable();
            $table->decimal('wisp_ER', 20, 2)->nullable();
            $table->decimal('wisp_EE', 20, 2)->nullable();
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
        Schema::dropIfExists('sss_deductions');
    }
};
