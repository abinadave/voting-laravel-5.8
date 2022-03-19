<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/* no header already */
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', function(){
    return response()->json(['success' => true]);
});

Route::get('/password_hash/{password}', function($password){
    // return $password;
      return password_hash($password, PASSWORD_DEFAULT);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/token/{id}', 'ApiTokenController@updateTokenWithId');
});


