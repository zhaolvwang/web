<?php 
namespace App\Http\Controllers\Fore;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\DemandInfo;
use Illuminate\Http\Response;

class DemandController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getCommit()
	{
		return view('fore.commit', ['content'=>Input::get('content')]);
	}
	
	public function postCommit()
	{
		if (auth()->check()) {
			$demand = new DemandInfo();
			$demand->uid = auth()->user()->id;
			$demand->dinum = 'D'.time();
			$demand->cmpy = Input::get('cmpy');
			$demand->uname = Input::get('uname');
			$demand->mobile = Input::get('mobile');
			$demand->content = Input::get('content');
			$demand->save();
			
			return new Response('<script>alert("发布需求成功，稍候经纪人会联系您！");window.location.href="/demand/commit"</script>');
		}
		
	}

}
