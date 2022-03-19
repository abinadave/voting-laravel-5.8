<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WelcomeLevel;
class WelcomeLevelController extends Controller
{
    public function delete(Request $request){
        $id = $request->payload;
        //  return response()->json(['station_id' => $station_id], 200);
        $resp = WelcomeLevel::destroy($id);
        return response()->json(['resp' => $resp]);
    }
    public function fetch(){
        return response()->json(WelcomeLevel::orderBy('level','asc')->get());
    }
    public function insert(Request $request){
        $level = new WelcomeLevel;
        $level->level = $request->welcomelevel;
        $level->save();
        return response()->json($level);
    }
}
