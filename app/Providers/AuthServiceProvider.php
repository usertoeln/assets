<?php
namespace App\Providers;

use App\AssetModel;
use App\OrderDetailModel;
use App\OrderModel;
use App\Policies\AssetPolicy;
use App\Policies\OrderDetailPolicy;
use App\Policies\OrderPolicy;
use App\Policies\UserPolicy;
use App\Policies\VenuePolicy;
use App\User;
use App\VenueModel;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies=[
		'App\Model'            =>'App\Policies\ModelPolicy',
		User::class            =>UserPolicy::class,
		OrderModel::class      =>OrderPolicy::class,
		AssetModel::class      =>AssetPolicy::class,
		OrderDetailModel::class=>OrderDetailPolicy::class,
		VenueModel::class      =>VenuePolicy::class,
	];
	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot() {
		$this->registerPolicies();
		Passport::routes();
		Passport::cookie('ifi_asset_at_ck');
		//
	}
}
