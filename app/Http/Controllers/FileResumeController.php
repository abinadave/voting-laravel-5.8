<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FileResume;
use Log;
use App\Applicant;
use App\Job;
use Illuminate\Support\Str;
class FileResumeController extends Controller
{
    // function to store file in 'upload' folder
    public function downloadFile(Request $request){
        $filename = $request->filename;
        // echo $filename;
        $pathToFile = public_path("upload/resume/" . $filename);
        $name = "newname.pdf";
        // return response()->download($pathToFile, $name, $headers);
        // return response()->download(, "new-name");
        return response()->download($pathToFile, $name);

    }
    public function fileStore(Request $request)
    {
        // $file = new FileResume;
        // $file->
        $upload_path = public_path('upload/resume');
        $applicant = Applicant::findOrFail($request->applicant_id);
        $job = Job::findOrFail($request->job_id);
        $file_name = $request->file->getClientOriginalName();
        // $generated_new_name = uniqid() . '-job_id['. $request->job_id . ']' . '-applicant_id['. $request->applicant_id . '].' .  $request->file->getClientOriginalExtension();
        $generated_new_name = $slug = Str::slug($applicant->name, '_') . "_" . strtoupper(Str::slug($job->job_title, '_')) . "_ID_" . $applicant->id . "." . $request->file->getClientOriginalExtension();
        $request->file->move($upload_path, $generated_new_name);

        $file = new FileResume;
        $file->job_id = $request->job_id;
        $file->applicant_id = $request->applicant_id;
        $file->filename = $generated_new_name;
        $file->save();
        return response()->json($file);
    }
}
