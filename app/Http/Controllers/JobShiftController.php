<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobShiftingInOuts;
use Log;
class JobShiftController extends Controller
{
    public function fetchByJobId(Request $request){
        $job_id = $request->job_id;
        $js = JobShiftingInOuts::where('job_id', $job_id)->first();
        return response()->json($js);
    }
    public function insert(Request $request){
    //    Log::info('request', array('payload' => $request->all()));
        $job_id = $request->job_id;
        $form = $request->form;
        JobShiftingInOuts::where('job_id', $job_id)->delete();
        if ($form['shift_type'] == 'flexible') {
            $js = new JobShiftingInOuts;
            $js->job_id = $job_id;
            $js->shift_type = $form['shift_type'];
            $js->time_in_flexible = $form['time_in_flexible'];
            $js->time_in_type_flexible = $form['time_in_type_flexible'];
            $js->time_out_flexible = $form['time_out_flexible'];
            $js->time_out_type_flexible = $form['time_out_type_flexible'];
            $js->save();
        }else {
            $js = new JobShiftingInOuts;
            $js->job_id = $job_id;
            $js->shift_type = $form['shift_type'];
            $js->time_in_fixed = $form['time_in_fixed'];
            $js->time_in_type_fixed = $form['time_in_type_fixed'];
            $js->save();
        }
       
    }
}
