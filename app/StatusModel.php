<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusModel extends Model
{
	protected $table   = 'statuses';
	protected $guarded = ['id'];

}
