<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTimetable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_table', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employ_id')->nullable();
            $table->integer('user_id');
            $table->date('table_date');
            $table->time('am_time_in');
            $table->time('am_time_out');
            $table->time('pm_time_in');
            $table->time('pm_time_out');
            $table->time('ot_time_in');
            $table->time('ot_time_out');
            $table->string('remarks')->nullable();
            $table->integer('late')->nullable();
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
        Schema::dropIfExists('table_timetable');
    }
}
