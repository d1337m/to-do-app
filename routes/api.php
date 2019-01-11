<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::post('logout', 'UserController@logout');

Route::group(['middleware' => 'auth:api'], function(){
	//Tasks
	Route::resource('/task', 'TaskController');

	//Categories
	Route::get('/category/{category}/tasks', 'CategoryController@tasks');
	Route::resource('/category', 'CategoryController');
});