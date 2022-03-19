<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GlobalVar;
use Illuminate\Support\Facades\Cache;
class GlobalController extends Controller
{
    public function setCache(Request $request){
        Cache::put($request->key, $request->value);
    }
    public function fetchOneRow(Request $request){
        $row = GlobalVar::where('column', $request->column)->first();

        return response()->json(array(
            'count' => GlobalVar::where('column', $request->column)->count(),
            'row' => $row
        ));
    }
}
