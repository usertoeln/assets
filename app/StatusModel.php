<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusModel extends Model{
	protected $table='statuses';
	protected $guarded=['id'];
	/**************************/
	const Status_Request=1;
	const Status_Duty=2;
	const Status_Packaging=3;
	const Status_Sent=4;
	const Status_Reject=5;
	const Status_Received=6;
	/**************************/
	const Rec_Type_Init='init';
	const Rec_Type_Debit='debit';
	const Rec_Type_Credit='credit';
	/**************************/
	const Status_RecType_Request='debit';
	const Status_RecType_Duty='credit';
	const Status_RecType_Packaging='credit';
	const Status_RecType_Sent='credit';
	const Status_RecType_Reject='debit';
	const Status_RecType_Received_Init='init';
	const Status_RecType_Received_Debit='debit';
	/**************************/
	public static function get_status_name($id) {
		switch ($id) {
			case 1:
				return 'request';
				break;
			case 2:
				return 'duty';
				break;
			case 3:
				return 'packaging';
				break;
			case 4:
				return 'sent';
				break;
			case 5:
				return 'reject';
				break;
			case 6:
				return 'received';
				break;
			default:
				return false;
		}
	}
}
