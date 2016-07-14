<?php 
namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\ProdQty;
use App\Product;
use App\Storehouse;
use App\CoopManu;
use Illuminate\Http\Request;
use App\OrderInfo;
use Curl\Curl;

class AjaxController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	protected $msg = array();
	
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}

	
	public function getSetLockSession()
	{
		$this->msg['status'] = 0;
		/*
		 * 假如页面的数据与数据库的不一致，页面需要重新刷新
		 */
		foreach (session('user_locked_goods') as $k=>$v) {
			$prodQty = ProdQty::find($k);
			$db_qty = $prodQty->qty - ProdQty::where('inid', $k)->sum('qty') - $prodQty->lockedQty;
			if ($v['qty'] != $db_qty) {
				$array = session('user_locked_goods');
				array_set($array, "$k.qty", $db_qty);
				session(['user_locked_goods'=>$array]);
				$this->msg['status'] = -1;
			}
		}	
		$id = Input::get('id');		//product_qty.id
		$qty = Input::get('qty');		//qty:现货供应的数量，应该是现存数量(入库数量-出库数量)-锁货数量，是供应商可以选购的最大数量
		$type = Input::get('type');
		if ($this->msg['status'] != -1) {
			/*
			 * 页面点击【选购】时，在session中新建1条数据
			 */
			if ($type == '' && !isset(session('user_locked_goods')[$id])) {
				$prodQty = ProdQty::find($id);
				$product = Product::findOrNew($prodQty->pid);
				$this->msg = [
						'pname'=>$product->pname,
						'alloyNum'=>$product->alloyNum,
						'processStatus'=>$product->processStatus,
						'standard'=>$this->getProdStandard($product->thickness, $product->width, $product->long),
						'storehouse'=>Storehouse::findOrNew($product->siid)->storehouse,
						'coopManu'=>CoopManu::findOrNew($product->coopid)->cname,
						'oUnitPrc'=>$product->oUnitPrc,
						'weight'=>$prodQty->weight,
						'unit'=>$product->unit,
						'amount'=>Input::get('amount'),
						'qty'=>$qty,
						'pid'=>$prodQty->pid,
				];
				session(['user_locked_goods'=>array_add(session('user_locked_goods'), $id, $this->msg)]);
			}
			else if ($type != '' && isset(session('user_locked_goods')[$id])) {	//修改选购数量
				$this->updateUserLockedGoodsAmount($id, Input::get('amount'));
			}
			$count = 0;
			$allWeight = 0;
			$total = 0;
			foreach (session('user_locked_goods') as $v) {
				$count += $v['amount'];
				$allWeight += $v['weight'] * $v['amount'];
				$total += $v['weight'] * $v['amount'] * $v['oUnitPrc'];
			}
			$this->msg['weight'] = session('user_locked_goods')[$id]['weight']*Input::get('amount');
			$this->msg['sums'] = session('user_locked_goods')[$id]['weight']*Input::get('amount')*session('user_locked_goods')[$id]['oUnitPrc'];
			$this->msg['count'] = $count;
			$this->msg['all_weight'] = $allWeight;
			$this->msg['total'] = $total;
			session(['user_locked_goods_all_weight'=>$allWeight]);
			session(['user_locked_goods_total'=>$total]);
			session(['user_locked_goods_count'=>$count]);
		}
		return response()->json($this->msg);
	}
	
	private function updateUserLockedGoodsAmount($id, $amount) {
		$array = session('user_locked_goods');
		array_set($array, "$id.amount", $amount);
		session(['user_locked_goods'=>$array]);
	}
	
	public function getDeleteLockSession(Request $request) {
		/*
		 * 通过ajax，清空session中的数据
		 */
		$id = Input::get('id');
		$count = 0;
		$allWeight = 0;
		$total = 0;
		if ($request->session()->has("user_locked_goods.$id")) {
			$request->session()->pull("user_locked_goods.$id");
		}
		/*
		 * id == ''清空全部数据
		 */
		if ($id == '') {
			session(['user_locked_goods'=>[]]);
		}
		else {
			/*
			 * 当删除指定数据时，更新全部数量、全部重量和总额
			 */
			foreach (session('user_locked_goods') as $v) {
				$count += $v['amount'];
				$allWeight += $v['weight'] * $v['amount'];
				$total += $v['weight'] * $v['amount'] * $v['oUnitPrc'];
			}
		}
		
		$this->msg['count'] = $count;
		$this->msg['all_weight'] = $allWeight;
		$this->msg['total'] = $total;
		session(['user_locked_goods_all_weight'=>$allWeight]);
		session(['user_locked_goods_total'=>$total]);
		session(['user_locked_goods_count'=>$count]);
		return response()->json($this->msg);
	}
	
	/*
	 * 取消订单操作
	 */
	public function getSetOrderCancel() {
		$oiid = Input::get('oiid');
		OrderInfo::findOrNew($oiid)->update(['oiStatus'=>'交易关闭']);
		$this->msg['status'] = 1;
		return response()->json($this->msg);
	}
	
	
	public function getSendMessage() {
		//http://api.duanxin.cm/?action=send&username=70211652&password=e10adc3949ba59abbe56e057f20f883e&phone=13232907227&content=529100&encode=utf8
		$msg_verify_code = rand(100000, 999999);
		$param = [
			'name'=>17705283126,
			'pwd'=>'65AB6D45B07CC748EF49815FD997',
			'mobile'=>Input::get('mobile'),
			'content'=>'您正在进行找铝网登陆手机验证，验证码为'.$msg_verify_code.'，10分钟内有效，请及时输入。', 
			'sign'=>'找铝网', 
			'type'=>'pt'
		]; 
		session(['msg_verify'=>[Input::get('mobile')=>$msg_verify_code, 'time_'.Input::get('mobile')=>strtotime('+10 minutes', time())]]);
		$curl = new Curl();
		$curl->post('http://web.1xinxi.cn/asmx/smsservice.aspx', $param);
		//return response()->json(json_decode($curl->response, true));   
		return response($curl->response);    
	}
	
}
