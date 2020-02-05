<?php
namespace App\Http\Controllers;

use App\OrderDetailModel;
use App\OrderModel;
use App\Providers\HttpResponseProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderDetailController extends Controller{
	public function insert(Request $request) {
		$user=$request->user();
		if($user->can('insert_order_detail',$user)) {
			if(!$request->order_id)
				$order=OrderModel::create(
					[
						'venue_id'=>$request->venue_id,
						'owner_id'=>$user->id,
					]);
			else
				$order=$request->all();
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
			return HttpResponseProvider::return ($data,HttpResponseProvider::Response_Created);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function update(Request $request) {
		$user=$request->user();
		if($user->can('update_order_detail',$user)) {
			$data=$request->all();
			$data['owner_id']=$user->id;
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
		if($user->can('remove_order_detail',$user)) {
			$item=OrderDetailModel::find($request->order_detail_id ?? 0)->delete();
			return HttpResponseProvider::return ($item,HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
}
