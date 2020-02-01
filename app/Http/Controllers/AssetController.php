<?php
namespace App\Http\Controllers;

use App\AssetModel;
use Illuminate\Http\Request;

class AssetController extends Controller{
	/********************************************/
	public function get_assets(Request $request) {
		$data=AssetModel::all();
		return response()->json($data,200);
	}
	/********************************************/
	/********************************************/
	/********************************************/
}
