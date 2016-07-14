<?php 
namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Requests\UserPostRequest;
use App\Http\Controllers\ConstantTool;
use Serverfireteam\Panel\CrudController;
use App\OrderInfo;
use App\Product;
use App\ProdOrderRel;
use App\ProdQty;
use Illuminate\Support\Facades\DB;
use App\Storehouse;
use App\DemandInfo;
use App\DemandOffer;
use Illuminate\Support\Facades\Input;
use App\PGBill;
use App\OrderDetails;
use App\InvoiceInfo;
use App\ContractInfo;

class TradeController extends CrudController {

	public function shopcart()
	{
		$staff = [];
		$staffData = \App\Admin::where('groupId', 14)->get()->all();
		foreach ($staffData as $v) {
			$staff[$v->id] = $v->first_name.'-'.$v->account.'-'.$v->mobile;
		}
		return $this->baseReturnView(null, null, ['staff'=>$staff]);
	}
	
	public function order()
	{
		$this->orderCommon(OrderInfo::where('uid', auth()->user()->id));
		$this->grid = \DataGrid::source($this->filter);
		$this->addGrid('oinum');
		$this->addGrid('oiStatus');
		$this->addGrid('unit');
		$this->addGrid('pname');
		$this->addGrid('coopManu');
		$this->addGrid('alloyNum');
		$this->addGrid('processStatus');
		$this->addGrid('standard');
		$this->addGrid('storehouse');
		$this->addGrid('oUnitPrc');
		$this->addGrid('qty');
		$this->addGrid('weight');
		$this->addGrid('prodWeight');
		$this->addGrid('totWeight');
		$this->addGrid('amount');
		$this->addGrid('canceled_at');
		$this->addGrid('pgbType');
		$this->addGrid('voucher');
		$this->addGrid('remarks');
		$this->addStylesToGrid('id', 10);
		$this->setGridCommon();
		$this->grid->row(function ($row) {
			$row->cell('oinum')->style('display:none;');
			$row->cell('oiStatus')->style('display:none;');
			$row->cell('unit')->style('display:none;');
			$row->cell('totWeight')->style('display:none;');
			$row->cell('amount')->style('display:none;');
			$row->cell('canceled_at')->style('display:none;');
			$row->cell('pgbType')->style('display:none;');
			$row->cell('voucher')->style('display:none;');
			$row->cell('remarks')->style('display:none;');
			$pgb = PGBill::firstOrNew(['oiid'=>$row->cell('id')->value]);
			$row->cell('pgbType')->value = $pgb->pgbType;
			$row->cell('voucher')->value = $pgb->voucher;
			$row->cell('remarks')->value = $pgb->remarks;
			$count = ProdOrderRel::where('oiid', $row->cell('id')->value)->get()->all();
			$amount = 0;
			$totWeiht = 0;
			foreach ($count as $v)
			{
				$prodOrderRel = $v;
				$prodQty = ProdQty::findOrNew($prodOrderRel->pqid);
				$product = Product::findOrNew($prodOrderRel->pid);
				$row->cell('pname')->value = $product->pname;
				$row->cell('coopManu')->value = $product->coopManu->cname;
				$row->cell('alloyNum')->value = $product->alloyNum;
				$row->cell('processStatus')->value = $product->processStatus;
				$row->cell('standard')->value = $this->getProdStandard($product->thickness, $product->width, $product->long);
				$row->cell('storehouse')->value = $prodQty->storehouse->storehouse;
				$row->cell('oUnitPrc')->value = '<p class="color-orange font-s-16 yahei">￥'.$product->oUnitPrc.'</p>';
				$row->cell('qty')->value = $prodOrderRel->actualQty ? $prodOrderRel->actualQty : $prodOrderRel->orderQty;
				$row->cell('weight')->value = $prodQty->weight;
				$row->cell('prodWeight')->value = $prodQty->weight*(int)$row->cell('qty')->value;
				$amount += $prodOrderRel->orderAmount;
				$totWeiht += $row->cell('prodWeight')->value;
				array_push($row->cells2, $row->toArray(true));
			}
			$row->cell('amount')->value = $amount;
			$row->cell('totWeight')->value = $totWeiht;
			$this->tdAttributes($row->cell('pname'), '10%');
			$this->tdAttributes($row->cell('coopManu'), '10%');
			$this->tdAttributes($row->cell('alloyNum'), '8%');
			$this->tdAttributes($row->cell('processStatus'), '8%');
			$this->tdAttributes($row->cell('standard'), '16%');
			$this->tdAttributes($row->cell('storehouse'), '10%');
			$this->tdAttributes($row->cell('oUnitPrc'), '14%');
			$this->tdAttributes($row->cell('qty'), '8%');
			$this->tdAttributes($row->cell('weight'), '8%');
			$this->tdAttributes($row->cell('prodWeight'), '8%');
			
		});
		$this->grid->getGrid('rapyd::datagrid_custom_order');
		return $this->baseReturnView();
	}
	
