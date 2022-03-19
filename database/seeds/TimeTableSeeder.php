<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class TimeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i=0; $i < 100; $i++) { 
            # code...
            DB::table('time_table')->insert([
                'employ_id' => 0,
                'user_id' => 2,
                'table_date' => $faker->date(),
                'am_time_in' => $faker->time(),
                'am_time_out' => $faker->time(),
                'pm_time_in' => $faker->time(),
                'pm_time_out' => $faker->time(),
                'ot_time_in' => $faker->time(),
                'ot_time_out' => $faker->time(),
                'remarks' => $faker->title()
            ]);
        }
    }
}
