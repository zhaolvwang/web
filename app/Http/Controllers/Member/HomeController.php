<?php 
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserPostRequest;
use Illuminate\Support\Facades\Auth;
use Matriphe\Imageupload\Imageupload;
use App\Http\Controllers\ConstantTool;
use App\OrderInfo;
use App\ProdOrderRel;
use App\DemandInfo;

class HomeController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$data = OrderInfo::where('uid', auth()->user()->id)->orderby('created_at', 'desc')->take(10)->get()->toArray();
		foreach ($data as &$v) {
			$products = ProdOrderRel::where('oiid', $v['id']);
			$orderInfo = OrderInfo::findOrNew($v['id']);
			$v['qty'] = $products->sum('actualQty') ? $products->sum('actualQty') : $products->sum('orderQty');
			$v['weight'] = $orderInfo->totalWeight();
			$v['amount'] = $orderInfo->totalAmount();
			if ($v['oiStatus'] == '待付款' && strtotime($v['canceled_at']) < time()) {
				OrderInfo::findOrNew($v['id'])->update(['oiStatus'=>'交易关闭']);
			}
		}
		return view('member.home', [
				'data'=>$data, 
				'orderCount'=>OrderInfo::where('uid', auth()->user()->id)->count(),
				'demandCount'=>DemandInfo::where('uid', auth()->user()->id)->count(),
		]);
	}
	
}
