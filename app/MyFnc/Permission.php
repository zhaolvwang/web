<?php 
namespace App\MyFnc;

use Illuminate\Support\Facades\Redirect;
class Permission {

	/**
	 * 检测用户是否有该功能权限
	 * 
	 * @param string $pre 		标识要检测的功能
	 * @param string $index 	是否查看全部信息功能
	 * @param number $first 	该功能的增删查改中的增的位置（通常增删查改的权限位置是连续的）
	 * @param number $len
	 * @return boolean
	 */
    static public function permission($pre, $index = null, $first = 0, $len = 4)
    {
    	$pre = strtolower($pre);
    	if ($pre == '' || $pre == null)	//不检查，直接通过
    	{
    		return true;
    	}
    	if (\Auth::user()->groupId == 0)	//全权限管理员
    	{
    		return true;
    	}
    	
    	$pre = strtolower($pre);
    	$authority = str_split(\Session::get($pre.'_authority'));
    	//dd($authority);
    	if ($index === null )	// 查看全部信息功能,即:$current_entity/all
    	{
    		if (stripos(substr(\Session::get($pre.'_authority'), $first, $len), "1") !== false)
    		{
    			return true;	//只要权限中有1，即通过
    		}
    		else {
    			return false;
    		}
    	}
    	else {
    		if ($authority[$index] == 1)
    		{
    			return true;
    		}
    		else {
    			return false;
    		}
    	} 
    	
    	
    }
    
}
