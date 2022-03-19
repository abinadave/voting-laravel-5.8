<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RoleViews;
class RoleController extends Controller
{
    public function checkUserIfHasHrAccessRoles(Request $request){
        $user_id = $request->user_id;
        $countHrRoles = RoleViews::where('role', 'hr_admin')->where('user_id', $user_id)->count();
        return response()->json([
            'count_hr_roles' => $countHrRoles
        ]);
    }
    public function checkMyRoles(Request $request){
        $roleViews = RoleViews::select('role')->where('user_id', $request->user_id)->get();
        return response()->json($roleViews);
    }
}