	public function order_pay()
	{
		$this->order();
		return $this->baseReturnView();
	}
	
	public function order_delivery()
	{
		$this->order();
		return $this->baseReturnView();
	}
	
	public function order_replenishment()
	{
		$this->order();
		return $this->baseReturnView();
	}
	
	public function order_invoice()
	{
		$this->order();
		return $this->baseReturnView();
	}
	
	public function order_detail()
	{
		/*
		 * Input::get('id')--->order_info.id
		 */
		$data = array();
		$data['info'] = OrderInfo::findOrNew(Input::get('id'))->toArray();
		$data['info']['amount'] =  OrderInfo::findOrNew(Input::get('id'))->totalAmount();
		$data['info']['actualAmount'] =  OrderInfo::findOrNew(Input::get('id'))->totalAmount(true);
		$data['pgb'] =  PGBill::firstOrNew(['oiid'=>Input::get('id')])->toArray();
		$data['details'] =  OrderDetails::where('oiid', Input::get('id'))->get()->toArray();
		$data['products'] = array();
		$data['products']['storehouse'] = Storehouse::findOrNew($data['info']['siid'])->toArray();
		$data['products']['info'] = array();
		$products_info = ProdOrderRel::where('oiid', Input::get('id'))->get();
		foreach ($products_info as $v) {
			$product = Product::findOrNew($v->pid);
			$prodQty = ProdQty::findOrNew($v->pqid);
			$info = $v->toArray();
			$info['pname'] = $product->pname;
			$info['alloyNum'] = $product->alloyNum;
			$info['processStatus'] = $product->processStatus;
			$info['standard'] = $this->getProdStandard($product->thickness, $product->width, $product->long);
			$info['oUnitPrc'] = $product->oUnitPrc;
			$info['unitWeight'] = $prodQty->weight;
			$info['weight'] = $v->orderQty*$prodQty->weight;
			$info['amount'] = $v->orderQty*$prodQty->weight*$product->oUnitPrc;
			array_push($data['products']['info'], $info);
		}
		/*
		 * $data['invoice']['status']
		 * 0:未开票
		 * 1:已申请开票
		 * 2:已开票
		 */
		if (InvoiceInfo::firstOrNew(['oiid'=>$data['info']['id']])->exists) {
			$data['invoice']['status'] = 2;
		}
		else {
			if ($data['info']['applied_at']) {
				$data['invoice']['status'] = 1;
			}
			else {
				$data['invoice']['status'] = 0;
			}
		}
		
		//dd($data);
		return $this->baseReturnView(null, null, $data);
	}
	
