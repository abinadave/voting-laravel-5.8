<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\User;
use DB;
use Hash;
use Log;
use App\RoleViews;
#for login mail example only
# use Illuminate\Support\Facades\Mail;
# use App\Mail\MailtrapExample;
#for login mail example only
use App\AddedRole;
use App\UserViews;
class UserController extends Controller
{
    public function checkUserIfHasHrAccessRoles(Request $request){
        // dd($request->all());
        $user_id = $request->user_id;
        // $count = AddedRole::where('')
    }
    public function removeUser(Request $request){
        $user_id = $request->user_id;
        $deleted = DB::table('users')->where('id', $user_id)->delete();
        return response()->json([
            'deleted' => $deleted
        ]);
    }
    public function searchPaginate(Request $request){
        $search = $request->search;
        $paginate = UserViews::where('name', 'like', '%' . $search . '%')->paginate();
        return response()->json([
            'paginate' => $paginate
        ]);
    }
    public function getToken(Request $request){
        return response()->json([
            'request-all' => $request->all(),
            'getting_token' => true
        ], 200);
    }
    public function unBlockUserById($id){
        $rsUpdated = DB::table('users')->where('id', $id)->update(['blocking_status' => 0]);
        return response()->json([
            'rs_updated' => $rsUpdated
        ], 200);
    }
    public function blockUserById($id){
        $rsUpdated = DB::table('users')->where('id', $id)->update(['blocking_status' => 1]);
        return response()->json([
            'rs_updated' => $rsUpdated
        ], 200);
    }
    public function paginateUsers(){
        $paginatedUsers = UserViews::orderBy('id', 'desc')->paginate(15);
        return response()->json([
            'paginate' => $paginatedUsers
        ]);
    }
    public function fetchUsers(){
        $users = User::orderBy('id','desc')->get();
        return response()->json($users);
    }
    public function publicRegistrationUser(Request $request){
        // dd($request->all());
        $newUser = new User;
        $newUser->email = $request->email;
        $newUser->username = $request->username;
        $newUser->name = $request->name;
        $newUser->password =  Hash::make($request->password);
        $newUser->api_token = Str::random(60);
        $newUser->save();
        $this->addRoleAfterCreate($newUser->id);
    }
    private function addRoleAfterCreate($user_id){
        $ar = new AddedRole;
        $ar->user_id = $user_id;
        $ar->role_id = 3;
        $ar->granted_by = $user_id;
        $ar->save();
    }
    public function logoutManual($id){
        $this->revokeToken($id, Str::random(60));
        return response()->json(['revoked' => true], 200);
    }
    public function userToken(Request $request) {
        return $request->user();
    }
    public function checkBlockingStatusOfUser($username){
        $count = User::where('username', $username)->count();
        if($count){
            $user = User::where('username', $username)->first();
            return $user->blocking_status;
        }else {
            return 0;
        }
    }
    public function manualLogin(Request $request){
        // $email = $request->email;
        // $password = $request->password;
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $blocking_status = $this->checkBlockingStatusOfUser($request->username);
            // Authentication passed...
            // return[] redirect()->intended('dashboard');
            // Mail::to('christiandaveabina@gmail.com')->send(new MailtrapExample()); 
            $token = $this->updateTokenWithId(Auth::user()->id);
            $roles = RoleViews::where('user_id', Auth::user()->id)->get();
            return response()->json([
                'token' => $token,
                'user' => Auth::user(),
                'authenticated' => true,
                'roles' => $roles,
                'blocking_status' => $blocking_status
            ], 200);
        }else {
            return response()->json([
                'authenticated'   => false, 
                'blocking_status' => 0
        ], 401);
        }
        
    }
    public function revokeToken($id, $newToken){
        // Log::info('logout token generated', ['token' => $newToken]);
        DB::table('users')->where('id', $id)->update(['api_token' => $newToken]);
    }
    public function updateTokenWithId($id)
    {
        $token = Str::random(60);
        DB::table('users')->where('id', $id)->update(['api_token' => hash('sha256', $token)]);
        return $token; 
    }
}
