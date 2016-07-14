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
use App\AdminsRole;
use App\AdminsRolePermission;
class RoleController extends Controller {


    public function getList(){

		$data = AdminsRole::where('status', 1)->paginate(20);
		//AdminsRole::create();
		$data1 = AdminsRole::getPermissionAll();
		//print_r($data1);
		return view('admin.role')->with('menu_open','role/list')->with('data', $data);
	}

	public function getAdd(){
		$id = Input::get('id', 0);
		$info = [];
		if($id > 0){
			$info = AdminsRole::find($id)->toArray();
		}
		//print_r($info);
		return view('admin.roleAdd')->with('id', $id)->with('info', $info);
	}
	public function postAdd(Request $request){
		$this->validate($request, [
			'role_name' => 'required|max:255',

		],array(),['role_name'=> trans("auth.role_name")]
		);
		$data = Input::get();
		//print_r($data);
		//exit;
		if($data['id'] > 0){
			//echo 1111;
			$model = AdminsRole::find($data['id']);
		}else{
			//echo 1111;
			$model = new AdminsRole();

		}
		//exit;
		$model->role_name = $data['role_name'];
		$model->status = $data['status'];
		$model->save();
		//AdminsRole::create(array('role_name'=>$data['role_name'],'status'=>$data['status'],));
		return redirect(action('Admin\RoleController@getList'));
	}

	/**
	 * 角色分配权限
	 * @return \Illuminate\View\View
	 */
	public function getPermissionSetting(){
		$role_name = AdminsRole::where('role_id', Input::get('id'))->pluck('role_name');
		$data = AdminPermission::select('permission_id as id', 'permission_parent_id as pId', 'permission_name as name',DB::raw('true as open'))->where('status', '正常')->get()->toArray();
		$role_permission = AdminsRolePermission::where('role_id', Input::get('id'))->lists('permission_id')->toArray();
		foreach($data as $key=>$value){
			$data[$key]['checked'] = in_array($value['id'], $role_permission) ? 1 : 0;
		}
		$data = json_encode($data);
		return view('admin.rolePermission')->with('role_name', $role_name)->with('data', $data)->with('role_id', Input::get('id'));
	}
	public function postPermissionSetting(){
		$data = Input::get();
		$model = new AdminsRolePermission;
		if($data['checked'] == 'true'){
			//查找当前role_id对应的permission_id是否存在，不存在才插入记录
			$id = AdminsRolePermission::where('role_id', $data['role_id'])->where('permission_id', $data['id'])->pluck('id');
			if(!$id){
				$model->role_id = $data['role_id'];
				$model->permission_id = $data['id'];
				$model->save();
			}

		}else{
			AdminsRolePermission::where('role_id', $data['role_id'])->where('permission_id', $data['id'])->delete();
		}
		echo json_encode(array('status'=>1, 'msg'=>'操作成功'));
	}
	public function getTable(){
		return view('admin.tables');
	}
}