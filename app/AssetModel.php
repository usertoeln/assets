<?php
namespace App;

use App\Providers\HttpResponseProvider;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssetModel extends Model{
	protected $table='assets';
	protected $guarded=['id'];
	/*********************/
	public static function get_mojodi($request) {
		try {
			$venue_ids=($request['venue_id'] === 'all') ? false : $request['venue_id'];
			$asset_ids=($request['asset_id'] === 'all') ? false : $request['asset_id'];
			$data=DB::table('mojodi')->get();
			if($venue_ids)
				$data=$data->filter(
					function($item) use ($venue_ids) {
						return in_array($item->venue_id,$venue_ids);
					});
			if($asset_ids)
				$data=$data->filter(
					function($item) use ($asset_ids) {
						return in_array($item->asset_id,$asset_ids);
					});
			Log::info(
				json_encode(
					[
						'method'=>__METHOD__,
						'user'  =>Auth::user()->name ?? 'NA',
						'descp' =>'on authorized',
						'data'  =>$data,
					]));
			$final_array = [];
			foreach ($data as $item){
				$final_array[]=$item;
			}
			return ['result'=>true,'data'=>$final_array,'msg'=>'ok'];
		} catch (\Exception $exception) {
			Log::error($exception->getTrace());
			Log::error($exception->getLine());
			return ['result'=>false,'data'=>[],'msg'=>$exception->getMessage()];
		}
	}
	/*********************/
}
