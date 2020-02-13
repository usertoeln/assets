<?php
namespace App\Http\Controllers;

use App\Providers\HttpResponseProvider;
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
	public function get_user_access(Request $request) {
		try {
			$user=$request->user();
			if(!$user)
				return HttpResponseProvider::return (['Forbidden request'],HttpResponseProvider::Response_Forbidden);
			/*********************/
			$venue=$user->venue;
			$btn_access=[];
			if($user->can('btn_remove',$venue))
				array_push($btn_access,'remove');
			if($user->can('btn_edit',$venue))
				array_push($btn_access,'edit');
			if($user->can('btn_reject',$venue))
				array_push($btn_access,'reject');
			if($user->can('btn_received',$venue))
				array_push($btn_access,'received');
			if($user->can('btn_sent',$venue))
				array_push($btn_access,'sent');
			if($user->can('btn_pack',$venue))
				array_push($btn_access,'packaging');
			if($user->can('btn_duty',$venue))
				array_push($btn_access,'duty');
			if($user->can('btn_delete',$venue))
				array_push($btn_access,'delete');
			$data=[
				'view_menu_order_management'   =>$user->can('view_menu_order_management',$user),
				'view_menu_warehouse_inventory'=>$user->can('view_menu_warehouse_inventory',$user),
				'view_menu_operations'         =>$user->can('view_menu_operations',$user),
				'view_menu_data'               =>$user->can('view_menu_data',$user),
				'order_access'                 =>$btn_access,
			];
			return HttpResponseProvider::return ($data,HttpResponseProvider::Response_Accepted);
		} catch (\Exception $exception) {
			return HttpResponseProvider::return (
				[$exception->getMessage()],
				HttpResponseProvider::Response_InternalServerError);
		}
	}
	/********************************************/
	/********************************************/
}
