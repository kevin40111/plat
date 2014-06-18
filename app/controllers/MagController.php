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
		
		$login_return = $this->login();
		if( $login_return ){
			return $login_return;
		}

		$input = Input::only('username', 'password');		
		$rulls = array(
			'username' => 'required|regex:/[0-9a-zA-Z!@_]/|between:3,20',
			'password' => 'required|regex:/[0-9a-zA-Z!@#$%^&*]/|between:3,20' );
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
		
		if( Auth::validate($input) ){ 	
			$input['active'] = 1;
			if( Auth::attempt($input, true) ){
				return Redirect::intended('/');
			}else{
				$validator->getMessageBag()->add('login_error', '帳號尚未開通');
				return Redirect::back()->withErrors($validator);
			}			
		}else{			
			$validator->getMessageBag()->add('login_error', '帳號密碼錯誤');
			return Redirect::back()->withErrors($validator);
		}
		
	}
	
	public function login() {
		$inpu_temp = Input::only('username', 'password');	
		$input = array('email'=>$inpu_temp['username'],'password'=>$inpu_temp['password']);
		//$input = Input::only('email', 'password', 'project');	
		$rulls = array(
			'email' => 'required|email',
			'password' => 'required|regex:/[0-9a-zA-Z!@#$%^&*]/|between:3,20',
			//'project'  => 'required|alpha',
		);
		$rulls_message = array(
			'email.required' => '電子郵件必填',
			'email.email' => '電子郵件格式錯誤',
			'password.required' => '密碼必填',
			'password.regex' => '密碼格式錯誤',						
			//'project.required' => '計畫錯誤',			
		);
		$validator = Validator::make($input, $rulls, $rulls_message);
		
		if( $validator->fails() ){
			return false;
			return Redirect::back()->withErrors($validator)->withInput();
		}
		
		$auth_input = $input;	
		//$auth_input = Input::only('email', 'password','project');	
				
		if( Auth::validate($auth_input) ){ 	
			$auth_input['active'] = 1;
			if( Auth::attempt($auth_input, true) ){
				return Redirect::intended('/');
				return Redirect::route('project');
				return Redirect::intended('project');
			}else{
				$validator->getMessageBag()->add('login_error', '帳號尚未開通');
				return Redirect::back()->withErrors($validator)->withInput();
			}			
		}else{			
			$validator->getMessageBag()->add('login_error', '電子郵件密碼錯誤');
			return Redirect::back()->withErrors($validator)->withInput();
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
			'username'              => 'required|regex:/[0-9a-zA-Z!@_]/|between:3,20',
			'password'              => 'required|regex:/[0-9a-zA-Z!@#$%^&*]/|between:6,20|confirmed',
			'password_confirmation' => 'required|regex:/[0-9a-zA-Z!@#$%^&*]/|between:6,20',
			'agree'                 => 'required|accepted' );
		$validator = Validator::make($input, $rulls);
		
		if( $validator->fails() ){
			return Redirect::to('registerPage')->withErrors($validator)->withInput();
		}
		
		$user = new User;
		$user->password = Hash::make($input['password']);
		$user->username = $input['username'];
		$user->save();
	
		
		return '註冊成功';


		$response = Response::json($response_obj);
		return $response;
	}
	
	public function emailChange() {
		$input = Input::only('email');
		$rulls = array(
			'email' => 'required|email',
		);
		$rulls_message = array(
			'email.required' => '電子郵件必填',
			'email.email' => '電子郵件格式錯誤',		
		);
		$validator = Validator::make($input, $rulls, $rulls_message);

		if( $validator->fails() ){
			return Redirect::back()->withErrors($validator)->withInput();
		}
		
		$user = Auth::User();
		$user->email = $input['email'];
		$user->save();
		return Redirect::back();
	}

	

}