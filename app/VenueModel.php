<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VenueModel extends Model
{
	protected $table   = 'venues';
	protected $guarded = ['id'];
}
