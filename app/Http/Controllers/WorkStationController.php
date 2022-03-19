<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WorkStation;
class WorkStationController extends Controller
{
    public function delete(Request $request){
        $station_id = $request->station_id;
        //  return response()->json(['station_id' => $station_id], 200);
        $resp = WorkStation::destroy($station_id);
        return response()->json(['resp' => $resp]);
    }
    public function fetchAll(){
        return response()->json(WorkStation::orderBy('name','asc')->get());
    }
    public function insert(Request $request){
        $station = new WorkStation;
        $station->name = $request->workstation;
        $station->created_by = auth()->user()->id;
        $station->save();
        return response()->json($station);
    }
}
