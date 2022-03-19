<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voter;
use App\VoterViews;
use App\User;
use DB;
use App\ElectionViews;
use App\BallotViews;
class VoterController extends Controller
{


    public function transferVotersToOtherElections(Request $request){
        // dd($request->all());
        // "destination_election_id" => 3
        // "origin_election_id" => 2
        $imported_voters = array();
        $voters_origin = Voter::where('election_id', $request->origin_election_id)->get();
        foreach ($voters_origin as $model) {
            # code...
            $voter = new Voter; 
            $voter->election_id = $request->destination_election_id;
            $voter->user_id = $model->user_id;
            $voter->imported = 1;
            $voter->save();
            // $nominee = new Nominee;
            // $nominee->election_id = $request->destination_election_id;
            // $nominee->user_id = $model->user_id;
            // $nominee->imported = 1;
            // $nominee->save();
            array_push($imported_voters, $voter);
        }
        return response()->json([
            'response' => 200,
            'imported_voters' => $imported_voters
        ]);
    }
    public function fetchVotersWhoDidNotVote(Request $request){
        // dd($request->all());
        $election_id = $request->election_id;
        // $ids = Voter::where('election_id', $election_id)->pluck('user_id')->all();
        $ballot_user_ids = BallotViews::where('election_id_voter', $election_id)->pluck('voter_user_id')->all();
        $arr_did_not_vote = VoterViews::where('election_id', $election_id)
         ->whereNotIn('user_id', $ballot_user_ids)
                    ->get();
        return response()->json([
            'voter_did_not_vote_views' => $arr_did_not_vote
        ]);
    }
    public function deleteOneVoterPerElection(Request $request){
        //  "election_id" => 10
        // "user_id" => 74
        $election_id = $request->election_id;
        $user_id     = $request->user_id;
        $rsDeleted = Voter::where('election_id', $election_id)->where('user_id', $user_id)->delete();
        return response()->json([
            'deleted' => $rsDeleted
        ]);
    }
    public function getVoterIdsPerElection(Request $request){
        $election_id = $request->election_id;
        $ids = VoterViews::where('election_id', $election_id)->pluck('user_id')->all();
        return response()->json(['ids'=>$ids]);
    }
    public function importOneUserAsVoter(Request $request){
        // dd($request);
        // "election_id" => 10
        // "user" => array:20 [
        //     "id" => 66
        //     "employ_id" => "188"
        //     "name" => "Jervis Neil Dela Cruz"
        //     "username" => "jnbdelacruz"
        //     "email" => "jervisneildelacruz@gmail.com"
        // ]
        // "param" => "import_as_voter"
        $user_id    = $request->user['id'];
        $election_id = $request->election_id;
        
        // insert now
        $voter = new Voter;
        $voter->user_id = $user_id;
        $voter->election_id = $election_id;
        $voter->imported = 1;
        $saved = $voter->save();
        return response()->json([
            'saved' => $saved,
            'voter' => $voter,
            'voter_view' => VoterViews::where('election_id', $election_id)->where('user_id', $user_id)->first()
        ]);
    }
    public function importAllUsersToElection(Request $request){
        $election_id = $request->election_id;
        DB::table('voters')->where('election_id', $election_id)->delete();
        $users = User::all();
        $inserted = 0;
        foreach ($users as $user) {
            $voter = new Voter;
            $voter->election_id = $election_id;
            $voter->user_id = $user->id;
            $voter->imported = 1;
            $saved = $voter->save();
            if($saved){
                ++$inserted;
            }
        }
        DB::table('elections')->where('id', $election_id)
        ->update(['done_imported_all' => 1]);
        return response()->json([
            'current_election' => ElectionViews::findOrFail($election_id),
            'inserted' => $inserted,
            'user_count' => DB::table('users')->count()
        ]);
    }
    public function searchPerElection(Request $request){
        $search = $request->search;
        $resp = VoterViews::where('election_id', $request->election_id)
                ->where('voters_name', 'like', '%' . $search . '%')
                ->orWhere('name_imported', 'like', '%' . $search . '%')
                ->orWhere('email_imported', 'like', '%' . $search . '%')
                // ->orWhere('voters_id', 'like', '%' . $search . '%')
                // ->orWhere('voters_key', 'like', '%' . $search . '%')
                // ->orWhere('election_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->paginate(12);
                // ->get();
        return response()->json([
            // 'count' => $resp->count(),
            'paginate' => $resp
        ]);
    }
    public function countVotersPerElection(Request $request){
        $election_id = $request->election_id;
        return response()->json([
            'voters_count' => Voter::where('election_id', $election_id)->count()
        ]);
    }
    public function generateRandomKeyAndId(Request $request){
        $length = $request->length;
        $voters_id  = $this->generateRandomStringKey($length, 'voters_id');
        $voters_key = $this->generateRandomStringKey($length, 'voters_key');
        return response()->json([
            'voters_id'  => $voters_id,
            'voters_key' => $voters_key
        ]);
    }
    private function randomize($length){
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    public function generateRandomStringKey($length, $column) {
        $key = "";
        do {
            $key = $this->randomize($length);
            $voter = Voter::where('voters_key', $column)->first();
        } while (!empty($voter));
        return $key;
    }
    public function fetchVotersByElectionId(Request $request){
        $elec_id = $request->elec_id;
        return response()->json([
            // 'voters' => VoterViews::where('election_id', $elec_id)->
            'paginate' => VoterViews::where('election_id', $elec_id)->paginate(12)
        ]);
    }
    public function insert(Request $request){
     $voter = new Voter;
     $voter->voters_name = $request->voters_name;
     $voter->voters_id = $request->voters_id;
     $voter->voters_key = $request->voters_key;
     $voter->email = $request->email;
     $voter->created_by = $request->created_by;
     $voter->election_id = $request->election_id;
     $voter->save();
     return response()->json($voter);
    }
}
