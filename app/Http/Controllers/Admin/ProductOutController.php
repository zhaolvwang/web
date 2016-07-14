<?php
namespace App\Http\Controllers;

use Serverfireteam\Panel\CrudController;
use App\Product;
use Illuminate\Support\Facades\Input;
use App\ProdQty;
use App\Storehouse;
use App\MyFnc\Permission;
use Illuminate\Http\Request;

class ProductOutController extends CrudController {

	public function all($entity) {
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        /*
         * Input::get('id')----->product_qty.id:产品入库的id，即inid
         * Input::get('pid')----->product_qty.pid:产品的id，即product_info.id
         */
        $qid = Input::get('id');
        $pid = Input::get('pid');
        
        $this->filter = \DataFilter::source(ProdQty::with("product", 'outBy', 'storehouse')->where('stockStatus', '!=', '入库')->whereRaw("(''='$qid' OR inid='$qid')")->whereRaw("(''='$pid' OR pid='$pid')"));
        $this->filter->add('product.pnum', trans('panel::fields.pnum'), 'text');
        $this->filter->add('product.batchNum', trans('panel::fields.batchNum'), 'text');
        $this->filter->add('product.pname', trans('panel::fields.pname'), 'text');
        $this->filter->add('outBy.first_name', trans('panel::fields.outBy'), 'text');
        $this->addFilter('outRegDt', null, 'daterange')->format('Y-m-d', 'en');
    	$this->commonSearch();
    	
    	$this->grid = \DataGrid::source($this->filter);
        $this->grid->add('{{ $product->pnum }}', trans('panel::fields.pnum'));
        $this->grid->add('{{ $product->batchNum }}', trans('panel::fields.batchNum'));
        $this->grid->add('{{ $product->pname }}', trans('panel::fields.pname'));
        $this->grid->add('{{ $storehouse->storehouse }}', trans('panel::fields.storehouse'));
    	$this->addGrid('weight');
    	 $this->grid->add('qty', trans("panel::fields.outQty"));
        $this->grid->add('{{ $outBy->first_name }}',trans("panel::fields.outBy"));
        $this->addGrid('enterOutBy');
        $this->addGrid('stockStatus');
        //$this->grid->add('outRegDt|substr[0,10]',trans("panel::fields.outRegDt"), true);
        $this->grid->add('outRegDt',trans("panel::fields.outRegDt"), true);
    	$this->addStylesToGrid();
        return $this->returnView(null, null, 'modify|delete');
    }

    public function edit($entity) {
    	$this->edit = \DataEdit::source(new ProdQty());
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->productInOutEdit('out');
        $product = Product::find($this->edit->model->pid);
        $qty = ProdQty::find($this->edit->model->inid)->qty;//入库数量
        $outQty = ProdQty::where('inid', $this->edit->model->inid)->where('id', '!=', $this->edit->model->id)->sum('qty');	//除了修改这条数据外，其他已出库数量
        
        $this->productCommonQty($product);
        $this->addEdit('weight')->readonly();
        
        $extantQty = $qty - $outQty;	//现存数量
        $this->edit->add('qty1', trans("panel::fields.qty"), 'text')->updateValue($qty)->readonly();
        $this->addEdit('extantQty')->updateValue($extantQty)->readonly();
        
        /*
         * 出库记录需要绑定订单号
         */
        $this->addEditSelect2('oiid', \App\OrderInfo::lists('oinum', 'id'), 'oinum')->rule('required');
        $this->addIngeterFormat('qty', 'outQty')->rule("ltoe:$extantQty");
        if ($this->edit->model->inid)
        {
        	$this->addEdit('stockStatus', 'select')->options(['待出库'=>'待出库', '已出库'=>'已出库']);
        }
        $this->edit->link('panel/'.$this->entity.'/all',trans("panel::fields.back"), "TR");
        return $this->edit->view('panelViews::edit', array(
        		'title'          => $this->entity ,
        		'edit' => $this->edit,
        ));
    }
    
    public function out($entity) {
    	$this->edit = \DataEdit::source(new ProdQty());
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	$this->productInOutEdit('out');
    	 
    	/*
    	 * Input::get('id')----->product_qty.id
    	 */
    	$prodQtyModel = ProdQty::find(Input::get('id'));
    	$qty = $prodQtyModel->qty;	//入库数量
    	$outQty = ProdQty::where('inid', Input::get('id'))->sum('qty');	//已出库数量
    	$weight = $prodQtyModel->weight;
    	$pid = $prodQtyModel->pid;
    	$product = Product::find($pid);
    	
    	$this->addEdit('pid', 'hidden')->allValue($pid);
    	$this->addEdit('inid', 'hidden')->allValue(Input::get('id'));
    	$this->productCommonQty($product);
    	$this->addEdit('weight')->allValue($weight)->readonly();
    	
    	$this->edit->add('qty1', trans("panel::fields.qty"), 'text')->allValue($qty)->readonly();
    	
    	/*
    	 * 出库记录需要绑定订单号
    	 */
    	$this->addEditSelect2('oiid', \App\OrderInfo::lists('oinum', 'id'), 'oinum')->rule('required');
    	
    	$extantQty = $qty - $outQty;	//现存数量
    	$this->addEdit('extantQty')->allValue($extantQty)->readonly();
    	$this->addIngeterFormat('qty', 'outQty')->rule("ltoe:$extantQty|required|numeric|min:1");
    	$this->addEdit('enterOutBy');
    	
    	if (Input::get('qty') != '') {
    		$this->edit->model->outRegDt = date('Y-m-d H:i:s');
    		$this->edit->model->outBy = auth()->user()->id;
    		$this->edit->model->stockStatus = '待出库';
    	}
    	if ($this->edit->status == 'create')
    		$this->edit->submit(trans('panel::fields.outStorage'), 'BL');
    	$this->edit->link('panel/'.$this->entity.'/all',trans("panel::fields.back"), "TR");
    	return $this->edit->view('panelViews::edit', array(
    			'title'          => $this->entity ,
    			'edit' => $this->edit,
    	));
    }
}
