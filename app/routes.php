<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
use Illuminate\Auth\Guard as AuthGuard,
	Illuminate\Auth\EloquentUserProvider,
	Illuminate\Hashing\BcryptHasher;
class AdminGuard extends AuthGuard
{
	public function getName()
	{
		return 'admin_login_'.md5(get_class($this));
	}

	public function getRecallerName()
	{
		return 'admin_remember_'.md5(get_class($this));
	}
}

Auth::extend('eloquent.normal', function()
{	
    return new AdminGuard(new EloquentUserProvider(new BcryptHasher, 'Normal'), App::make('session.store'));
});

//Route::group(array('domain' => 'plat.{domain}'), function() {

Route::get('test', function() {
	$value = Session::get('keyuni', rand(0,1000));
	Session::put('keyuni', $value);
	return $value;
	return View::make('tr_qtree', array('auth'=>'empty', 'root'=>''));
});

	//平台-------------------------------------------------------------------------------------------------------------------------------
	Route::get('login', array('before' => 'delay', 'uses' => 'MagController@platformLoginPage'));	
	Route::post('loginAuth', array('before' => 'delay|csrf|dddos', 'uses' => 'MagController@platformLoginAuth'));
	
	Route::get('registerPage', array('before' => 'delay|loginRegister', 'uses' => 'MagController@platformRegisterPage'));	
	Route::post('register', array('before' => 'delay|csrf|dddos|loginRegister', 'uses' => 'MagController@platformRegister'));		 

	Route::group(array('before' => 'auth_logined'), function() {
		Route::get('/', function() {
			return View::make('management.ques.root')->nest('child_tab','management.tabs',array('pagename'=>'index'));
		});
		Route::get('platform', 'MagController@platformHome');
		Route::get('platformLogout', 'MagController@platformLogout');
		
		Route::post('upload', 'MagController@upload');
		
		Route::get('{root}/demo', array('before' => 'folder_ques', 'uses' => 'HomeController@demo'))->where('root', '[a-z0-9_]+');
		Route::get('platform/{root}/show', array('before' => 'folder_ques|loginAdmin', 'uses' => 'ViewerController@showData'))->where('root', '[a-z0-9_]+');
		Route::get('platform/{root}/codebook', array('before' => 'folder_ques', 'uses' => 'ViewerController@codebook'))->where('root', '[a-z0-9_]+');
		Route::get('platform/{root}/traffic', array('before' => 'folder_ques', 'uses' => 'ViewerController@traffic'))->where('root', '[a-z0-9_]+');
		Route::get('platform/{root}/report', array('before' => 'folder_ques', 'uses' => 'ViewerController@report'))->where('root', '[a-z0-9_]+');
		Route::get('platform/{root}/report_solve', array('before' => 'folder_ques', 'uses' => 'ViewerController@report_solve'))->where('root', '[a-z0-9_]+');
		
		Route::get('fileManager/{active_uniqid}', 'FileController@fileManager');
		Route::get('fileActiver/{active_uniqid}', 'FileController@fileActiver');

	});
	
	
	
	Route::group(array('before' => 'auth_logined'), function() {
		
		Route::get('user/fileManager', 'FileController@fileManager');
		Route::any('user/fileActiver', 'FileController@fileActiver');

		Route::get('user/auth/logout', 'UserController@platformLogout');
		Route::get('user/{context}', array('before' => '', 'uses' => 'DemoController@home'));
		Route::post('user/{context}', array('before' => '', 'uses' => 'DemoController@home'));
		
		Route::post('user/auth/password/change', array('before' => 'csrf', 'uses' => 'UserController@passwordChange'));
		
		
	});
	
	Route::get('user/auth/password/remind', 'UserController@remindPage');
	Route::post('user/auth/password/remind', 'UserController@remind');
	
	Route::get('user/auth/password/reset/{token}', 'UserController@resetPage');	
	Route::post('user/auth/password/reset/{token}', 'UserController@reset');
	
	Route::get('user/auth/project', 'UserController@project');
	Route::post('user/auth/login', array('before' => 'delay|csrf|dddos', 'uses' => 'UserController@login'));
	Route::get('user/auth/{project}', array('before' => 'delay', 'uses' => 'UserController@loginPage'));	
	
	//平台-------------------------------------------------------------------------------------------------------------------------------
	
		
	//編輯器-------------------------------------------------------------------------------------------------------------------------------
	Route::post('editor/save/analysis/{root}', array('before' => 'login', 'uses' => 'EditorController@saveAnalysis'));



	

	Route::get('{root}/creatTable', array('before' => 'folder_ques|loginAdmin', 'uses' => 'QuesCreaterController@creatTable'))->where('root', '[a-z0-9_]+');
	Route::get('{root}/deleteTable', array('before' => 'folder_ques|loginAdmin', 'uses' => 'QuesCreaterController@deleteTable'))->where('root', '[a-z0-9_]+');
	Route::get('{root}/creatUser', array('before' => 'folder_ques|loginAdmin', 'uses' => 'QuesCreaterController@creatUser'))->where('root', '[a-z0-9_]+');

	Route::get('{root}/updatetime', array('before' => 'folder_ques|loginPublic', 'uses' => 'ViewerController@updatetime'))->where('root', '[a-z0-9_]+');
	//編輯器-------------------------------------------------------------------------------------------------------------------------------
	
