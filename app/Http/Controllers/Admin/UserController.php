<?php

namespace App\Http\Controllers\Admin;

use App\AdminPermission;
use Illuminate\Support\Facades\DB;
use Serverfireteam\Panel\CrudController;
use \Illuminate\Http\Request;
use App\Authority;
use App\Group;
use App\MyFnc\Permission;
use Illuminate\Support\Facades\Input;
use App\User;
class UserController extends Controller {


    public function getList(){

		$data = User::orderby('created_at', 'desc')->paginate(20);
		return view('admin.user')->with('menu_open','user/list')->with('data', $data);
	}

	public function getAdd(){
		$id = Input::get('id', 0);
		$info = [];
		if($id > 0){
			$info = User::find($id)->toArray();
		}
		return view('admin.userAdd')->with('id', $id)->with('info', $info)->with('menu_open', 'user/add');
	}
	public function postAdd(Request $request){
		$this->validate($request, [
			'mobile' => 'required|max:255',
			'uname' => 'required|max:255',

		],array(),[
				'mobile'=> '手机号码',
				'uname'=> '真实姓名',
			]
		);
		$data = Input::get();

		if($data['id'] > 0){
			$model = User::find($data['id']);
		}else{
			$model = new User();
		}
		$model->fill($request->all())->save();
		return redirect(action('Admin\UserController@getList'));
	}

	public function getView(){
		$data = User::find(Input::get('id'));
		return view('admin.userView')->with('data', $data)->with('menu_open','user/list');
	}
}