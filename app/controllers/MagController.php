<?php
class MagController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
	protected $dataroot = '';
	
	public function __construct(){
		$this->dataroot = app_path().'/views/ques/data/';
		$this->beforeFilter(function($route){
			$this->root = $route->getParameter('root');
			Config::addNamespace('ques', app_path().'/views/ques/data/'.$this->root);
			$this->config = Config::get('ques::setting');
			Config::set('database.default', 'sqlsrv');
			Config::set('database.connections.sqlsrv.database', 'ques_admin');
		});
	}
	
	public function fileManager($intent_key) {
		$fileManager = new app\library\files\v0\FileManager();
		$fileManager->accept($intent_key);
	}
	
	public function test($root) {
		return $root;
	}
				
	public function home() {
		return View::make('management.index');
	}
	
	public function platformHome() {
		return View::make('management.platform.layout.main')
			->nest('child_tab','management.platform.tabs')
			->nest('child_main','management.platform.gg')
			->nest('child_footer','management.footer');
	}
		
	public function platformLoginPage() {
		$dddos_error = Input::old('dddos_error');
		$csrf_error = Input::old('csrf_error');
		
		Session::flush();
		Session::start();
		
		View::share('dddos_error',$dddos_error);
		View::share('csrf_error',$csrf_error);
		$contents = View::make('management.home')
			->nest('child_tab','management.tabs',array('pagename'=>'index'))
			->nest('child_main','management.login')
			->nest('child_footer','management.footer');		
		$response = Response::make($contents, 200);
		$response->header('Cache-Control', 'no-store, no-cache, must-revalidate');
		$response->header('Pragma', 'no-cache');
		$response->header('Last-Modified', gmdate( 'D, d M Y H:i:s' ).' GMT');
		return $response;
	}
	
	public function platformLogout() {
		Auth::logout();
		return Redirect::to('/');
	}	
	
	public function platformLoginAuth() {
		
		$input = Input::only('username', 'password');
		$rulls = array(
			'username' => 'required|regex:/[0-9a-zA-Z!@#$%^&*]/|between:3,16',
			'password' => 'required|regex:/[0-9a-zA-Z!@#$%^&*]/|between:3,16' );
		$rulls_message = array(
			'username.required' => '帳號必填',
			'password.required' => '密碼必填',
			'username.regex' => '帳號格式錯誤',
			'username.regex' => '密碼格式錯誤'
		);
		$validator = Validator::make($input, $rulls, $rulls_message);

		if( $validator->fails() ){
			return Redirect::back()->withErrors($validator);
		}
		
		/*
		$user = new User;
		$user->password = Hash::make($input['password']);
		$user->username = $input['username'];
		$user->save();
		*/
		if( Auth::attempt($input, true) ){ 			
			return Redirect::intended('/');
		}else{
			$validator->getMessageBag()->add('login_error', '帳號密碼錯誤');
			return Redirect::back()->withErrors($validator);
		}
		
	}
	
	public function upload() {
		$upload_handler = new UploadHandler();
		//return View::make('management.index');
	}
	
	public function maintenance() {
		View::share('config', $this->config);
		return View::make('management.home')
			->nest('child_tab','management.tabs')
			->nest('child_main','management.maintenance')
			->nest('child_footer','management.footer');	
	}
	
	public function platformRegisterPage() {
		return View::make('management.register_layout')
			->nest('child_tab','management.tabs')
			->nest('child_main','management.register')
			->nest('child_footer','management.footer');
	}
	
	public function platformRegister() {
		$input = Input::only('username', 'password', 'password_confirmation','agree');
		$rulls = array(
			'username'              => 'required|between:1,50',
			'password'              => 'required|alpha_num|between:6,20|confirmed',
			'password_confirmation' => 'required|alpha_num|between:6,20',
			'agree'                 => 'required|accepted' );
		$validator = Validator::make($input, $rulls);
		
		if( $validator->fails() ){
			return Redirect::to('registerPage')->withErrors($validator)->withInput();
		}
		
		$user = new User;
		$user->password = Hash::make($input['password']);
		$user->username = $input['username'];
		$user->save();
	
		
		return 1;


		$response = Response::json($response_obj);
		return $response;
	}

	

}