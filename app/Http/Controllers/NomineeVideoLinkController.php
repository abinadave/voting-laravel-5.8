<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NomineeVideoLink;
use App\NomineeViews;
use App\Nominee;
use App\NomineeVideoLinkViews;
use App\Election;
class NomineeVideoLinkController extends Controller
{
    public function fetchVideoLinksPerElection(Request $request){
        // "election_id" => "2"
        $nominee_video_link_views = NomineeVideoLinkViews::where('election_id', $request->election_id)->get();
        return response()->json([
            'nominee_video_link_views' => $nominee_video_link_views,
            'election' => Election::findOrFail($request->election_id)
        ]);
    }

    public function insertVideoLink(Request $request){
        $link = $request->link;
        $nominee_id = $request->nominee_id;

        $nominee = Nominee::findOrFail($nominee_id);
        $user_id = $request->user_id;
        $nvl = new NomineeVideoLink;
        $nvl->nominee_id = $nominee_id;
        $nvl->video_link = $link;
        $nvl->added_by = $user_id;
        $nvl->election_id = $nominee->election_id;
        $nvl->save();
        return response()->json([
            'nominee_video_link' => $nvl,
            'nominee_view' => NomineeViews::findOrFail($nominee_id)
        ]);
    }
}
