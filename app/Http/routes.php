<?php

use Illuminate\Support\Facades\Route;
use App\TextPage;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
get('/', 'Fore\WelcomeController@index');
get('/agreement', 'Fore\WelcomeController@getAgreement');
Route::any('/findPwd1', 'Fore\WelcomeController@findPwd1');
Route::any('/findPwd2', 'Fore\WelcomeController@findPwd2');
Route::any('/findPwd3', 'Fore\WelcomeController@findPwd3');

Route::controller('home', 'Fore\HomeController');
Route::controller('mall', 'Fore\MallController');
Route::post('purchase/offer', [
		'middleware' => 'auth',
		'uses' => 'Fore\PurchaseController@postOffer'
]);
Route::controller('purchase', 'Fore\PurchaseController');
Route::get('resource/upload', [
		'middleware' => 'auth',
		'uses' => 'Fore\ResourceController@getUpload'
]);
Route::get('resource/attention', [
		'middleware' => 'auth',
		'uses' => 'Fore\ResourceController@getAttention'
]);
Route::controller('resource', 'Fore\ResourceController');
Route::controller('demand', 'Fore\DemandCommitController');
Route::get('news/article/{id}', 'Fore\NewsController@getArticle');
Route::controller('news', 'Fore\NewsController');
Route::controller('ajax', 'Ajax\AjaxController');

Route::group(['prefix' => 'member', 'middleware' => 'auth'], function()
{
	//, 'middleware' => 'auth'
	Route::get('/', ['uses' => 'Member\HomeController@getIndex']);
	Route::any('/{entity}/{methods}', array('uses' => 'Member\MemberController@entityUrl'));
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('text/page/{id}', function ($id) {
	
	return view('fore.text_page')
			->with('id', $id)
			->with('text', TextPage::findOrNew($id));
});
//Admin 后台路由
Route::filter('admin_auth',function(){
	if(empty(session()->get('adminInfo'))){
		return \Redirect::to('/admin/login');
	}
});
Route::group(array('prefix' => 'admin','before' => 'admin_auth'), function(){

	Route::get('/index', 'Admin\AdminController@getIndex');
	Route::get('/test', 'Admin\AdminController@getTest');
	Route::get('/index', 'Admin\AdminController@getIndex');
	Route::get('/mangeList', 'Admin\AdminController@getMangeList');
	Route::get('/mangerEdit', 'Admin\AdminController@getMangeEdit');
	Route::controller('/article', 'Admin\ArticleInfoController');
	Route::controller('/role', 'Admin\RoleController');
	Route::controller('/user', 'Admin\UserController');
	Route::controller('/permission', 'Admin\PermissionController');
});

Route::get('/admin/login', 'Admin\AdminController@getLogin');
Route::post('/admin/login', 'Admin\AdminController@postLogin');
Route::get('/admin/loginout', 'Admin\AdminController@loginOut');
//Route::get('admin/test', 'Admin\AdminController@getTest');