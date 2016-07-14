<?php 
namespace App\Http\Controllers\Fore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Matriphe\Imageupload\Imageupload;
use App\Http\Controllers\ConstantTool;
use App\DemandInfo;
use App\DemandOffer;
use Illuminate\Support\Facades\Input;
use App\OrderInfo;

class PurchaseController extends Controller {

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
		/*
		 * Input::get('id')--->demand_info.id
		 */
		if (auth()->check()) {
			$demand = DemandInfo::with('user')->where('relTms', 3)->where('diStatus', '释放')->where('uid', '!=', auth()->user()->id);
		}
		else {
			$demand = DemandInfo::with('user')->where('relTms', 3)->where('diStatus', '释放');
		}
		if (Input::get('id'))
			$demand->where('id', Input::get('id'));
		$this->filter = \DataFilter::source($demand);
		$this->filter->build();
		
		$this->grid = \DataGrid::source($this->filter);
		$this->setGridCommon();
		$this->grid->add('dinum');
		$this->grid->add('pname');
		$this->grid->add('variety');
		$this->grid->add('alloyNum');
		$this->grid->add('processStatus');
		$this->grid->add('qty');
		$this->grid->add('unitWeight');
		$this->grid->add('intentPrc');
		$this->grid->add('city');
		$this->grid->add('payDays');
		$this->grid->add('deliveryDt');
		$this->grid->add('updated_at');
		$this->grid->add('{{ $user->uname }}');
		$this->grid->add('{{ $user->mobile }}');
		$this->grid->add('disMode');
		$this->grid->add('standard');
		$this->grid->add('offerCount');
		$this->grid->add('isOffer');
		$this->grid->paginate(20);
		$this->grid->orderBy('id', 'desc');
		$this->grid->row(function ($row) {
			$row->cell('offerCount')->value = DemandOffer::where('diid', $row->cell('id')->value)->count();
			$row->cell('isOffer')->value = 0;
			if (auth()->check()) {
				if (DemandOffer::where('diid', $row->cell('id')->value)->where('uid', auth()->user()->id)->count()) {
					$row->cell('isOffer')->value = 1;
				}
			}
		});
		$this->grid->getGrid('rapyd::datagrid_custom_purchase');
		
		/*
		 * 交易记录
		 */
		$orderData = OrderInfo::with('demand')->where('oiStatus', '待付款')->orWhere('oiStatus', '交易成功')->whereNotNull('diid')->get()->all(); 
		/*
		 * END
		 */
		return $this->baseReturnView('fore.purchase', null, $orderData); 
	}
	

	public function postOffer(Request $request) {
		//dd(Input::all());
		$demandOffer = new DemandOffer();
		$demandOffer->fill($request->all())->save();
		return response('<script>alert("采购报价成功！");window.location.href="'.url('purchase').'"</script>');
	}
}
