<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:api')->get(
	'/user',function(Request $request) {
	return $request->user();
});
Route::get('/get_users','UserController@get_users')->middleware('auth:api');
Route::get('/get_groups','GroupController@get_groups')->middleware('auth:api');
Route::get('/get_assets','AssetController@get_assets')->middleware('auth:api');
Route::get('/get_venues','VenueController@get_venues')->middleware('auth:api');
Route::get('/get_cities','CityController@get_cities')->middleware('auth:api');