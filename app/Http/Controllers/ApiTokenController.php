<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use DB;
class ApiTokenController extends Controller
{
    /**
     * Update the authenticated user's API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function update(Request $request)
    {
        $token = Str::random(60);

        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return ['token' => $token];
    }

    public function updateTokenWithId($id)
    {
        $token = Str::random(60);
        DB::table('users')->where('id', $id)->update(['api_token' => hash('sha256', $token)]);
        return ['token' => $token]; 
    }
}