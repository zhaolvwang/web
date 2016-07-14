<?php 

namespace App\Http\Controllers;

use App\Demand;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;
use App\User;
use App\CoopManu;
use Illuminate\Support\Facades\Input;
use App\OrderInfo;
use App\Product;
use App\ProdQty;
use App\ProdOrderRel;
use App\Storehouse;
use Illuminate\Http\Response;
use App\PGBill;
use App\OrderDetails;
use App\App;
use App\DemandInfo;
use App\InvoiceInfo;

class OrderInfoController extends CrudController{

    public function all($entity) {
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        //$this->filter = \DataFilter::source(OrderInfo::with('user')->where('regBy', auth()->user()->id));
        if(auth()->user()->groupId == 0 || auth()->user()->groupId == 2) {
        	$orderInfo = OrderInfo::with('user', 'admin');
        }
        elseif (auth()->user()->groupId == 6) {
        	$orderInfo = OrderInfo::with('user', 'admin')->whereHas('admin', function ($query){
        		$query->where('regBy', auth()->user()->id);
        	});
        }
        elseif (auth()->user()->groupId == 14) {
        	$orderInfo = OrderInfo::with('user', 'admin')->where('regBy', auth()->user()->id);
        }
        $this->filter = \DataFilter::source($orderInfo);
        $this->addFilter('oinum');
        $this->addFilterSelect('oiStatus', new OrderInfo());
        $this->addFilter('created_at', 'created_at', 'daterange')->format('Y-m-d', 'en');
        $this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->addGrid('oinum');
        $this->addGrid('{{ $user->cmpy }}', 'cmpy');
        $this->addGrid('{{ $user->uname }}', 'uname');
        $this->addGrid('{{ $user->mobile }}', 'mobile');
        $this->addGrid('letterHeader');
        //$this->addGrid('payMode');
        $this->addGrid('oiStatus');
        $this->addGrid('created_at');
        $this->grid->add('contract1', trans('panel::fields.show').trans('panel::fields.contract'))->actions('all', explode('|', 'btn|'.trans('panel::fields.show').trans('panel::fields.contract')."|ContractInfo"));
        $this->grid->add('contract')->style('display:none');
        $this->grid->add('contractName')->style('display:none');
        $this->addGrid('pgbill')->actions('pgbill', explode('|', 'btn|'.trans('panel::fields.pgbill')."|".$entity));
        $this->addGrid('orderDetails')->actions('details', explode('|', 'btn|'.trans('panel::fields.show')."|".$entity));
        $this->addGrid('invoice')->actions('edit', explode('|', 'btn|'.trans('panel::fields.upload').trans('panel::fields.invoice')."|InvoiceInfo"));	//action.blade.php:action[4]
        //'&modify='.\App\InvoiceInfo::firstOrNew(['oiid'=>$id])->id
        $this->addGrid('paymentInfo')->actions('edit', explode('|', 'btn|'.trans('panel::fields.enter').trans('panel::fields.paymentInfo')."|PaymentInfo"));
        $this->addGrid('applied_at', 'applyInvoice');
        $this->addGrid('setOiStatus')->select('setOiStatus', new OrderInfo(), 'oiStatus');
        $this->grid->add('{{ $admin->first_name }}', '交易员');
        $this->grid->add('canceled_at')->style('display:none');
        
        $this->addStylesToGrid();
        $this->grid->row(function ($row) {
        	$row->cell('pgbill')->value->with('params', '&modify='.PGBill::firstOrNew(['oiid'=>$row->cell('id')->value])->id);
        	$row->cell('contract')->style('display:none');
        	$row->cell('contractName')->style('display:none');
        	$row->cell('canceled_at')->style('display:none');
        	$row->cell('contract1')->value->with('params', '&search=1&order_oinum='.$row->cell('oinum')->value);
        	$row->cell('invoice')->value->with('params', '&modify='.\App\InvoiceInfo::firstOrNew(['oiid'=>$row->cell('id')->value])->id);
        	if ((time() > strtotime($row->cell('canceled_at')->value)) && $row->cell('oiStatus')->value == '待付款') {
        		OrderInfo::findOrNew($row->cell('id')->value)->update(['oiStatus'=>'交易关闭']);
        		$row->cell('oiStatus')->value = '交易关闭';
        	}
        	if($row->cell('applied_at')->value) {
        		if(InvoiceInfo::firstOrNew(['oiid'=>$row->cell('id')->value])->exists) {
        			$row->cell('applied_at')->value = '已开票';
        		}
        		else {
        			$row->cell('applied_at')->value = '申请开票';
        		}
        	}
        	else {
        		$row->cell('applied_at')->value = '未开票';
        	}
        });
        return $this->returnView(null, null, 'show|modify');
    }
    
