<?php
namespace App\Http\Controllers;

class ConstantTool {
	const WEBSITE_NAME = "找铝网";	const TITLE = "找铝网 - 铝材网上交易平台";	const ACCOUNT_FIELD = "email";
	const DEFAULT_LOGO = 'bundles/fe/images/index_04.gif';
	const TIPS_MINUTE = 2000;
	const HOME_URL = "/";
	const PAGE_COUNTS_FE = 10;	//前台分页中每页要显示的数目
	
	public static $AUTHORITY = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0); 	//每一个功能的权限初始位数，20位
	
	const ACCOUNT_FORMAT = "######";
	const BROKER_ID = "6";
	const DEFAULT_AVATAR_URL = "/packages/member/images/default_avatar.gif";
	const AVATAR_URL = 'uploads/website/avatar/';
	const AVATAR_WIDTH = 110;
	const AVATAR_HEIGHT = 110;
	const IMAGE_PUBLIC_URL = '/public/';
	const ADMIN_PAGE_COUNTS = 20;	//后台数据分页每页显示数据条数
	
	const ORDER_NUM_PREFIX = 'XS';	//订单编号前缀
	const PRICE_UNIT = '￥';			//价格符号
	const CANCEL_ORDER_TIME = '+8 hours';			//订单后默认3小时不付款，取消订单
}