//});//domain

	/*
Route::filter('auth_logined_normal', function($route) {
	Config::set('database.default', 'sqlsrv');
	Config::set('database.connections.sqlsrv.database', 'ques_admin');
	Config::set('auth.table', 'users_normal');
	Config::set('auth.driver', 'eloquent.normal');
	Config::set('auth.model', 'Normal');
	if( Auth::guest() )
		return Redirect::to('user/auth/project');
});
	 */

Route::filter('auth_logined', function($route) {
	if( Auth::guest() )
		return Redirect::to('login');
});

Route::filter('maintenance', function($route) {
	$app = app();
    return $app->make('MagController')->callAction($app, $app['router'], 'maintenance', array());
});

Route::filter('loginOwner', function($route) {
	$root = $route->getParameter('root');
	return Redirect::to($root);
});

Route::filter('loginAdmin', function($route) {
	return '無權限存取';
});

Route::filter('loginRegister', function($route) {
	//return '無權限存取';
});

Route::filter('loginPublic', function($route) {
});

Route::filter('folder_ques', function($route) {//找不到根目錄
	$root = $route->getParameter('root');
	$folder = ques_path().'/ques/data/'.$root;
	if( !is_dir($folder) )
		return Response::view('nopage', array(), 404);
});

Route::filter('login', function($route) {
	$root = $route->getParameter('root');
	if ( !Session::has($root.'_login') )
		return Redirect::to($root);
});

Route::filter('delay', function() {
	usleep(500000);
});

Route::filter('dddos', function() {	
	$input = Input::all();	
		
	if (Session::get('dddos') != Input::get('_token2')){
		//throw new Illuminate\Session\TokenMismatchException;	
		$input['dddos_error'] = false;
		return Redirect::back()->withInput($input);
	}
	Session::forget('dddos');
	
	$ip = Request::server('REMOTE_ADDR');
	$ip_time = Cache::get($ip, array('block'=>false,'time'=>array()));
	array_push($ip_time['time'],date("Y/n/d H:i:s"));	

	$ip_time_re = array_reverse($ip_time['time']);
	if( count($ip_time_re)>2 ){
		if( $ip_time['block'] ){
			$ip_time['block'] = (strtotime($ip_time_re[0])-strtotime($ip_time_re[1])<30);
		}else{
			$ip_time['block'] = (strtotime($ip_time_re[0])-strtotime($ip_time_re[1])<10) && (strtotime($ip_time_re[1])-strtotime($ip_time_re[2])<10);
		}
	}
	Cache::put($ip, $ip_time, 10);

	$input['dddos_error'] = true;
	if( $ip_time['block']  && false )
		return Redirect::back()->withInput($input);
});

App::error(function($exception) {//找不到子頁面
	//return Response::view('nopage', array(), 404);
});

App::missing(function($exception) {
	//return Response::view('nopage', array(), 404);
});
