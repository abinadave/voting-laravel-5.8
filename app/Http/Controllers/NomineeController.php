<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nominee;
use App\NomineeViews;
use App\User;
use App\ElectionViews;
use DB;
use Log;
use Illuminate\Support\Facades\Cache;
use App\NomineeReportViews;
class NomineeController extends Controller
{
    public function transferNomineesToOtherElections(Request $request){
        // dd($request->all());
        // "destination_election_id" => 3
        // "origin_election_id" => 2
        $imported_nominees = array();
        $nominees_origin = Nominee::where('election_id', $request->origin_election_id)->get();
        foreach ($nominees_origin as $model) {
            # code...
            $nominee = new Nominee;
            $nominee->election_id = $request->destination_election_id;
            $nominee->user_id = $model->user_id;
            $nominee->imported = 1;
            $nominee->save();
            array_push($imported_nominees, $nominee);
        }
        return response()->json([
            'response' => 200,
            'imported_nominees' => $imported_nominees
        ]);
    }
    public function fetchNomineeReportView(Request $request){
        $arr = NomineeReportViews::where('election_id', $request->election_id)->get();
        return response()->json($arr);
    }
    public function searchNomineesInElection(Request $request){
        // "search" => "jane"
        // "election_id" => "19"
        $search = $request->search;
        $all_nominees = NomineeViews::where('election_id', $request->election_id)
            ->where('name', 'like', '%' . $search . '%')
            // ->orWhere('email', 'like', '%' . $search . '%')
            // ->orWhere('phone', 'like', '%' . $search . '%')
            ->get();
        return response()->json([
            'all_nominees' => $all_nominees
        ]);
    }
    public function fetchNomineesForceAll(Request $request){
        $election_id = $request->election_id;
        $all_nominees = NomineeViews::where('election_id', $election_id)->get();
        return response()->json([
            'all_nominees' => $all_nominees
        ]);
    }
    public function paginate(Request $request){
        $election_id = $request->election_id;

        $paginate = NomineeViews::where('election_id', $election_id)->paginate(10);
        return response()->json([
            'paginate' => $paginate
        ]);
       
        
    }
    public function searchNomineePerElection(Request $request){
        $search = $request->search;
        $paginate = NomineeViews::where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('phone', 'like', '%' . $search . '%')
            ->paginate(12);
        return response()->json([
            'paginate' => $paginate
        ]);
    }
    public function countAllNomineesPerElectionId(Request $request){
        $count = DB::table('nominees')->where('election_id', $request->election_id)->count();
        return response()->json([
            'nominees_count' => $count
        ]);
    }
    public function importAllUsers(Request $request){
        $election_id = $request->election_id;
        DB::table('nominees')->where('election_id', $election_id)->delete();
        $users = User::all();
        $inserted = 0;
        foreach ($users as $user) {
            // $voter = new Voter;
            // $voter->election_id = $election_id;
            // $voter->user_id = $user->id;
            // $voter->imported = 1;
            // $saved = $voter->save();
            
            $nominee = new Nominee;
            $nominee->election_id = $election_id;
            $nominee->user_id = $user->id;
            $nominee->imported = 1;
            $saved = $nominee->save();
            if($saved){
                ++$inserted;
            }
        }
        DB::table('elections')->where('id', $election_id)
        ->update(['done_importing_all_nominees' => 1]);
        return response()->json([
            'current_election' => ElectionViews::findOrFail($election_id),
            'inserted' => $inserted,
            'user_count' => DB::table('users')->count()
        ]);
    }
    public function removeOneImportedUser(Request $request){
        // dd($request->all());
        // "user_id" => 65
        // "election_id" => 10
        $deleted = Nominee::where('election_id', $request->election_id)
                    ->where('user_id', $request->user_id)
                    ->delete();
        return response()->json([
            'deleted' => $deleted,
            'user_id' => $request->user_id,
            'election_id' => $request->election_id
        ]);
    }
    public function fetchAlreadyImportedUserIds(Request $request){
        // dd($request->all());
        // "election_id" => 10
        $election_id = $request->election_id;
        return response()->json([
            'user_ids' => Nominee::where('election_id', $election_id)->pluck('user_id')->all()
        ]);
    }
   public function fetchNomineesByElectionId(Request $request){
        $election_id = $request->election_id;
        $force_all = $request->force_all;
        if($force_all == "no"){
             return response()->json([
                // 'voters' => VoterViews::where('election_id', $elec_id)->
                'paginate' => NomineeViews::where('election_id', $election_id)->paginate(12)
            ]);
        }else {
            $data = NomineeViews::where('election_id', $election_id)->get();
            return response()->json($data);
        }
       
    }
    public function importOneUser(Request $request){
        // "user_id" => 69
        // "election_id" => 10
        $nominee = new Nominee;
        $nominee->election_id = $request->election_id;
        $nominee->user_id = $request->user_id;
        $nominee->imported = 1;
        $nominee->save();
        return response()->json([
            'nominee' => NomineeViews::findOrFail($nominee->id)
        ]);
    }
}
