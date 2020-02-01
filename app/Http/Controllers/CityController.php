<?php
namespace App\Http\Controllers;

use App\CityModel;
use Illuminate\Http\Request;

class CityController extends Controller{
	/********************************************/
	public function get_cities(Request $request) {
		$data=CityModel::all();
		return response()->json($data,200);
	}
	/********************************************/
	/********************************************/
	/********************************************/
}
