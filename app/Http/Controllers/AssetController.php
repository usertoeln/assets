<?php
namespace App\Http\Controllers;

use App\AssetModel;
use App\Providers\HttpResponseProvider;
use App\StatusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssetController extends Controller{
	/********************************************/
	public function get_assets(Request $request) {
		$user=$request->user();
		if($user->can('select_asset',$user)) {
			$data=AssetModel::orderBy('asset_name')->get();
			return HttpResponseProvider::return ($data,HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function insert(Request $request) {
		$user=$request->user();
		if($user->can('insert_asset',$user)) {
			$data=$request->all();
			$data['owner_id']=$user->id;
			$new_item=AssetModel::create($data);
			return HttpResponseProvider::return ($new_item,HttpResponseProvider::Response_Created);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function update(Request $request) {
		$user=$request->user();
		if($user->can('update_asset',$user)) {
			$data=$request->all();
			$data['owner_id']=$user->id;
			$item=AssetModel::where('id',$request->id ?? 0)->update($request->except('id'));
			return HttpResponseProvider::return ($item,HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function remove(Request $request) {
		$user=$request->user();
		if($user->can('remove_asset',$user)) {
			$item=AssetModel::find($request->id ?? 0)->delete() ?? false;
			return HttpResponseProvider::return ($item,HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function get_venue_assets(Request $request) {
		$user=$request->user();
		if($user->can('select_asset',$user)) {
			$data=DB::table('order_view')->whereIn('venue_id',$request->venue_id)->get();
			//			$data=DB::table('order_view')->get();
			return HttpResponseProvider::return ($data,HttpResponseProvider::Response_Accepted);
		} else {
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function get_mojodi(Request $request) {
		$user=$request->user();
		if($user->can('get_mojodi',$user)) {
			$data=($request->venue_id === 'all') ? DB::table('mojodi')->get()
				: DB::table('mojodi')->whereIn('venue_id',$request->venue_id)->get();
			Log::info(
				json_encode(
					[
						'method'=>__METHOD__,
						'user'  =>Auth::user()->name ?? 'NA',
						'descp' =>'on authorized',
						'data'  =>$data,
					]));
			return HttpResponseProvider::return ($data,HttpResponseProvider::Response_Accepted);
		} else {
			Log::info(
				json_encode(
					[
						'method'=>__METHOD__,
						'user'  =>Auth::user()->name ?? 'NA',
						'descp' =>'on authorized Failed',
						'data'  =>'Forbidden request',
					]));
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
	public function get_asset_turn_over(Request $request) {
		$user=$request->user();
		if($user->can('get_asset_turn_over',$user)) {
			$data=DB::table('asset_turn_over_report')->where(
				[
					['venue_id','=',$request->venue_id ?? 0],
					['asset_id','=',$request->asset_id ?? 0],
//					['status','=',6],
				])->get();
			Log::info(
				json_encode(
					[
						'method'=>__METHOD__,
						'user'  =>Auth::user()->name ?? 'NA',
						'descp' =>'get_asset_turn_over',
						'data'  =>$data->count() ?? -1,
					]));
			return HttpResponseProvider::return ($data,HttpResponseProvider::Response_Accepted);
		} else {
			Log::info(
				json_encode(
					[
						'method'=>__METHOD__,
						'user'  =>Auth::user()->name ?? 'NA',
						'descp' =>'on authorized Failed',
						'data'  =>'Forbidden request',
					]));
			return HttpResponseProvider::return ('Forbidden request',HttpResponseProvider::Response_Forbidden);
		}
	}
	/********************************************/
}
