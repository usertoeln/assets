<?php
namespace App\Http\Controllers;

use App\AssetModel;
use App\OrderDetailModel;
use App\OrderModel;
use App\Providers\HttpResponseProvider;
use App\StatusModel;
use App\VenueModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderDetailController extends Controller{
	public function insert(Request $request) {
		//		from init venue_id = xxx  , status = 6 , rec_type = init
		//		from order venue_id = 0  , status = 1 , rec_type = debit
		$user=$request->user();
		if($request->status === 1 && $request->rec_type === 'debit') {
			//		from order venue_id = 0  , status = 1 , rec_type = debit
			$venue=VenueModel::find($user->venue->id ?? 0);
		} else {
			//		from init venue_id = xxx  , status = 6 , rec_type = init
			$venue=VenueModel::find($request->venue_id ?? 0);
		}
		if($user->can('create_in_venue',$venue)) {
			$order=($request->order_id) ? $request->all() : OrderModel::create(
				[
					'venue_id'=>$venue->id,
					'owner_id'=>$user->id,
				]);
			$data_array=[
				'order_id'   =>$order->id,
				'asset_id'   =>$request->asset_id,
				'asset_count'=>$request->asset_count,
				'serial_no'  =>$request->serial_no,
				'status'     =>$request->status,
				'rec_type'   =>$request->rec_type,
				'descp'      =>$request->descp,
				'owner_id'   =>$order->owner_id,
			];
			$data=OrderDetailModel::create($data_array);
			$data=OrderController::get_full_order_records([$order->id],false);
			return HttpResponseProvider::return ($data,HttpResponseProvider::Response_Created);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function update(Request $request) {
		$user=$request->user();
		$venue=VenueModel::find($request->venue_id ?? 0);
		if($user->can('update_asset_in_venue',$venue)) {
			$update_array=[
				'asset_count'=>$request->asset_count ?? 0,
				'serial_no'  =>$request->serial_no ?? 'NA',
				'descp'      =>$request->descp ?? 'NA',
			];
			$result=OrderDetailModel::find($request->order_detail_id ?? 0)->update($update_array);
			return HttpResponseProvider::return ($result,HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function remove(Request $request) {
		$user=$request->user();
		$venue=OrderDetailModel::find($request->order_detail_id)->order->venue ?? 0;
		if($user->can('remove_asset_from_venue',$venue)) {
			$item=OrderDetailModel::find($request->order_detail_id ?? 0)->delete();
			return HttpResponseProvider::return ($item,HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function reject_order(Request $request) {
		$user=$request->user();
		if($user->can('reject_order',$user)) {
			$update_array=['status'=>StatusModel::Status_Reject];
			OrderDetailModel::find($request->order_detail_id ?? 0)->update($update_array);
			$data=OrderController::get_full_order_records([$request->order_id]);
			return HttpResponseProvider::return ($data[0],HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function receive_order(Request $request) {
		$parent_order_id=DB::table('order_view')
			->where('order_detail_id',$request->order_detail_id)
			->get()->first()->parent_order;
		$parent_order=OrderModel::find($parent_order_id);
		$user=$request->user();
		if($user->can('receive_order',$parent_order)) {
			$today=Carbon::now(config('ap.timezone'))->format('Y-m-d H:i:s');
			$update_array=['received'=>$today];
			OrderDetailModel::find($request->order_detail_id ?? 0)->update($update_array);
			$data=OrderController::get_full_order_records([$parent_order->id]);
			return HttpResponseProvider::return ($data[0],HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function save_duty(Request $request) {
		try {
			$user=$request->user();
			if($user->cant('save_duty',$user))
				return HttpResponseProvider::return (['Forbidden request'],HttpResponseProvider::Response_Forbidden);
			/********* check mojodi ************/
			$data=[
				'venue_id'=>$request->venue_id_source ? [$request->venue_id_source] : 'all',
				'asset_id'=>$request->venue_id_source ? [$request->asset_id] : 'all',
			];
			$mojodi=AssetModel::get_mojodi($data);
			if(!$mojodi['result'])
				throw new \Exception($mojodi['msg']);
			$mojodi=$mojodi['data'][0];
			if($mojodi->asset_count < $request->asset_count)
				throw new \Exception('Request more than stock warehouse');
			/********* Create duty order ************/
			$order=OrderModel::create(
				[
					'venue_id'    =>$request->venue_id_source,
					'owner_id'    =>$user->id,
					'parent_order'=>$request->order_id,
				]);
			$data_array=[
				'order_id'   =>$order->id,
				'asset_id'   =>$request->asset_id,
				'asset_count'=>$request->asset_count,
				'serial_no'  =>'xxxx',
				'status'     =>StatusModel::Status_Duty,
				'rec_type'   =>StatusModel::Status_RecType_Duty,
				'descp'      =>'Request from admin',
				'owner_id'   =>$order->owner_id,
			];
			$data=OrderDetailModel::create($data_array);
			$data=OrderController::get_full_order_records([$order->id]);
			/*********************/
			return HttpResponseProvider::return ($data,HttpResponseProvider::Response_Accepted);
		} catch (\Exception $exception) {
			return HttpResponseProvider::return (
				[$exception->getMessage()],
				HttpResponseProvider::Response_InternalServerError);
		}
	}
	/********************************************/
	public function sent_order(Request $request) {
		$user=$request->user();
		$orderModel=OrderModel::find($request->order_id);
		if($user->can('sent_order',$orderModel)) {
			$update_array=['status'=>StatusModel::Status_Sent];
			OrderDetailModel::find($request->order_detail_id ?? 0)->update($update_array);
			$data=OrderController::get_full_order_records([$request->order_id]);
			return HttpResponseProvider::return ($data[0],HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function packaging_order(Request $request) {
		$user=$request->user();
		$orderModel=OrderModel::find($request->order_id);
		if($user->can('packaging_order',$orderModel)) {
			$update_array=['status'=>StatusModel::Status_Packaging];
			OrderDetailModel::find($request->order_detail_id ?? 0)->update($update_array);
			$data=OrderController::get_full_order_records([$request->order_id]);
			return HttpResponseProvider::return ($data[0],HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function delete(Request $request) {
		$user=$request->user();
		if($user->can('delete_order',$user)) {
			$child_orders=OrderModel::where('parent_order',$request->order_id)->delete();
			$item=OrderModel::find($request->order_id ?? 0)->delete();
			return HttpResponseProvider::return ($item,HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function sample_policy(Request $request) {
		$user=$request->user();
		$order=OrderModel::find(5003);
		if($user->can('test_order_policy',$order))
			return response()->json([$order,$user,'can']);
		else
			return response()->json([$order,$user,'cant']);
	}
	/********************************************/
}
