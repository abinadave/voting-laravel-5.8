<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobDescription;
use Log;
class JobDescriptionController extends Controller
{
    public function insert(Request $request){
        $count = JobDescription::where('job_id', $request->job_id)->count();
        // $desc = [];
        if($count){
            $rsUpdate = JobDescription::where('job_id', $request->job_id)
                ->update(['content' => $request->content]);
                Log::info('response JobDescription Update', array('rs_update' => $rsUpdate));
            // return response()->json($desc);
        }else {
            $desc = new JobDescription;
            $desc->job_id = $request->job_id;
            $desc->content = $request->content;
            $desc->save();
            return response()->json($desc);
        }
        // return $this->fetchByJobId($request);        
    }
    public function fetchByJobId(Request $request){
        $row = JobDescription::where('job_id', $request->job_id)->first();
        return response()->json($row);
    }
}
