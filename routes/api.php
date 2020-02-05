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
//******** Assets *********************
Route::post('asset/insert','AssetController@insert')->middleware('auth:api');
Route::post('asset/update','AssetController@update')->middleware('auth:api');
Route::post('asset/remove','AssetController@remove')->middleware('auth:api');
//******** Operations *********************
Route::post('ops/get_venue_assets','AssetController@get_venue_assets')->middleware('auth:api');
Route::post('ops/get_mojodi','AssetController@get_mojodi')->middleware('auth:api');
Route::post('ops/get_asset_turn_over','AssetController@get_asset_turn_over')->middleware('auth:api');
//******** orders *********************
Route::post('get_full_order','OrderController@get_full_order')->middleware('auth:api');
//******** order_detail *********************
Route::post('order_detail/insert','OrderDetailController@insert')->middleware('auth:api');
Route::post('order_detail/update','OrderDetailController@update')->middleware('auth:api');
Route::post('order_detail/remove','OrderDetailController@remove')->middleware('auth:api');

//******** operations *********************
