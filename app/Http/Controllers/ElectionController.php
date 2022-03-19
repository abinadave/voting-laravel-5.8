<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Election;
use App\ElectionViews;
use App\VoterViews;
use DB;
use App\BallotViews;
class ElectionController extends Controller
{
    public function updateElection(Request $request){
        // "form" => array:5 [
        //     "election_name" => "2ND Level Nomination DILG"
        //     "start_date" => "2020-07-27 00:00:00"
        //     "end_date" => "2020-07-31 00:00:00"
        //     "start_time" => "08:00:00"
        //     "end_time" => "00:00:00"
        // ]
        // "election_id" => 19
        $election = Election::find($request->election_id);
        $election->election_name = $request->form['election_name'];
        $election->start_date = $request->form['start_date'];
        $election->end_date = $request->form['end_date'];
        $election->start_time = $request->form['start_time'];
        $election->end_time = $request->form['end_time'];
        $updated = $election->save();
        return response()->json([
            'election' => $election,
            'updated' => $updated
        ]);
    }
    public function resetElection(Request $request){
        // "election_id" => 19
        $election_id = $request->election_id;
        $rsTruncateVoters   = DB::table('voters')->where('election_id', $election_id)->delete();
        $rsTruncateNominees = DB::table('nominees')->where('election_id', $election_id)->delete();
        $rsNullSettings = DB::table('elections')
            ->where('id', $election_id)
            ->update([
                'done_imported_all' => null,
                'done_importing_all_nominees' => null,
                'launched' => null
            ]);
        return response()->json([
            'rs_truncate_voters' => $rsTruncateVoters,
            'rs_truncate_nominees' => $rsTruncateNominees,
            'rs_null_set_null_settings' => $rsNullSettings
        ]);
    }
    public function countElectionParticipation(Request $request){
        $election_id = $request->election_id;
        $count = DB::table('ballot_views')->where('election_id', $election_id)->count();
        return response()->json(['election_participation_count' => $count]);
    }
    public function fetchUserRelatedElection(Request $request){
        // dd($request->all());
        // "user_id" => "46"
        $user_id = $request->user_id;
        $voters_view = VoterViews::where('user_id', $user_id)->get();
        $already_voted = array();
        foreach ($voters_view as $voter) {
            $count = DB::table('ballots')->where('voter_primary_id', $voter['id'])->count();
            if($count){
                array_push($already_voted, ['voter_primary_id' => $voter['id']]);
            }
        }
        return response()->json([
            'voters_view' => $voters_view,
            'already_voted' => $already_voted
        ]);
    }
    public function launchElection(Request $request){
        $election_id = $request->election_id;
        $updated = DB::table('elections')->where('id', $election_id)
                    ->update(['launched' => 1]);
        return response()->json(['updated' => $updated]);
    }
    public function generateRandomStringForElection($length){
        $random_string = $this->generateRandomStringKey($length, 'random_string');
        return $random_string;
    }
    private function randomize($length){
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    public function generateRandomStringKey($length, $column) {
        $key = "";
        do {
            $key = $this->randomize($length);
            $election = Election::where('random_string', $column)->first();
        } while (!empty($election));
        return $key;
    }
    public function checkIfIdExist(Request $request){
        $elec_id = $request->elec_id;
        return response()->json([
            'count' => Election::where('id', $elec_id)->count()
        ]);
    }
    public function delete(Request $request){
        $id = $request->id;
        $resp = Election::destroy($id);
        return response()->json([
            'del_resp' => $resp
        ]);
    }
    public function fetchAll(){
        return response()->json(ElectionViews::orderBy('id','desc')->get());
    }
    public function createElection(Request $request){
        $election = Election::create($request->all());
        $election_id = $election->id;
        $randomString = $this->generateRandomStringForElection(5);
        Election::where('id', $election_id)
            ->update(['random_string' => $randomString]);
        return response()->json(Election::where('id', $election_id)->first());
    }
}
