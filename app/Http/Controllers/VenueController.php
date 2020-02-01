<?php
namespace App\Http\Controllers;

use App\VenueModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenueController extends Controller{
	/********************************************/
	public function get_venues(Request $request) {
		$data=DB::table('venue_view')->get();
		return response()->json($data,200);
	}
	/********************************************/
	/********************************************/
	/********************************************/
}
