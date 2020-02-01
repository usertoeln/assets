<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class LoginController extends Controller{
	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/
	use AuthenticatesUsers;
	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo=RouteServiceProvider::HOME;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('guest')->except('logout');
	}
	/*********************************/
	public function authenticate(Request $request) {
		$credentials=$request->only('email','password');
		if(Auth::attempt($credentials)) {
			Log::info($this->get_access_token($credentials));
		}
		return redirect()->intended($this->redirectTo);
	}
	/*********************************/
	public function get_access_token($credentials) {
		$http=new Client();
		$response=$http->post(
			'http://assets.irsafam.local/oauth/token',[
			'form_params'=>[
				'grant_type'   =>'password',
				'client_id'    =>'2',
				'client_secret'=>'C68O2dJr6EnNiUBOPI6RB1FFmOKFPI2uzfneJ4ic',
				'username'     =>$credentials['email'],
				'password'     =>$credentials['password'],
				'scope'        =>'*',
			],
		]);
		return json_decode((string)$response->getBody(),true) ?? false;
	}
	/*********************************/
	/*********************************/
}
