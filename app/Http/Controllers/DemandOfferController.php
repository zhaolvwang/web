<?php 

namespace App\Http\Controllers;

use App\Demand;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;
use App\User;
use App\CoopManu;
use Illuminate\Support\Facades\Input;
use App\DemandOffer;
use App\OrderInfo;
use App\DemandInfo;

class DemandOfferController extends CrudController{

    public function all($entity){
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        $this->filter = \DataFilter::source(DemandInfo::with('user')->where('relTms', 3)->where('diStatus', '释放'));
        $this->addFilter('dinum', 'demandNum');
        $this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->addGrid('dinum');
        $this->addGrid('variety');
        $this->addGrid( 'alloyNum');
        $this->addGrid('processStatus');
        $this->addGrid('standard');
        $this->addGrid('intentPrc');
        $this->addGrid('disMode');
        $this->grid->add('province')->style('display:none');
        $this->grid->add('county')->style('display:none');
        $this->grid->add('city', '发货地');
        $this->addGrid('freight');
        $this->addGrid('payDays');
        $this->addGrid('deliveryDt');
        $this->grid->add('updated_at', '更新时间');
        $this->grid->add('offerCount', '目前报价数');
        $this->grid->add('checkOffer', '查看报价')->actions('offer',  explode('|', 'btn|'.trans('panel::fields.show')));
        $this->grid->row(function ($row) {
        	$row->cell('province')->style('display:none');
        	$row->cell('county')->style('display:none');
        	if ($row->cell('province')->value == $row->cell('city')->value) {
        		$row->cell('city')->value = $row->cell('city')->value.$row->cell('county')->value;
        	}
        	else {
        		$row->cell('city')->value = $row->cell('province')->value.$row->cell('city')->value.$row->cell('county')->value;
        	}
        	$row->cell('offerCount')->value = DemandOffer::where('diid', $row->cell('id')->value)->count();
        	if($row->cell('offerCount')->value == 0) {
        		$row->cell('checkOffer')->value->with('name', 'disabled');
        	}
        	//$row->cell('offerPrc')->value = "￥".$row->cell('offerPrc')->value;
        	//$row->cell('offerAmount')->value = "￥".$row->cell('offerAmount')->value;
        });
        $this->addStylesToGrid();
        return $this->returnView(null, true, '');
    }
    
    public function offer($entity){
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        /*
         * Input::get('id')--->demand_info.id
         */
        $this->filter = \DataFilter::source(DemandOffer::with('user', 'demand')->where('diid', Input::get('id')));
        //$this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->grid->add('{{ $user->cmpy }}', '报价人公司');
        $this->grid->add('{{ $user->uname }}', '报价人');
        $this->grid->add('{{ $user->mobile }}', '报价人电话');
        $this->grid->add('offerPrc', '采购价格（元/吨）');
        $this->grid->add('offerQty', '采购数量（吨）');
        $this->addGrid('{{ $demand->disMode }}', 'disMode');
        $this->grid->add('province')->style('display:none');
        $this->grid->add('county')->style('display:none');
        $this->grid->add('city', '收货地');
        $this->grid->add('logFee', '运费/吨');
        $this->grid->add('offerAmount', '采购总价（元）');
        $this->grid->add('created_at', '报价时间');
        $this->grid->row(function ($row) {
        	$row->cell('province')->style('display:none');
        	$row->cell('county')->style('display:none');
        	if ($row->cell('province')->value == $row->cell('city')->value) {
        		$row->cell('city')->value = $row->cell('city')->value.$row->cell('county')->value;
        	}
        	else {
        		$row->cell('city')->value = $row->cell('province')->value.$row->cell('city')->value.$row->cell('county')->value;
        	}
        	$row->cell('offerPrc')->value = "￥".$row->cell('offerPrc')->value;
        	$row->cell('offerAmount')->value = "￥".$row->cell('offerAmount')->value;
        });
        $this->addStylesToGrid();
        $this->grid->link('panel/'.$this->entity.'/all', trans('panel::fields.back'), "TR", ['class'=>'btn btn-default']);
        $this->grid->link('panel/'.$this->entity.'/edit', trans('panel::fields.add'), "TR", ['class'=>'btn btn-primary']);
        $this->grid->edit('edit', trans('panel::fields.edit'), 'modify|delete');
        $demandInfo = DemandInfo::findOrNew(Input::get('id'));
        $msg = '<font style="font-size:16px;">采购单：'.$demandInfo->dinum.'<br>产品： '.$demandInfo->variety.'&nbsp;'.$demandInfo->alloyNum.'&nbsp;'.$demandInfo->processStatus.'&nbsp;'.$demandInfo->standard.'&nbsp;</font>';
        return view('panelViews::all', array(
        		'grid' 	      => $this->grid,
        		'title'          => $this->entity ,
        		'label_title'          => '采购报价',
        		'current_entity' => $this->entity,
        		'method' => $this->routeMethod,
        		'import_message' => $msg,
        ));
    }
    
