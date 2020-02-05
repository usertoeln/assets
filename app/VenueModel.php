<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class VenueModel extends Model{
	protected $table='venues';
	protected $guarded=['id'];
	/******************************************/
	public function order() {
		return $this->hasMany(OrderModel::class,'venue_id');
	}
	/******************************************/
}
