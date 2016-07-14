<?php 
namespace App\Http\Controllers\Fore;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\DemandInfo;
use Illuminate\Http\Response;
use App\Http\Requests\DemandPostRequest;
use Illuminate\Http\Request;

class DemandCommitController extends Controller {

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
		//$this->middleware('auth');
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
	
	public function postCommit(Request $request) {
		
		$this->validate($request, [
				'mobile' => 'required|mobile',
				'content' => 'required',
		]);
		$demand = new DemandInfo();
		$demand->uid = auth()->check() ? auth()->user()->id : 0;
		$demand->mobile = Input::get('mobile');
		$demand->dinum = 'D'.time();
		$demand->content = Input::get('content');
		$demand->save();
		return new Response('<script>alert("发布需求成功，稍候经纪人会联系您！");window.location.href="'.url('/').'"</script>');
		/* if (Input::get('mobile') && Input::get('content')) {
			$demand = new DemandInfo();
			$demand->uid = auth()->user()->id;
			$demand->dinum = 'D'.time();
			$demand->content = Input::get('content');
			$demand->save();
			return new Response('<script>alert("发布需求成功，稍候经纪人会联系您！");window.location.href="'.url('demand/commit').'"</script>');
		}
		else {
			return redirect('demand/commit?mobile='.Input::get('mobile')."&content=".Input::get('content'));
		} */
		
	}
	
	public function postRelease(DemandPostRequest $request) {
		$demand = new DemandInfo();
		$demand->verifyStatus = '审核中';
		$demand->fill($request->all())->save();
		return new Response('<script>alert("发布采购成功！");window.location.href="'.url('member/purchase/release').'"</script>');
	}

}
