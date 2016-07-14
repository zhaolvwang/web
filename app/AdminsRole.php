<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AdminPermission;

class AdminsRole extends Model {

    protected $table = 'admins_role';
    protected $primaryKey = 'role_id';
    public $role_permission_table = 'admins_role_permission';
    public $timestamps = false;
    /**
     * 获取角色的权限
     * @param $role_id
     * @return array
     */
    public static function getRolePermission($role_id){
        $data = [];
        $permission = [];
        if($role_id){
            $dataAll = \DB::table('admins_role_permission as arp')
                ->distinct('arp.permission_id')
                ->join('admins_permission as ap', 'arp.permission_id','=', 'ap.permission_id')
                ->where('role_id',$role_id)
                ->get();
            $dataAll = self::object_to_array($dataAll);
            foreach($dataAll as $dd){
               if($dd['permission_parent_id'] == 0){
                    $data[$dd['permission_id']] = $dd;
                }

            }
            foreach($dataAll as $dd){
                if($dd['permission_parent_id'] > 0){
                    if($dd['type'] == '菜单'){
                        $data[$dd['permission_parent_id']]['menu'][$dd['id']] = $dd;
                    }else{
                        $permission[$dd['permission_parent_id']][] = $dd;
                    }
                }
            }
        }
        //第二次循环将权限插入到对应的菜单上
        foreach($data as $key=>$value){
            if(isset($value['menu']) && !empty($value['menu'])){
                foreach($value['menu'] as $k=>$v){
                    $data[$key]['menu'][$k]['permission'] = isset($permission[$k]) ? $permission[$k] : [];
                }
            }
        }
        //print_r($permission);
        return $data;
    }

    public static function object_to_array($obj)
    {
        $arr = [];
        $_arr= is_object($obj) ? get_object_vars($obj) : $obj;
        foreach($_arr as $key=> $val)
        {
            $val= (is_array($val) || is_object($val)) ?       self::object_to_array($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }

    public static function getPermissionTree(){
        $data = [];
        $dataAll = \DB::table('admins_permission as arp')
            ->where('arp.status', '正常')
            ->where('arp.type', '菜单')
            ->get();
        $dataAll = self::object_to_array($dataAll);
        foreach($dataAll as $dd){
            if($dd['permission_parent_id'] > 0){
                if($dd['type'] == '菜单'){
                    $data[$dd['permission_parent_id']]['menu'][] = $dd;
                }
            }else{
                $data[$dd['permission_id']] = $dd;
            }
        }
        return $data;
    }
    public static function getPermissionAll(){
        $data = [];
        $dataAll = \DB::table('admins_permission as arp')
            ->where('arp.status', '正常')
            ->get();
        $dataAll = self::object_to_array($dataAll);

        $data = self::formatData($dataAll);
        return $data;
    }
    protected static function formatData($dataAll){
        $data = [];
        foreach($dataAll as $dd){
            if($dd['permission_parent_id'] > 0){
                if($dd['type'] == '菜单'){
                    $data[$dd['permission_parent_id']]['menu'][$dd['permission_id']] = $dd;
                }else{
                    $permission[$dd['permission_parent_id']][] = $dd;
                }
            }else{
                $data[$dd['permission_id']] = $dd;
            }

        }
        //第二次循环将权限插入到对应的菜单上
        foreach($data as $key=>$value){
            if(isset($value['menu']) && !empty($value['menu'])){
                foreach($value['menu'] as $k=>$v){
                $data[$key]['menu'][$k]['permission'] = isset($permission[$k]) ? $permission[$k] : [];
                }
            }
        }
        return $data;
    }

}