    public function edit($entity) {
    	$this->edit = \DataEdit::source(new OrderInfo());
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	//dd(session('shopcart_admin'));
        $this->initEntity(trans('panel::fields.order'));
        
        if (isset($_SERVER['HTTP_REFERER'])) {
        	if (stripos($_SERVER['HTTP_REFERER'], 'OrderInfo/all') !== false) {		//新建和修改订单时，清空要用的session
        		session(['shopcart_admin'=>[]]);
        		session(['admin_order_info_modify'=>'']);	//编辑订单时，添加完产品后返回的modify参数
        		session(['order_uid'=>'']);
        		session(['letterHeader'=>'']);
        		session(['order_mobile'=>'']);
        		session(['order_diid'=>'']);
        	}
        }
        
        
        if ($this->edit->status == 'modify' || $this->edit->status == 'show')
        {
        	if ($this->edit->status == 'modify') {
        		session(['admin_order_info_modify'=>Input::get('modify')]);
        	}
        	$this->addEdit('oinum')->readonly();
        	$this->addEditSelect('oiStatus', new OrderInfo());
        	session(['shopcart_admin_oid'=>$this->model->id]);
        }
        elseif ($this->edit->status == 'create') {
        	$this->addEdit('oinum1', 'text', 'oinum')->placeholder(trans('panel::fields.sysAutoCreate'))->readonly();
        	session(['shopcart_admin_oid'=>'']);
        }

        /* $users = array(''=>"");
        foreach (User::all() as $v)
        {
        	$users[$v->id] = $v->cmpy." - ".$v->uname." - ".$v->mobile;
        }
        
        $this->edit->model->uid = session('order_uid') ? session('order_uid') : $this->edit->model->uid;
        $this->addEdit('uid', 'select', 'user')->options($users)->attributes(['class'=>'s2example'])->rule('required'); */
        //dd(session('order_mobile'));
        $mobileInput = $this->addEdit('mobile')->rule('required')->insertValue(session('order_mobile'));  
        if ($this->edit->status != 'create') { 
        	$mobileInput->allValue(User::find($this->edit->model->uid)->mobile)->readonly();
        }
        
        /* 
         * 订单绑定需求单
         *
         */
        $demandInfo = array(''=>"");
        $demandInfoData = DemandInfo::where('verifyStatus', '审核通过')->where('grabBy', auth()->user()->id)->select('demand_info.id', 'demand_info.dinum')->get()->all();
        foreach ($demandInfoData as $v)
        {
        	if (!OrderInfo::firstOrNew(['diid'=>$v->id])->exists || $this->edit->model->diid == $v->id) {
        		$demandInfo[$v->id] = $v->dinum;
        	}
        }
        $this->edit->model->diid = session('order_diid') ? session('order_diid') : $this->edit->model->diid;
        $this->addEdit('diid', 'select', 'dinum')->options($demandInfo)->attributes(['class'=>'s2example'])->rule('required');
        
        
        $this->addEdit('regBy', 'hidden')->insertValue(auth()->user()->id);
        $this->addEdit('products.oiid', 'hidden', 'product');
        $this->addEditSelect('payMode', new OrderInfo())->rule('required');
        $this->addEdit('letterHeader')->insertValue(session('letterHeader'))->rule('required');
        //$this->addEditSelect('oiStatus', new OrderInfo());
        /*
         * 从添加产品页面跳转
         */
        $products = array();
        if (isset($_SERVER['HTTP_REFERER'])) {
        	if (stripos($_SERVER['HTTP_REFERER'], 'addProduct') !== false)
        	{
        		$storehouse = '';
        		foreach (session('shopcart_admin') as $k=>$v)
        		{
        			if (isset($v['storehouse']))
        			{
        				if (!$storehouse) {
        					$storehouse = $v['storehouse'];
        					continue;
        				}
        	
        				if ($storehouse != $v['storehouse']) {
        					return new Response('<script>alert("该订单中的货物在不相同的仓库中");window.history.go(-1);</script>');
        					break;
        				}
        			}
        		}
        		/*
        		 * 产品数据直接从session中获取
        		 */
        		$products = session('shopcart_admin');
        	}
        	else {
        		$pids = ProdOrderRel::where('oiid', $this->edit->model->id)->lists('pid', 'pqid')->all();
        		$products = array();
        		foreach ($pids as $k=>$v)
        		{
        			$product = Product::findOrNew($v);
        			$prodQty = ProdQty::findOrNew($k);
        			$prodOrderRel = ProdOrderRel::firstOrNew(['oiid'=>$this->edit->model->id, 'pqid'=>$k]);
        			if ($prodQty->exists) {
        				$products = array_add($products, $k, $prodOrderRel->toArray() + array(
        						'pnum'=>$product->pnum,
        						'batchNum'=>$product->batchNum,
        						'pname'=>$product->pname,
        						'standard'=>$this->getProdStandard($product->thickness, $product->width, $product->long),
        						'storehouse'=>Storehouse::find($prodQty->siid)->storehouse,
        						'oUnitPrc'=>$product->oUnitPrc,
        						'weight'=>$prodQty->weight."吨/".$product->unit,
        						'inputQty'=> $prodOrderRel->orderQty,
        						'receAmount'=> $prodOrderRel->receAmount,
        						'orderAmount'=> $prodOrderRel->orderAmount,
        						'actualQty'=> $prodOrderRel->actualQty,
        						'actualWeight'=> $prodOrderRel->actualWeight,
        				));
        			}
        			
        		}
        		if (count($products) == 0) {
        			$products = session('shopcart_admin');
        		}
        		else {
        			session(['shopcart_admin'=>$products]);
        		}
        	}
        }
        
        /*
         * 订单合同
         */
        if ($this->edit->status == 'modify') {
        	/*
        	 * 合同签订环节 要求 客户完善所有信息资料
        	 */
        	$orderUser = User::findOrNew($this->edit->model->uid);
        	if($orderUser->isPerfectUser()) {
        		$uploadUrl = 'public/uploads/contract/'.$this->edit->model->id;
        		$this->edit->add('contract', trans('panel::fields.contract').trans('panel::fields.upload'), 'file')
        		->rule('mimes:doc,docx,pdf,jpeg,jpg,png,JPEG,JPG,PNG')->move($uploadUrl, 'contract_'.md5($this->edit->model->id));
        		if (isset($_FILES['contract']['name'])) {
        			$contractName = explode(",", $_FILES['contract']['name']);
        			if ($contractName[0])
        				$this->edit->model->contractName = $contractName[0];
        		}
        		else {
        			$this->addEdit('contractName', 'hidden');
        		}
        	}
        	else {
        		$msg = '该采购商没有完善帐户信息（合同签订环节，需要客户完善所有帐户信息）';
        		$this->edit->add('qqqq', trans('panel::fields.contract').trans('panel::fields.upload'), 'text')
        		->insertValue($msg)->updateValue($msg)->attributes(['style'=>'color:red'])->readonly();
        	}
        	
        }
        /*
         * END
         */
        //dd($products);
        $sumAmount = 0;
        foreach ($products as $k=>$v) {
        	if (is_array($v)) {
        		$sumAmount += $v['orderAmount'];
        	}
        }
        $this->addEdit('orderTotalPrc', 'text', 'orderTotalPrc')->allValue("￥".$sumAmount)->readonly();
        if ($this->edit->status == 'modify' || $this->edit->status == 'show') {
        	$this->addEdit('isLocked', 'select')->options(['否', '是']);
        }
        $button = $this->addEdit('product', 'button', 'addProd')->options($products);
        if ($this->edit->model->isLocked) {
        	$button->attributes(['disabled'=>true]);
        }
        foreach ($products as $k=>$v)
        {
        	$this->edit->model->siid = ProdQty::find($k)->siid; 
        	$this->edit->model->unit = Product::find(ProdQty::find($k)->pid)->unit;
        	break;
        }
        /*
         * 生成订单编号
         */
        if (isset($_POST['oinum1']))
        {
        	if (Input::get('oinum1') == '')
        	{
        		$this->edit->model->oinum = ConstantTool::ORDER_NUM_PREFIX.date('ymdHis').rand(10, 99);
        		$this->edit->model->uid = User::firstOrNew(['mobile'=>$_POST['mobile']]);
        	}
        }
        
        return $this->returnEditView();
    }   

