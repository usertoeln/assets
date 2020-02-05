<?php
namespace App;

use App\Providers\HttpResponseProvider;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;

class AssetModel extends Model{
	protected $table='assets';
	protected $guarded=['id'];
	/*********************/
	/*********************/
}
