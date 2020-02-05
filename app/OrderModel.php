<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model{
	protected $table='orders';
	protected $guarded=['id'];
	/******************************************/
	public function order_detail() {
		return $this->hasMany(OrderDetailModel::class,'order_id');
	}
	/******************************************/
	public function venue() {
		return $this->belongsTo(VenueModel::class,'venue_id');
	}
	/******************************************/
	/******************************************/
	/******************************************/
	/******************************************/
}
