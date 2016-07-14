<?php

namespace App\Http\Controllers\Admin;

use App\AdminPermission;
use Serverfireteam\Panel\CrudController;
use \Illuminate\Http\Request;
use App\Authority;
use App\Group;
use App\MyFnc\Permission;
use Illuminate\Support\Facades\Input;
use App\AdminsRole;

class PermissionController extends Controller {


    public function getList(){

		$data = AdminPermission::select('admins_permission.*', 'p.permission_name as parent_name')->leftJoin('admins_permission as p ', 'admins_permission.permission_parent_id', '=', 'p.permission_id')->paginate(10);
		return view('Admin.permission_list')->with('menu_open','permission/list')->with('data', $data);
	}

	public function getAdd(){
		$tree = AdminsRole::getPermissionTree();
		//print_r($tree);
		return view('Admin.permissionAdd')->with('menu_open', 'permission/add')->with('tree', $tree);
	}
	public function postAdd(){
		$data = Input::get();
		\DB::table('admins_permission')->insert([
						'permission_name' => $data['permission_name'],
						'permission_code' => $data['permission_code'],
						'permission_parent_id' => $data['permission_parent_id'],
						'type' => $data['type']
				]
				);
//		$model->create([
//						'permission_name' => $data['permission_name'],
//						'permission_code' => $data['permission_code'],
//						'permission_parent_id' => $data['permission_parent_id'],
//						'type' => $data['type']
//				]);
		return redirect(url($this->url_prev . 'permission/list'));

	}
	public function getView(){
		$id = Input::get('id');
		$data = AdminPermission::select('admins_permission.*', 'p.permission_name as parent_name')->leftJoin('admins_permission as p ', 'admins_permission.permission_parent_id', '=', 'p.permission_id')->find($id);
		return view('Admin.permission_view')->with('data', $data);
	}

	public function getGrid(){
		return view('Admin.grid');
	}

	public function getTable(){
		return view('Admin.tables');
	}
}