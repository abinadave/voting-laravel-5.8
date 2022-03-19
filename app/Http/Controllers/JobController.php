<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\JobViews;
use Log;
class JobController extends Controller
{
    public function searchJob($keyword){
        if($keyword){
            $jobs = JobViews::where('job_title', 'like', '%'. $keyword . '%')
                    ->orWhere('job_level', 'like', '%'. $keyword . '%')
                    ->orWhere('cat_name', 'like', '%'. $keyword . '%')
                    ->orWhere('monthly_salary', 'like', '%'. $keyword . '%')
                    ->orWhere('station_name', 'like', '%'. $keyword . '%')->get();
            return response()->json($jobs);
        }else {
            return $this->paginate();
        }
    }
   
    public function paginate(){
         return JobViews::orderBy('id','desc')->paginate(10);
    }

    public function updateJob(Request $request){
        $job = Job::findOrFail($request->id);
        // Log::info('job to update', array('job' => $job));
        $job->job_category_id = $request->job_category;
        $job->station_id = $request->station;
        $job->job_title = $request->job_title;
        $job->total_vacancies = $request->total_vacancies;
        $job->shift_type = $request->shift_type;
        $job->job_level = $request->job_level;
        $job->monthly_salary = $request->salary;
        $job->require_cover_letter = ($request->require_cover_letter == 'accepted') ? 1 : 0;
        $job->require_email = ($request->require_email == 'accepted') ? 1 : 0;
        $job->hide_monthly_salary = $request->hide_monthly_salary;
        $job->save();
        return response()->json($job);
    }
    public function fetch(){
        return response()->json(Job::orderBy('id','desc')->get());
    }
    public function addJob(Request $request){
        $job = new Job;
        $job->job_category_id = $request->job_category;
        $job->station_id = $request->station;
        $job->job_title = $request->job_title;
        $job->total_vacancies = $request->total_vacancies;
        $job->shift_type = $request->shift_type;
        $job->job_level = $request->job_level;
        $job->monthly_salary = $request->salary;
        $job->require_cover_letter = ($request->require_cover_letter == 'accepted') ? 1 : 0;
        $job->require_email = ($request->require_email == 'accepted') ? 1 : 0;
        $job->hide_monthly_salary = $request->hide_monthly_salary;
        $job->save();
        return response()->json($job);
    }
}
