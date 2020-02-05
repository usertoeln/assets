<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetailModel extends Model{
	protected $table='order_detail';
	protected $guarded=['id'];
	/******************************************/
	public function order() {
		return $this->belongsTo(OrderModel::class,'order_id');
	}
	/******************************************/
	/******************************************/
	/******************************************/
	/******************************************/
}
