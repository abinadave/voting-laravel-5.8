<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobCategory;
class JobCategoryController extends Controller
{
    public function fetch(){
        
        return response()->json(JobCategory::all());
    }
    public function deleteOne(Request $request){
        $id = $request->id;
        $respDestroyed = JobCategory::destroy($id);
        return response()->json(['destroyed' => $respDestroyed]);
    }
    public function insert(Request $request){
        $newCat = $request->job_cat;
        $job_category = new JobCategory;
        $job_category->cat_name = $newCat;
        $job_category->save();
        return response()->json($job_category);
    }
}