    public function addProduct($entity)
    {
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	
    	$this->filter = \DataFilter::source(ProdQty::with('product', 'storehouse')->whereNull('inid'));
    	$this->addFilter('product.pnum', 'pnum');
    	$this->addFilter('product.batchNum', 'batchNum');
    	$this->addFilter('product.pname', 'pname');
    	$this->addFilter('storehouse.storehouse', 'storehouse', 'select')->options(array(""=>trans('panel::fields.selectStorehouse')) + Storehouse::lists('storehouse', 'id')->all());
    	$this->commonSearch();
    	$this->filter->build();
    	 
    	$this->grid = \DataGrid::source($this->filter);
    	$this->addGrid('{{ $product->pnum }}','pnum');
    	$this->addGrid('{{ $product->batchNum }}','batchNum');
    	$this->addGrid('{{ $product->pname }}', 'pname');
    	$this->addGrid('prodStandard');
        //$this->filter->add('pnum', 'p', 'text');
        $this->grid->add('{{ $storehouse->storehouse }}',trans("panel::fields.storehouse"));
        $this->addGrid('{{ $product->coopid }}', 'coopManu');
        $this->addGrid('{{ $product->unit }}', 'unit');
        $this->addGrid('weight');
        $this->addGrid('unitWeight');
        $this->addGrid('{{ $product->oUnitPrc }}', 'oUnitPrc');
        $this->addGrid('qty');
        $this->grid->add('extantQty',trans("panel::fields.extantQty"));
        $this->addGrid('lockedQty');
        $this->addGrid('addProd')->style('width:150px;')->shopcart();
        $this->grid->add('id')->style("display:none;");
        $this->grid->add('pid')->style("display:none;");
        $this->grid->add('{{ $product->thickness }}')->style("display:none;");
        $this->grid->add('{{ $product->width }}')->style("display:none;");
        $this->grid->add('{{ $product->long }}')->style("display:none;");
        $this->grid->add('{{ $product->unit }}')->style("display:none;");
        $this->grid->add('weight')->style("display:none;");
        $this->grid->row(function ($row) {
        	$id = $row->cell('id')->value;;
        	$row->cell('id')->style("display:none");
        	$row->cell('pid')->style("display:none");
        	$row->cell('{{ $product->thickness }}')->style("display:none");
        	$row->cell('{{ $product->width }}')->style("display:none");
        	$row->cell('{{ $product->long }}')->style("display:none");
        	$row->cell('{{ $product->unit }}')->style("display:none");
        	$row->cell('weight')->style("display:none");
        	$row->cell('{{ $product->coopid }}')->value = CoopManu::find($row->cell('{{ $product->coopid }}')->value)->cname;
        	$row->cell('prodStandard')->value = $this->getProdStandard($row->cell('{{ $product->thickness }}')->value, $row->cell('{{ $product->width }}')->value, $row->cell('{{ $product->long }}')->value);
        	$row->cell('extantQty')->value = $row->cell('qty')->value - ProdQty::where('inid', $id)->sum('qty');
        	$row->cell('addProd')->value->with('id', $id);
        	$row->cell('unitWeight')->value = $row->cell('weight')->value.$row->cell('{{ $product->unit }}')->value.'/件';
        	$row->cell('qty')->attributes(['class'=>'qty']);
        	$row->cell('extantQty')->attributes(['class'=>'extantQty']);
        	$row->cell('lockedQty')->attributes(['class'=>'lockedQty']);
        	foreach (session('shopcart_admin') as $k=>$v)
        	{
        		if ($k == $id) {
        			$row->cell('lockedQty')->value -= ProdOrderRel::where('pqid', $id)->where('isPay', 0)->where('oiid', session('shopcart_admin_oid'))->sum('orderQty');
        			break;
        		}
        	}
        	//
        });
        
        if (Input::get('uid')) {
        	session(['order_uid'=>Input::get('uid')]);
        }
        if (Input::get('diid')) {
        	session(['order_diid'=>Input::get('diid')]);
        }
        if (Input::get('letterHeader')) {
        	session(['letterHeader'=>Input::get('letterHeader')]);
        }
        if (Input::get('mobile')) {
        	session(['order_mobile'=>Input::get('mobile')]); 
        }
        //dd(session('order_mobile'));
        
        $modify = session('admin_order_info_modify') ? "?modify=".session('admin_order_info_modify') : '';
        $this->grid->link('panel/'.$this->entity.'/edit'.$modify, trans('panel::fields.back'), "TR", ['class'=>'btn btn-default']);
        $this->grid->link('panel/'.$this->entity.'/edit'.$modify, trans('panel::fields.lockGoods'), "TR", ['class'=>'btn btn-primary']);

        $this->addStylesToGrid('pid', 20);
        return view('panelViews::all', array(
        		'grid' 	      => $this->grid,
        		'filter' 	     => $this->filter,
        		'title'          => $this->entity ,
        		'current_entity' => $this->entity,
        		'method' => $this->routeMethod,
        		'import_message' => (\Session::has('import_message')) ? \Session::get('import_message') : ''
        ));
    }
    
