<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobShiftingInOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_shifting_in_outs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_id');

            $table->string('shift_type');

            $table->string('time_in_fixed')->nullable();
            $table->string('time_in_type_fixed', 2)->nullable();

            $table->string('time_in_flexible')->nullable();
            $table->string('time_in_type_flexible', 2)->nullable();
            $table->string('time_out_flexible')->nullable();
            $table->string('time_out_type_flexible', 2)->nullable();

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
        Schema::dropIfExists('job_shifting_in_outs');
    }
}
