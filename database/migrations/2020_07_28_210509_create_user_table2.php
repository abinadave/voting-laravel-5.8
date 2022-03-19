<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_table2', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employ_id')->nullable();
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('api_token')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamps();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('confirmation')->nullable();
            $table->integer('office')->nullable();
            $table->integer('blocking_status')->nullable();
            $table->integer('province_id')->nullable();
            $table->string('province_name')->nullable();
            $table->integer('citymun_id')->nullable();
            $table->string('citymun_name')->nullable();
            $table->string('office_name')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_table2');
    }
}