    /**
     * 编辑提货函
     * 
     * 提货函在订单新建的时候自动创建1个新的记录
     * 
     * @param String $entity
     */
    public function pgbill($entity)
    {
    	/* if (stripos($_SERVER['HTTP_REFERER'], 'OrderInfo/all') !== false)
    	{
    		return new Response('<script>window.location.href="/panel/'.$entity.'/pgbill?id='.$orderInfo->id.'&modify='..'";</script>');
    	} */
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	 /*
         * Input::get('id')----->order_info.id
         */
    	$orderInfo = OrderInfo::findOrNew(Input::get('id'));
    	$this->edit = \DataEdit::source(new PGBill());
    	//
    	//dd(2222);
    	$this->initEntity(trans('panel::fields.pgbill'));
    	$this->addEdit('oinum')->updateValue($orderInfo->oinum)->readonly();
    	$this->addEditSelect('pgbType', new PGBill());
    	$this->addEdit('voucher');
    	$this->addEdit('remarks', 'textarea');
    	return $this->returnEditView();
    }
    
    /**
     * 订单详情
     *
     * @param String $entity
     */
    public function details($entity)
    {
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	$this->filter = \DataFilter::source(OrderDetails::with('enterBy')->where('oiid', Input::get('id')));
    	//$this->addFilter('created_at', 'created_at', 'daterange')->format('Y-m-d', 'en');
    	//$this->commonSearch();
    	/*
    	 * Input::get('id')----->order_info.id
    	*/
    	$orderInfo = OrderInfo::findOrNew(Input::get('id'));
    	$this->grid = \DataGrid::source($this->filter);
    	$this->addGrid('details|strip_tags', 'orderDetails');
    	$this->addGrid('created_at');
    	$this->addGrid('{{ $enterBy->first_name }}', 'enterBy');
    	$this->addStylesToGrid('id');
    	$this->grid->link('panel/'.$this->entity.'/all', trans('panel::fields.back'), "TR", ['class'=>'btn btn-default']);
    	//$this->grid->link('panel/'.$this->entity.'/detailsEdit?oiid='.Input::get('id'), trans('panel::fields.add'), "TR", ['class'=>'btn btn-primary']);
    	//$this->grid->edit('detailsEdit', trans('panel::fields.edit'), 'modify');
    	$this->grid->row(function ($row) {
    		if (stripos($row->cell('{{ $enterBy->first_name }}')->value, "{{") !== false)
    		{
    			$row->cell('{{ $enterBy->first_name }}')->value = "";
    		}
    	});
        return view('panelViews::all', array(
        		'grid' 	      => $this->grid,
        		'filter' 	     => $this->filter,
        		'label_title'          => trans('panel::fields.orderDetails'),
        		'oinum'          => $orderInfo->oinum,
        		'current_entity' => $this->entity,
        		'method' => $this->routeMethod,
        		'import_message' => (\Session::has('import_message')) ? \Session::get('import_message') : ''
        ));
    }
    