	public function myoffer()
	{
		$this->filter = \DataFilter::source(DemandOffer::with('demand')->where('uid', auth()->user()->id));
		$this->filter->attributes(['class'=>'form-custom member-search mt20 mb20 clearfix']);
		$this->addFilter('demand.dinum', 'demandNum')->placeholder('请输入采购单编号')->attributes(['class'=>'in-txt']);
		$this->addFilter('oiStatus')->attributes(['style'=>'display:none']);
		$this->filter->submit(trans('panel::fields.query'));
		$this->filter->build();
		$this->grid = \DataGrid::source($this->filter);
		$this->setGridCommon();
		$this->addGrid('{{ $demand->dinum }}');
		$this->addGrid('{{ $demand->variety }}');
		$this->addGrid('{{ $demand->alloyNum }}');
		$this->addGrid('{{ $demand->processStatus }}');
		$this->addGrid('{{ $demand->standard }}');
		$this->addGrid('city');
		$this->addGrid('offerPrc');
		$this->addGrid('offerQty');
		$this->addGrid('offerAmount');
		$this->addGrid('logFee');
		$this->addGrid('{{ $demand->disMode }}');
		$this->grid->getGrid('rapyd::datagrid_custom_myoffer');
		return $this->baseReturnView();
	}
	
	public function delivery_goods()
	{
		/*
		 * Input:get('id')--->order_info.id
		 */
		$this->filter = \DataFilter::source(DemandOffer::with('demand')->where('uid', auth()->user()->id));
		$this->filter->build();
		$this->grid = \DataGrid::source($this->filter);
		$this->setGridCommon();
		$this->addGrid('{{ $demand->dinum }}');
		$this->addGrid('{{ $demand->variety }}');
		$this->addGrid('{{ $demand->alloyNum }}');
		$this->addGrid('{{ $demand->processStatus }}');
		$this->addGrid('{{ $demand->standard }}');
		$this->addGrid('city');
		$this->addGrid('offerPrc');
		$this->addGrid('offerQty');
		$this->addGrid('offerAmount');
		$this->addGrid('logFee');
		$this->addGrid('{{ $demand->disMode }}');
		$this->grid->getGrid('rapyd::datagrid_custom_myoffer');
		$info = array();
		$products = array();
		$orderInfo = OrderInfo::findOrNew(Input::get('id'));
		if ($orderInfo->siid) {
			$info['oinum'] = $orderInfo->oinum;
			$info['storehouse'] = Storehouse::findOrNew($orderInfo->siid)->toArray();
			$products = ProdOrderRel::with('product', 'prodQty')->where('oiid', $orderInfo->id)->get()->all();
		}
		
		return $this->baseReturnView(null, null, ['no_member_menu'=>'1', 'info'=>$info, 'products'=>$products]);
	}
	
	/**
	 * 撤消采购报价
	 */
	public function cancelOffer() {
		/*
    	 * Input::get('id')----->demand_offer.id
    	 */
		DemandOffer::findOrNew(Input::get('id'))->delete();
		return redirect('member/trade/myoffer');
	}
	
	public function invoice()
	{
		$this->filter = \DataFilter::source(OrderInfo::whereNotNull('applied_at')->where('uid', auth()->user()->id));
		$this->filter->attributes(['class'=>'form-custom member-search mt20 mb20 clearfix']);
		$this->addFilter('oinum')->placeholder('请输入订单编号')->attributes(['class'=>'in-txt']);
		$this->filter->submit(trans('panel::fields.query'));
		$this->filter->build();
		
		$this->grid = \DataGrid::source($this->filter);
		$this->setGridCommon();
		$this->addGrid('oinum');
		$this->addGrid('letterHeader');
		$this->addGrid('applied_at');
		$this->addGrid('amount');
		$this->addGrid('annex');
		$this->grid->row(function ($row) {
			$row->cell('amount')->value = OrderInfo::findOrNew($row->cell('id')->value)->totalAmount();
			$row->cell('annex')->value = InvoiceInfo::firstOrNew(['oiid'=>$row->cell('id')->value])->annex;
		});
		$this->grid->getGrid('rapyd::datagrid_custom_invoice');
		
		return $this->baseReturnView();
	}
	
