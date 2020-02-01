<?php
namespace App\Http\Controllers;

use App\GroupModel;
use Illuminate\Http\Request;

class GroupController extends Controller{
	/********************************************/
	public function get_groups(Request $request) {
		$data=GroupModel::all();
		return response()->json($data,200);
	}
	/********************************************/
	/********************************************/
	/********************************************/
}