    /**
     * 编辑or新建订单详情
     *
     * @param String $entity
     */
    public function detailsEdit($entity)
    {
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	$this->edit = \DataEdit::source(new OrderDetails());
    	$this->initEntity(trans('panel::fields.orderDetails')); 
    	$this->addEdit('details', 'redactor', 'orderDetails');
    	$this->addEdit('oiid', 'hidden')->insertValue($this->model->oiid ? $this->model->oiid : Input::get('oiid'));
    	$this->addEdit('regBy', 'hidden')->insertValue($this->model->regBy ? $this->model->regBy : auth()->user()->id);
    	return $this->returnEditView(null, 'details?id='.($this->edit->model->oiid ? $this->edit->model->oiid : Input::get('oiid')));
    }
    
    /**
     * 设置订单状态
     *
     * @param String $entity
     */
    public function setOiStatus($entity)
    {
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	/*
    	 * Input::get('id')----->order_info.id
    	*/
    	//dd(Input::all());
    	if (Input::get('oiStatus') == '待付款') {
    		OrderInfo::findOrNew(Input::get('id'))->update(['oiStatus'=>Input::get('oiStatus'), 'canceled_at'=>date('Y-m-d H:i:s', strtotime(ConstantTool::CANCEL_ORDER_TIME, time()))]);
    	}
    	else {
    		OrderInfo::findOrNew(Input::get('id'))->update(['oiStatus'=>Input::get('oiStatus')]);
    	}
    	
    	return redirect('panel/'.$entity.'/all?'.http_build_query(Input::all()));
    }
    
    
}
