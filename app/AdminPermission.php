<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model
{
    //
    protected $table = 'admins_permission';
    protected $primaryKey = 'permission_id';

    public function parentPermission(){
        return $this->hasOne('App\AdminPermission', 'permission_parent_id', 'permission_id');
    }

    /**
     * 检查当前登陆的角色是否有该操作权限
     * @param $code
     * @return bool
     */
    public static function hasPermission($code){
        $result = false;
        if($code){
            $adminInfo = session()->get('adminInfo');
            //$role_id = $adminInfo['role_id'];
            $dataAll = \DB::table('admins_role_permission as arp')
                //->select('ap.permission_code')
                ->distinct('arp.permission_id')
                ->join('admins_permission as ap', 'arp.permission_id','=', 'ap.permission_id')
                ->where('role_id', $adminInfo['role_id'])
                ->where('type', '操作')
                ->lists('ap.permission_code');
            //print_r($dataAll);
            $result = in_array($code, $dataAll) ? true : false;
        }
        return $result;
    }
}
