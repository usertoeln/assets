<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller{
	/********************************************/
	public function get_users(Request $request) {
		$data=DB::table('users_view')
			->select(['user_id','user_name','user_email','venue_en_name'])
			->addSelect(DB::raw('count(group_id) as group_members'))
			->groupBy('user_id')
			->get();
		return response()->json($data,200);
	}
	/********************************************/
	/********************************************/
	/********************************************/
}
