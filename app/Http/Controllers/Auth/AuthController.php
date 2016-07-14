<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectPath = '/member';
    //protected $loginPath = '/login';
    
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }
    
    // 登录页面
    public function getLogin()
    {
    	return view('auth.login');
    }
    
    // 验证码
    public function getValidation()
    {
        $width = Input::get('width', 80);
        $height = Input::get('height', 34);
    	$code=new ValidationCode($width, $height, 4);
    	//$_SESSION['SESSION_VALIDATE_REGISTER']=$code->getCheckCode();     //将验证码的值存入session中以便在页面中调用验证
        session()->put('SESSION_VALIDATION', strtolower($code->getCheckCode()));
    	return response($code->showImage(), 200);//输出验证码 */
    }
    
    public function getValidationCode()
    {
    	return response()->json(['validation_code'=>strtolower(session('SESSION_VALIDATION'))]); 
    }
    
    /* // 登录操作
    public function postLogin()
    {
    	if (\Auth::attempt(['email' => \Input::get('email'), 'password' => \Input::get('password'), 'active' => 1], \Input::get('remember'))) {
    		// The user is active, not suspended, and exists.
    		return \Redirect::to('/')->with('message', '成功登录');
    	}
    	else {
    		 return \Redirect::to('auth/login')->withInput(\Input::except('password'))->with('errors', ['Authentication failed.']);
    		
    	}
    } */
    
    // 注册界面Step1
    public function getRegister()
    {
    	return view('auth.register1');
    	if (\Input::get('step') == 1 || \Input::get('step') == '')
    	{
    		return view('auth.register1');
    	}
    	elseif (\Input::get('step') == 2)
    	{
    		return view('auth.register');
    	}
    	
    }
    
    
    // 注册界面Step2
    public function postRegister1(\Illuminate\Http\Request $request)
    {
    	$this->validate($request, [
            'mobile' => 'required|mobile|unique:user_profile', 'validateCode' => 'required',
        ],array(),
    	[
            'mobile' => trans("panel::fields.mobile"), 'validateCode' => trans("auth.validateCode"),
        ]);
    	
   		if (\Input::get('step') == 2 && \Input::get('mobile') && \Input::get('validateCode'))
    	{
    		\Session::put('mobile', \Input::get('mobile'));
    		return view('auth.register');
    	}
    	
    }
    
    // 注册有误界面Step2
    public function getRegister1()
    {
    	return view('auth.register');
    	 
    
    }
    
    /**
     * 通过ajax得到account是否已经存在
     */
    public function getAccount()
    {
    	$exits = User::firstOrNew(['mobile'=>Input::get('account')])->exists;
    	$status = $exits ? 1 : 0;
    	return response()->json(['status'=>$status]);
    }
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'mobile' => 'required',
            'uname' => 'required|max:255',
            'cmpy' => 'required',
            'password' => 'required|confirmed|min:6|max:18',
            'validateCode' => 'required',
            'msgCode' => 'required|msgCode:'.$data['mobile'],
        ], array(), [
            'uname' => trans("auth.trueName"),
            'cmpy' => trans("auth.cmpy"),
            'password' => trans("panel::fields.password"),
            'validateCode' => trans("panel::fields.validateCode"),
            'msgCode' => '短信验证码',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
    	//dd($data['mobile']);
        return User::create([
            'uname' => $data['uname'],
            'mobile' => $data['mobile'],
            'cmpy' => $data['cmpy'],
            'password' => bcrypt($data['password']),
        ]);
    }
    
}
