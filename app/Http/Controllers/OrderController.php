<?php
namespace App\Http\Controllers;

use App\OrderDetailModel;
use App\OrderModel;
use App\Providers\HttpResponseProvider;
use App\StatusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller{
	/****************************/
	public function get_orders(Request $request) {
		$user=$request->user();
		if($user->can('get_orders',$user)) {
			$data=$request->all();
			return HttpResponseProvider::return ($data,HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/****************************/
	public static function get_full_order_records($order_ids=false,$show_init=true) {
		$user=Auth::user();
		$venue_criteria=($user->can('see_all_orders',$user)) ? ['venue_id','<>',null] :
			['venue_id','=',$user->venue_id ?? 0];
		$init_criteria=$show_init ? ['rec_type','<>',null] : ['rec_type','<>','init'];
		if($order_ids) {
			$orders=DB::table('order_view')
				->where([$venue_criteria,$init_criteria])
				->whereIn('order_id',$order_ids)
				->orderBy('order_detail_created_at','desc')->get();
		} else {
			$orders=DB::table('order_view')
				->where([$venue_criteria,$init_criteria])
				->orderBy('order_detail_created_at','desc')->get();
		}
		$data=[];
		foreach ($orders as $order) {
			switch ($order->status) {
				case StatusModel::Status_Duty:
					$status_color='danger';
					break;
				case StatusModel::Status_Received:
					$status_color='success';
					break;
				case StatusModel::Status_RecType_Sent:
					$status_color='warning';
					break;
				default :
					$status_color=null;
			};
			$data[]=[
				'order_id'               =>$order->order_id,
				'venue_id'               =>$order->venue_id,
				'owner_id'               =>$order->owner_id,
				'parent_order'           =>$order->parent_order,
				'has_child'              =>DB::table('order_view')->where('parent_order',$order->order_id)->count(),
				'order_detail_id'        =>$order->order_detail_id,
				'asset_id'               =>$order->asset_id,
				'asset_count'            =>$order->asset_count,
				'serial_no'              =>$order->serial_no,
				'status'                 =>StatusModel::get_status_name($order->status),
				'rec_type'               =>$order->rec_type,
				'descp'                  =>$order->descp,
				'user_name'              =>$order->user_name,
				'vname'                  =>$order->vname,
				'asset_name'             =>$order->asset_name,
				'order_detail_created_at'=>$order->order_detail_created_at,
				'parent'                 =>self::get_order_parent($order->order_id),
				'children'               =>self::get_order_children($order->order_id),
				'_cellVariants'          =>['status'=>$status_color]
			];
		}
		return $data;
	}
	/****************************/
	public function get_full_order(Request $request) {
		$user=$request->user();
		if($user->can('get_order_detail',$user)) {
			return HttpResponseProvider::return (
				self::get_full_order_records(false,$request->show_init ?? true),
				HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/****************************/
	public static function get_order_parent($order_id=0) {
		$child_order=OrderModel::find($order_id) ?? false;
		if($child_order && $child_order->parent_order) {
			$parent_id=$child_order->parent_order;
		} else {
			$parent_id=0;
		}
		return DB::table('order_view')->where('order_id',$parent_id)->get();
	}
	/****************************/
	public static function get_order_children($order_id) {
		return DB::table('order_view')->where('parent_order',$order_id)->get();
	}
	/****************************/
	/****************************/
}