	public function invoice_contract()
	{
		$this->filter = \DataFilter::source(ContractInfo::with('order')->whereIn('oiid', OrderInfo::where('uid', auth()->user()->id)->lists('id')->all()));
		$this->filter->attributes(['class'=>'form-custom member-search mt20 mb20 clearfix']);
		$this->filter->add('order.oinum','订单编号', 'text')->placeholder('请输入订单编号')->attributes(['class'=>'in-txt']);
		$this->filter->submit(trans('panel::fields.query'));
		$this->filter->build();
		
		$this->grid = \DataGrid::source($this->filter);
		$this->setGridCommon();
		$this->addGrid('{{ $order->oinum }}');
		$this->addGrid('{{ $order->created_at }}');
		$this->addGrid('annexName');
		$this->addGrid('isReturn');
		$this->addGrid('conStatus');
		$this->addGrid('annex');
		$this->addGrid('oiid');
		$this->grid->row(function ($row) {
		});
		$this->grid->getGrid('rapyd::datagrid_custom_contract');
		$this->grid->orderBy('created_at', 'desc');
		$this->grid->paginate(10);
		return $this->baseReturnView();
	}
	
	/**
	 * 采购商在商场选购完产品后，锁货或下单进行数据insert函数
	 * 
	 * @param Request $request
	 */
	public function insertNewOrder($entity, Request $request) {
		$collection = collect(session('user_locked_goods'));
		/*
		 * 将数据通过collection以仓库进行groupby
		 */
		$grouped = $collection->groupBy(function (&$item, $key) {
			$item['pqid'] = $key;
			return $item['storehouse'];
		});
		$orderData = $grouped->toArray();
		//dd($orderData);
		foreach ($orderData as $s=>$item) {
			$orderInfo = new OrderInfo();
			$orderInfo->oinum = ConstantTool::ORDER_NUM_PREFIX.date('ymdHis').rand(10, 99);
			$orderInfo->uid = auth()->user()->id;
			$orderInfo->siid = Storehouse::firstOrNew(['storehouse'=>$s])->id;
			$orderInfo->unit = $item[0]['unit'];
			$orderInfo->regBy = $request->get('regBy');
			$orderInfo->save();
			foreach ($item as $v) {
				$prodOrderRel = new ProdOrderRel();
				$prodOrderRel->oiid = $orderInfo->id;
				$prodOrderRel->pqid = $v['pqid'];
				$prodOrderRel->pid = $v['pid'];
				$prodOrderRel->orderQty = $v['amount'];
				$prodOrderRel->orderAmount = $v['amount']*$v['weight']*$v['oUnitPrc'];
				$prodOrderRel->save();
			}
		}
		session(['user_locked_goods'=>""]);
		return response('<script>alert("下单成功！");window.location.href="'.url('member/trade/order').'"</script>');
	}
	
	/**
	 * 填订单的提货函
	 */
	public function setPgBill() {
		/*
		 * Input::get('oiid')----->pickup_goods_bill.oiid
		 */
		PGBill::firstOrNew(['oiid'=>Input::get('oiid')])->fill(Input::except(['oiStatus']))->save();
		return response('<script>alert("填提货函成功！");window.location.href="'.url('member/trade/order?oiStatus='.Input::get('oiStatus').'&search=1').'"</script>');
	}
	
	/**
	 * 申请开发票
	 */
	public function applyInvoice() {
		/*
		 * Input::get('id')----->order_info.id
		 */
		if (!Input::get('letterHeader')) 
			return response('<script>alert("请填写开票抬头");window.history.go(-1);</script>');
		OrderInfo::findOrNew(Input::get('id'))->update(['applied_at'=>date('Y-m-d H:i:s'), 'oiStatus'=>'待开票', 'letterHeader'=>Input::get('letterHeader')]);
		return response('<script>alert("申请开发票成功！");window.location.href="'.url('member/trade/order_detail?id='.Input::get('id')).'"</script>');
	}
	
	private function orderCommon($source = null) {
		$this->filter = \DataFilter::source($source ? $source : new OrderInfo);
		$this->filter->attributes(['class'=>'form-custom member-search mt20 mb20 clearfix']);
		$this->addFilter('oinum')->placeholder('请输入订单编号')->attributes(['class'=>'in-txt']);
		$this->addFilterSelect('oiStatus', new OrderInfo());
		$this->filter->submit(trans('panel::fields.query'));
		$this->filter->build();
	}
	
	private function tdAttributes($grid, $width)
	{
		return $grid->attributes(['align'=>'center', 'valign'=>'middle', 'width'=>$width]);
	}
}
