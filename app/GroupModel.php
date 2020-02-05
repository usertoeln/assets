<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupModel extends Model{
	protected $table='groups';
	protected $guarded=['id'];
	/*****************************/
	public function users() {
		return $this->belongsToMany(User::class,'group_user','group_id','user_id');
	}
	/*****************************/
}
