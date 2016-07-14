<?php 

namespace App\Http\Controllers;

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
use App\DemandInfo;
use App\InvoiceInfo;

class OrderDetailsController extends CrudController{

    public function all($entity){
        if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	$this->filter = \DataFilter::source(null);
    	$this->filter->attributes(['url'=>url('panel/'.$entity.'/details')]);
    	$this->filter->add('oinum', '请输入完整的订单编号')->attributes(['style'=>'width:300px;']);
    	$this->filter->submit(trans('panel::fields.search'));
    	$this->filter->build();
    	
        return view('panelViews::all', array(
        		'grid' 	      => $this->grid,
        		'filter' 	     => $this->filter,
        		'label_title'          => trans('panel::fields.show').trans('panel::fields.orderDetails'),
        		'current_entity' => $this->entity,
        		'method' => $this->routeMethod,
        		'import_message' => (\Session::has('import_message')) ? \Session::get('import_message') : ''
        ));
    }
    
   
    
    /**
     * 订单详情
     *
     * @param String $entity
     */
    public function details($entity)
    {
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	$this->filter = \DataFilter::source(OrderDetails::with('enterBy')->whereHas('orderInfo', function ($query){
    		$query->where('oinum', Input::get('oinum'));
    	}));
    	//$this->addFilter('created_at', 'created_at', 'daterange')->format('Y-m-d', 'en');
    	//$this->commonSearch();
    	$orderInfo = OrderInfo::firstOrNew(['oinum'=>Input::get('oinum')]);
    	$this->grid = \DataGrid::source($this->filter);
    	$this->addGrid('details|strip_tags', 'orderDetails');
    	$this->addGrid('created_at');
    	$this->addGrid('{{ $enterBy->first_name }}', 'enterBy');
    	$this->addGrid('isSys')->style('display:none');
    	$this->addStylesToGrid('id');
    	$this->grid->link('panel/'.$this->entity.'/all', trans('panel::fields.back'), "TR", ['class'=>'btn btn-default']);
    	if ($orderInfo->exists)
    		$this->grid->link('panel/'.$this->entity.'/detailsEdit?oiid='.$orderInfo->id, trans('panel::fields.add'), "TR", ['class'=>'btn btn-primary']);
    	$this->grid->edit('detailsEdit', trans('panel::fields.edit'), 'modify');
    	$this->grid->row(function ($row) {
    		$row->cell('isSys')->style('display:none');
    		if (stripos($row->cell('{{ $enterBy->first_name }}')->value, "{{") !== false)
    		{
    			$row->cell('{{ $enterBy->first_name }}')->value = "";
    		}
    		if ($row->cell('isSys')->value == 1) {
    			$row->cell('_edit')->value = '';
    		}
    	});
        return view('panelViews::all', array(
        		'grid' 	      => $this->grid,
        		'filter' 	     => $this->filter,
        		'label_title'          => trans('panel::fields.orderDetails'),
        		'orderInfo'          => $orderInfo,
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
    	$this->edit = \DataEdit::source(new OrderDetails());
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	$this->initEntity(trans('panel::fields.orderDetails')); 
    	$this->addEdit('details', 'redactor', 'orderDetails');
    	$this->addEdit('oiid', 'hidden')->insertValue($this->model->oiid ? $this->model->oiid : Input::get('oiid'));
    	$this->addEdit('regBy', 'hidden')->insertValue($this->model->regBy ? $this->model->regBy : auth()->user()->id);
    	$oiid = $this->edit->model->oiid ? $this->edit->model->oiid : Input::get('oiid');
    	$orderInfo = OrderInfo::findOrNew($oiid);
    	if ($this->edit->status == 'create') {
    		$this->edit->model->isSys = 0;
    	}
    	return $this->returnEditView(null, 'details?oinum='.$orderInfo->oinum);
    }
    
    
}
