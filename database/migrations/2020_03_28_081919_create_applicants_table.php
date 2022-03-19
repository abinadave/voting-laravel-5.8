<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_table', function (Blueprint $table) {
            $table->time('am_time_in')->nullable()->change();
            $table->time('am_time_out')->nullable()->change();
            $table->time('pm_time_in')->nullable()->change();
            $table->time('pm_time_out')->nullable()->change();
            $table->time('ot_time_in')->nullable()->change();
            $table->time('ot_time_out')->nullable()->change();
            // $table->string('name', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicants');
    }
}
