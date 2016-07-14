<?php 
namespace App\Http\Controllers\Member;

use App\Http\Controllers\ConstantTool;
use Serverfireteam\Panel\CrudController;
use App\OrderInfo;
use App\CoopManu;
use App\Product;
use App\Http\Requests\DemandPostRequest;
use App\DemandInfo;
use App\DemandOffer;
use App\User;

class PurchaseController extends CrudController {

	public function release()
	{
		/*
		 * 厂家数据
		 */
		$coopManu = $this->getOptionGroupData(new CoopManu(), 'cname');
		$data = array();
		$data['coopManu'] = $coopManu;
		$data['processStatus'] = Product::getEnumValues('processStatus');
		$alloyNum = array(''=>"");
		foreach (Product::getEnumValues('alloyNum') as $k=>$v) {
			$alloyNum[$k] = $v;
		}
		$data['alloyNum'] = $alloyNum;
		$data['variety'] = Product::getEnumValues('matType');
		return $this->baseReturnView(null, null, $data);
	}
	
	public function index()
	{
		$this->purchaseCommon(DemandInfo::with('coopManuItem')->where('uid', auth()->user()->id)->whereNotNull('verifyStatus')->orderBy('id', 'desc'));
		$this->grid = \DataGrid::source($this->filter);
		$this->setGridCommon();
		$this->addGrid('dinum');
		$this->addGrid('variety');
		$this->addGrid('alloyNum');
		$this->addGrid('processStatus');
		$this->addGrid('standard');
		$this->addGrid('intentPrc');
		$this->addGrid('qty');
		$this->addGrid('unitWeight');
		$this->addGrid('disMode');
		$this->addGrid('verifyStatus');
		$this->addGrid('payDays');
		$this->addGrid('deliveryDt');
		$this->addGrid('province');
		$this->addGrid('freight');
		$this->addGrid('{{ $coopManuItem->cname }}');
		$this->addGrid('coopManu');
		$this->addGrid('amount');
		$this->grid->row(function ($row) {
			$row->cell('coopManu')->value = $row->cell('{{ $coopManuItem->cname }}')->value;
		});
		$this->grid->getGrid('rapyd::datagrid_custom_demand');
		
		return $this->baseReturnView();
	}
	
	public function index_check()
	{
		$this->index();
		return $this->baseReturnView();
	}
	
	public function index_pass()
	{
		$this->index();
		return $this->baseReturnView();
	}
	
	public function index_nopass()
	{
		$this->index();
		return $this->baseReturnView();
	}
	
	public function index_deal()
	{
		$this->index();
		return $this->baseReturnView();
	}
	
	public function demand_offer()
	{
		$this->filter = \DataFilter::source(DemandInfo::where('uid', auth()->user()->id)->where('relTms', 3)->where('diStatus', '释放'));
		$this->filter->attributes(['class'=>'form-custom member-search mt20 mb20 clearfix']);
		$this->addFilter('demand.dinum', 'demandNum')->placeholder('请输入采购单编号')->attributes(['class'=>'in-txt']);
		$this->addFilter('oiStatus')->attributes(['style'=>'display:none']);
		$this->filter->submit(trans('panel::fields.query'));
		$this->filter->build();
		$this->grid = \DataGrid::source($this->filter);
		$this->setGridCommon();
		$this->addGrid('dinum');
		$this->addGrid('variety');
		$this->addGrid('alloyNum');
		$this->addGrid('processStatus');
		$this->addGrid('standard');
		$this->addGrid('disMode');
		$this->addGrid('qty');
		$this->addGrid('province');
		$this->addGrid('city');
		$this->addGrid('county');
		$this->addGrid('payDays');
		/*
		 * 采购商报价信息字段
		 */
		$this->addGrid('cmpy');
		$this->addGrid('uname');
		$this->addGrid('mobile');
		$this->addGrid('offerDt');	//报价时间
		$this->addGrid('offerPrc');	
		$this->addGrid('offerQty');	
		$this->addGrid('offerAmount');	
		$this->addGrid('logFee');	
		$this->addGrid('offerProvince');	
		$this->addGrid('offerCity');	
		$this->addGrid('offerCounty');	
		
		$this->grid->row(function ($row) {
			$count = DemandOffer::where('diid', $row->cell('id')->value)->get()->all();
			foreach ($count as $v)
			{
				$offerUser = User::findOrNew($v['uid']);
				$row->cell('cmpy')->value = $offerUser->cmpy;
				$row->cell('uname')->value = $offerUser->uname;
				$row->cell('mobile')->value = $offerUser->mobile;
				$row->cell('offerDt')->value = substr($v['created_at'], 0, 10);
				$row->cell('offerPrc')->value = $v['offerPrc'];
				$row->cell('offerQty')->value = $v['offerQty'];
				$row->cell('offerAmount')->value = $v['offerAmount'];
				$row->cell('logFee')->value = $v['logFee'];
				$row->cell('offerProvince')->value = $v['province'];
				$row->cell('offerCity')->value = $v['city'];
				$row->cell('offerCounty')->value = $v['county'];
				array_push($row->cells2, $row->toArray(true));
			}
		});
		$this->grid->getGrid('rapyd::datagrid_custom_demandOffer');
		return $this->baseReturnView();
	}
	
	
	private function purchaseCommon($source = null)
	{
		$this->filter = \DataFilter::source($source ? $source : new OrderInfo);
		$this->filter->attributes(['class'=>'form-custom member-search mt20 mb20 clearfix']);
		$this->filter->add('dinum', '采购单编号', 'text')->placeholder('请输入采购单编号')->attributes(['class'=>'in-txt']);
		$this->addFilter('verifyStatus')->attributes(['style'=>'display:none']);
		$this->filter->submit(trans('panel::fields.query'));
		$this->filter->build();
	}
}
