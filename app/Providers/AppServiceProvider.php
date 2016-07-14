<?php

namespace App\Providers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Product;
use App\ProdQty;
use App\Http\Controllers\ConstantTool;
use App\TextPage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	ini_set('xdebug.max_nesting_level', 1000);
    	session(['user_locked_goods'=>[]]);
    	view()->share('title', ConstantTool::TITLE);
    	view()->share('member_title', '会员中心');
    	view()->share('header', [
    			''=>'首页',
    			'mall'=>'现货供应',
    			'purchase'=>'采购报价',
    			'resource'=>'资源单',
    			'news'=>'资讯动态',
    	]);
    	view()->share('member_menu', [
    			'交易管理'=>[
    					'trade/shopcart'=>'我的购物车',
    					'trade/order'=>'我的商场订单',
    					'trade/myoffer'=>'我的报价',
    					'trade/invoice'=>'我的合同发票',
    			],
    			'发布管理'=>[
    					'purchase/release'=>'发布采购',
    					'purchase/index'=>'采购管理',
    					'purchase/demand_offer'=>'收到的报价',
    			],
    			'资源单管理'=>[
    					'resource/index'=>'我的资源单',
    					'resource/attention'=>'我的关注',
    			],
    			'帐户中心'=>[
    					'account/index'=>'帐户信息',
    					'account/integral'=>'我的积分',
    					'account/security'=>'帐户安全',
    			],
    	]);
    	$array = $this->getOptionGroupData(new TextPage(), 'title', 'pid');
    	$footer_column = [];
    	foreach ($array as $k=>$v) {
    		if ($k != 0) {
    			$footer_column[TextPage::findOrNew($k)->title] = $v;
    		}
    	}
    	view()->share('footer_column', $footer_column);


        /*
         * 自定义的表单验证
         */
    	Validator::extend('mobile', function($attribute, $value, $parameters)
    	{
    		return preg_match('/^^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/', $value);
    	});
    	
    	Validator::extend('idenNum', function($attribute, $value, $parameters)
    	{
    		if (strlen($value) == 15)
    		{
    			return (bool) preg_match("/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/", $value);
    		}
    		else {
    			return (bool) preg_match("/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}(\d|x|X)$/", $value);
    		}
    		
    	});
    	
    	
    	Validator::extend('ltoe', function($attribute, $value, $parameters)	//less than or equal, 验证小于等于的值
    	{
    		/*
    		 * array:2 [▼
  				0 => "0"
				]
    		 */
    		if (isset($parameters[0]))
    		{
    			if (is_numeric($value) && is_numeric($parameters[0]))
    			{
    				if ((int)$value <= $parameters[0])
    				{
    					return true;
    				}
    				else {
    					return false;
    				}
    			}
    		}
    	
    	});
    	Validator::extend('msgCode', function($attribute, $value, $parameters)	//短信验证码
    	{
    		if (isset($parameters[0])) {
    			if ($parameters[0]) {
    				
    				if (time() > session('msg_verify')['time_'.$parameters[0]]) {
    					return false;
    				}
    				else {
    					if ($value === session('msg_verify')[$parameters[0]]) {
    						return true;
    					}
    					else {
    						return false;
    					}
    				}
    			}
    		}
    		return false;
    		
    	
    	});
    	
    	Validator::extend('xlsx,xls,csv', function($attribute, $file, $parameters)	
    	{
    		//dd($file->getClientMimeType());
    		return $file instanceof UploadedFile
			        && in_array($file->getClientMimeType(), array(
			            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			            'application/vnd.ms-excel',
			            'text/csv',
			        ));
    		 
    	});
    }
    
    public function getOptionGroupData($model, $fieldName = 'name', $item = 'item', $id = 'id')
    {
    	$items1 = $model->lists($item, $id)->all();
    	$items2 = array_unique($items1);
    	$fieldNames = $model->lists($fieldName, $id)->all();
    	$options = array();
    	foreach ($items2 as $value) {
    		$options[$value] = array();
    		foreach ($items1 as $k => $v) {
    			if ($v == $value)
    			{
    				$options[$value][$k] = $fieldNames[$k];
    			}
    		}
    	}
    	return $options;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
