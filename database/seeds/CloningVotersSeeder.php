<?php

use Illuminate\Database\Seeder;
use App\Voter;
use App\Nominee;
// use Log;
class CloningVotersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ids = Voter::where('election_id', 1)->pluck('id')->all();
        foreach ($ids as $id) {
            $voter = Voter::findOrFail($id);
            $nomimee = new Nominee;
            
            $nominee->user_id = $voter->user_id;
            $nominee->election_id = $voter->election_id;
            $nominee->imported = 1;
            $nominee->save();
        }
    }
}
