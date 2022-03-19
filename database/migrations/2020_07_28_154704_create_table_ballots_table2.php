<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBallotsTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ballots2', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nominee_primary_id')->nullable();
            $table->integer('voter_primary_id')->nullable();
            $table->integer('voted_none')->nullable();
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
        Schema::dropIfExists('table_ballots_table2');
    }
}
