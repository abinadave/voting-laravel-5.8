<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ballot;
use App\BallotViews;
class BallotController extends Controller
{
    public function fetchVotersWhoVotedNone(Request $request){
        // "election_id" => 19
        $election_id = $request->election_id;
        return response()->json([
            'ballot_views' => BallotViews::where('election_id_voter', $election_id)
                ->where('voted_none', 1)->get()
        ]);
    }
    public function voteNone(Request $request){
        $voter_view = $request->voter_view;
        $ballot = new Ballot;
        $ballot->voter_primary_id = $voter_view['id'];
        $ballot->voted_none = 1;
        $ballot->save();
        return response()->json($ballot);
    }

    public function fetchAllBallotsPerElection(Request $request){
        $election_id = $request->election_id;
        return response()->json([
            'ballot_views' => BallotViews::where('election_id', $election_id)->whereNull('voted_none')->get()
        ]);
    }
    public function submitBallotVote(Request $request){
        // dd($request->all());
        $nominee_view = $request->nominee_view;
        $voter_view = $request->voter_view;
        $ballot = new Ballot;
        $ballot->nominee_primary_id = $nominee_view['id'];
        $ballot->voter_primary_id = $voter_view['id'];
        $ballot->save();
        return response()->json($ballot);
    }
}
