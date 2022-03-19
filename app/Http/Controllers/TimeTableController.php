<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TimeTable;
use App\TimeTableViews;
use DB;
class TimeTableController extends Controller
{
    public function corsGetTimeTableByEmployIds(Request $request){
        $start_date = $request->form['from'];
        $end_date   = $request->form['to'];
        $employ_ids = $request->employ_ids;
        $dtrs = array();
        foreach ($employ_ids as $employ_id) {
            $arr = TimeTableViews::where('employ_id', $employ_id)
            ->whereBetween('table_date', [$start_date, $end_date])
            ->get();
            array_push($dtrs, [
                $employ_id => $arr
            ]);
        }
        // "form" => array:x [
        //     "from" => "yyyy-mm-dd"
        //     "to" => "yyyy-mm-dd"
        //     "time_type" => "standard or military"
        // ]
        // "employ_ids" => array:1 [
        //     x => "x1"
        // ]
        return response()->json([
            'dtrs' => $dtrs,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }
    public function fetchByUserId(Request $request){
        $arr = TimeTable::where('user_id', $request->user_id)
                    ->orderBy('id','desc')
                    ->skip(0)
                    ->take(12)
                    ->get();
        return response()->json($arr);
    }
    public function getCurrentTimeTableOfEmployee(Request $request){
        // dd($request->all());
        $date = $this->getDateToday();
        $user_id = $request->user_id;
        $count = TimeTable::where('user_id', $user_id)->where('table_date', $date)->count();
        $tb = null;
        if($count){ 
            $tb = TimeTable::where('user_id', $user_id)->where('table_date', $date)->first();
        }
        return response()->json([
            'count' => $count,
            'time_table' => $tb
        ]);
    }
    private function getDateToday(){
        return date('Y-m-d');
    }
    public function insertOrEdit(Request $request){
        $type = $request->type;
        $user_id = $request->user_id;

        $date = $this->getDateToday();
        $time = date('H:i:s');

        $tb = null;
        $crud = "";

        $count = TimeTable::where('user_id', $user_id)->where('table_date', $date)->count();
        if($count){
            #already exist
            $tb = TimeTable::where('user_id', $user_id)->where('table_date', $date)->first();
            $tb[$type] = $time;
            $tb->save();
            $crud = "update";
        }else {
            #new data
            $tb = new TimeTable;
            $tb->table_date = $date;
            $tb[$type] = $time;
            $tb->user_id = $user_id;
            $tb->save();
            $crud = "create";
        }
        return response()->json([
            'crud' => $crud,
            'time_table' => $tb
        ]);
        // "type" => "am_time_in"
        // "user_id" => "2"
            
    }
    public function checkExistence(){

    }
    public function getCurrentDateAndTime(){
        $now = date('Y-m-d H:i:s');
        // $fakeDateAndTime = "2020-05-08 11:59:30";
        return response()->json(["date_today" => $now]);
    }
}
