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

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();
Route::view('/','welcome');
//Route::view('/home','home')->middleware('auth')->name('home');
Route::post('/authenticate','Auth\LoginController@authenticate')->name('authenticate');
Route::get('/home','SPAController@index')->middleware('auth')->name('home');
Route::get('/spa/{any}','SPAController@index')->where('any','.*');
//Route::get('/users','SPAController@index')->where('any','.*');
//Route::get('/groups','SPAController@index')->where('any','.*');
//Auth::routes(['register'=>false]);