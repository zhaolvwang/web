<?php 
namespace App\Http\Controllers\Fore;

use App\Http\Controllers\Controller;
use App\Product;
use App\CoopManu;
use App\App;
use App\ArticleInfo;
use App\ProdQty;
use App\DemandInfo;
use App\OrderInfo;
use App\TextPage;
use App\FreeGoods;
use App\MetalMarket;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/
	public $matType = '';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = [];
		$data['alloyNum'] = Product::getEnumValues('alloyNum');
		$data['processStatus'] = Product::getEnumValues('processStatus');
		$data['lvjuan'] = CoopManu::where('item', '铝卷')->lists('cname', 'id')->all();
		$data['lvban'] = CoopManu::where('item', '铝板')->lists('cname', 'id')->all();
		$data['lvbo'] = CoopManu::where('item', '铝箔')->lists('cname', 'id')->all();
		$data['xingcai'] = CoopManu::where('item', '型材')->lists('cname', 'id')->all();
		$data['coopManu'] = $this->getOptionGroupData(new CoopManu(), 'cname');
		//
		$data['products'] = [];
		/*
		 * buy:求购信息
		 * goods:现货资源
		 * trade:交易记录
		 */
		foreach (CoopManu::select('logo', 'item')->get()->groupBy('item')->all() as $k=>$v) {
			$data['products'][$k]['logo'] = $v->all();
			$this->matType = $k;
			
			/*
			 * 现货资源
			 */
			$data['products'][$k]['goods'] = ProdQty::with("product", 'inBy', 'storehouse')->where('stockStatus', '入库')->whereNull('inid')
			->whereHas('product', function ($query){
				$query->where('matType', $this->matType);
			})->orderby('created_at', 'desc')->get()->all();
			
			/*
			 * 求购信息
			 */
			$demand = DemandInfo::with('user')->where('relTms', 3)->where('diStatus', '释放')->where('variety', $k);
			if (auth()->check()) {
				$demand->where('uid', '!=', auth()->user()->id);
			}
			$data['products'][$k]['buy'] = $demand->get()->all();
			
			/*
			 * 交易记录
			 */
			$data['products'][$k]['trade'] = OrderInfo::with('demand')->where('diid', '!=', 0)->where('oiStatus', '待付款')->orWhere('oiStatus', '交易成功')->whereNotNull('diid')->whereHas('demand', function ($query){
				$query->where('variety', $this->matType);
			})->get()->all();
		}
		
		$data['indexImg'] = TextPage::findOrNew(25);		//首页图片
		$data['metalMarket'] = MetalMarket::all()->all();		//首页金属行情
		$data['freeGoods'] = FreeGoods::limit(3)->get()->all();		//客户经理免费找货
		$data['news'] = ArticleInfo::orderby('id', 'desc')->where('flag', 'like', '%推荐%')->get()->all();
		//dd($data);
		return view('welcome2')->with('data', $data);
	}

	/*
	 * 注册网站协议
	 */
	public function getAgreement() {
		return view('agreement');
	}
	public function findPwd1() {
		return view('findPwd.findPwd1');
	}
	public function findPwd2() {
		return view('findPwd.findPwd2');
	}
	public function findPwd3() {
		return view('findPwd.findPwd3');
	}
}