    public function edit($entity) {
    	$this->edit = \DataEdit::source(new DemandOffer());
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->initEntity(trans('panel::fields.demandOffer'));
       	//dd(Input::all());
        //$demandInfo = DemandInfo::findOrNew($this->model->diid);
        $this->addEdit('province1', 'hidden')->updateValue($this->edit->model->province);
        $this->addEdit('city1', 'hidden')->updateValue($this->edit->model->city);
        $this->addEdit('county1', 'hidden')->updateValue($this->edit->model->county);
        if ($this->edit->status == 'create') {
        	$this->addEditSelect2('diid', \App\DemandInfo::whereNotIn('id', \App\Demandoffer::lists('diid')->all())->lists('dinum', 'id'), 'dinum')->rule('required');
        	$users = array(''=>"");
	        foreach (\App\User::whereNotIn('id', \App\Demandoffer::lists('uid')->all())->get()->all() as $v)
	        {
	        	$users[$v->id] = $v->cmpy." - ".$v->uname." - ".$v->mobile;
	        }
	        
	        $this->addEdit('uid', 'select', 'user')->options($users)->attributes(['class'=>'s2example'])->rule('required');
	        
        }
        else {
        	$this->addEdit('demand.dinum', 'text', 'dinum')->readonly();
        	$this->addEdit('demand.variety', 'text', 'variety')->readonly();
        	$this->addEdit('demand.alloyNum', 'text', 'alloyNum')->readonly();
        	$this->addEdit('demand.processStatus', 'text', 'processStatus')->readonly();
        	$this->addEdit('demand.standard', 'text', 'standard')->readonly();
        	$this->addEdit('demand.disMode', 'text', 'disMode')->readonly();
        }
        $this->addDecimalFormat('offerPrc')->attributes(['onblur'=>'offerAmountFnc()', 'id'=>'offerPrc'])->rule('required')->unit = trans('panel::fields.yuan_ton');
        $this->addDecimalFormat('offerQty')->attributes(['onblur'=>'offerAmountFnc()', 'id'=>'offerQty'])->rule('required')->unit = trans('panel::fields.ton');
        $this->addDecimalFormat('logFee', 'freight')->attributes(['onblur'=>'offerAmountFnc()', 'id'=>'logFee'])->unit = trans('panel::fields.yuan_ton');
        
        /*
         * ForeingKey不被覆盖
         */
        $this->addEdit('demand.id', 'hidden');
        
        $this->addDecimalFormat('offerAmount')->attributes(['id'=>'offerAmount'])->readonly()->unit = trans('panel::fields.yuan');
        $this->addEdit('county', 'select', 'deliPlace')->rule('required');
        if (Input::get('county') != '')
        {
        	$this->edit->model->city = Input::get('city');
        	$this->edit->model->county = Input::get('county');
        }
        return $this->returnEditView();
    }    
}
