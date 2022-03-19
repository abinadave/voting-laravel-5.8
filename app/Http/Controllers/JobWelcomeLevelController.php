<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobWelcomeLevel;
use Log;
class JobWelcomeLevelController extends Controller
{
    public function fetchByJobId(Request $request){
        $jwls = JobWelcomeLevel::where('job_id', $request->job_id)->get();
        return response()->json($jwls);
    }
    public function insert(Request $request){
        if($request->truncate){
            $rsDeleted = JobWelcomeLevel::where('job_id', $request->job_id)->delete();
            Log::info('job_welcome_level Deletion Before insert', array('response' => $rsDeleted));
        }
        $welcome_level_ids = $request->welcome_level_ids;
        $job_id = $request->job_id;
        // Log::info('levels', array(
        //     'ids' => $welcome_level_ids,
        //     'job_id' => $job_id
        // ));
        foreach ($welcome_level_ids as $level_id) {
            $jwl = new JobWelcomeLevel;
            $jwl->job_id = $job_id;
            $jwl->welcome_level_id = $level_id;
            $jwl->save();
        } 
        
    }
}
