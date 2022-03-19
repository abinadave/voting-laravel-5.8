<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Applicant;
use Log;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\MailtrapExample;
use App\ApplicantViews;
class ApplicantController extends Controller
{
    public function search(Request $request){
        $keyword = $request->search;
        if($keyword){
            $jobs = ApplicantViews::where('job_title', 'like', '%'. $keyword . '%')
                    ->orWhere('name', 'like', '%'. $keyword . '%')
                    ->orWhere('email', 'like', '%'. $keyword . '%')
                    ->orWhere('phone', 'like', '%'. $keyword . '%')->get();
            return response()->json($jobs);
        }else {
            return $this->paginate();
        }
    }
    public function paginate(){
        return ApplicantViews::orderBy('id','desc')->paginate(10);
    }
    public function fetchApplicantsView(){
        return $this->paginate();
    }
    public function checkDuplicateApplication(Request $request){
        // "email" => "daveabina@gmail.com"
        //   "job_id" => 39
        $count = Applicant::where('job_id', $request->job_id)->where('email', $request->email)->count();
        return response()->json([
            'count' => $count
        ]);
    }
    public function fetchByJobId(Request $request){
        $applicantViews = ApplicantViews::where('job_id', $request->job_id)->orderBy('id','desc')->get();
        return response()->json($applicantViews);
    }
    public function applyNow(Request $request){
        $applicant = Applicant::create($request->all());
        // $emailResposne = $this->sendEmailnotif();
        // Log::info('Email response', array('response' => $emailResposne));
        return response()->json($applicant);
    }
    private function sendEmailnotif(){
        // Mail::to('christiandaveabina@gmail.com')->send(new MailtrapExample()); 
        // return 'A message has been sent to Mailtrap!';
    }
}   
