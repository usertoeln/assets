<?php
namespace App\Http\Controllers;

use App\OrderModel;
use App\Providers\HttpResponseProvider;
use App\StatusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
	public function get_full_order(Request $request) {
		$user=$request->user();
		if($user->can('get_order_detail',$user)) {
			$orders=DB::table('order_view')->orderBy('order_detail_created_at','desc')->get();
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
					'_cellVariants'          =>['status'=>$status_color]
				];
			}
			return HttpResponseProvider::return ($data,HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/****************************/
	/****************************/
	/****************************/
	/****************************/
}
