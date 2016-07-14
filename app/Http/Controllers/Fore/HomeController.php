<?php 
namespace App\Http\Controllers\Fore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserPostRequest;
use Illuminate\Support\Facades\Auth;
use Matriphe\Imageupload\Imageupload;
use App\Http\Controllers\ConstantTool;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return view('home');
	}
	
	public function postIndex(UserPostRequest $request)
	{
		$user = auth()->user();
		$imageUpload = new Imageupload();
		if ($request->hasFile("regPic")) {
			
			$user->regPic = $imageUpload->upload($request->file('regPic'), 'reg_'.md5($user->id), $user->id)['filename'];
		}
		if ($request->hasFile("taxPic")) {
			
			$user->taxPic = $imageUpload->upload($request->file('taxPic'), 'tax_'.md5($user->id), $user->id)['filename'];
		}
		if ($request->hasFile("orgPic")) {
			
			$user->orgPic = $imageUpload->upload($request->file('orgPic'), 'org_'.md5($user->id), $user->id)['filename'];
		}
		$user->fill($request->except(['regPic', 'taxPic', 'orgPic']))->save();
		
		//return view('member.account.index')->with('import_message', '修改成功')->with('data', $user);
		return redirect('member/account/index?import_message=1');
	}
	
	public function postUpdatePwd(Request $request)
	{
		$user = auth()->user();
		if (Hash::check(Input::get('password'), $user->password)) {
			$this->resetPassword($user, Input::get('password_confirmation'));
			return redirect('member/account/security?import_message=2');
		}
		else {
			return redirect('member/account/security?import_message=1');
		}
	}
	
	/**
	 * Reset the given user's password.
	 *
	 * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
	 * @param  string  $password
	 * @return void
	 */
	protected function resetPassword($user, $password)
	{
		$user->password = bcrypt($password);
		$user->save();
	}

}
