<?php
namespace App\Http\Controllers;

use Serverfireteam\Panel\CrudController;
use App\Product;
use App\ProdQty;
use App\Storehouse;
use Illuminate\Support\Facades\Input;
use App\MyFnc\Permission;
use App\ProdOrderRel;

class ProductInController extends CrudController {

    public function all($entity) {
    	if(!parent::all($entity)) return view('panelViews::hasNoAuthority');

        /*
         * Input::get('id')----->product_info.id
         */
        $pid = Input::get('id');
        
        $this->filter = \DataFilter::source(ProdQty::with("product", 'inBy', 'storehouse')->where('stockStatus', '=', '入库')->whereRaw("(''='$pid' OR pid='$pid')"));
        $this->filter->add('product.pnum', trans('panel::fields.pnum'), 'text');
        $this->filter->add('product.batchNum', trans('panel::fields.batchNum'), 'text');
        $this->filter->add('product.pname', trans('panel::fields.pname'), 'text');
        $this->filter->add('inBy.first_name', trans('panel::fields.inBy'), 'text');
        $this->addFilter('inRegDt', null, 'daterange')->format('Y-m-d', 'en');
    	$this->commonSearch();
    	
    	$this->grid = \DataGrid::source($this->filter);
        $this->grid->add('{{ $product->pnum }}', trans('panel::fields.pnum'));
        $this->grid->add('{{ $product->batchNum }}', trans('panel::fields.batchNum'));
        $this->grid->add('{{ $product->pname }}', trans('panel::fields.pname'));
        $this->grid->add('{{ $storehouse->storehouse }}', trans('panel::fields.storehouse'));
    	$this->addGrid('weight');
    	$this->addGrid('qty');
    	$this->addGrid('extantQty');
    	$this->addGrid('outQty');
        $this->grid->add('{{ $inBy->first_name }}',trans("panel::fields.inBy"));
        $this->addGrid('enterInBy');
        //$this->grid->add('inRegDt|substr[0,10]',trans("panel::fields.inRegDt"), true);
        $this->grid->add('inRegDt',trans("panel::fields.inRegDt"), true);
        $this->addGrid('outStorage')->actions('out', explode('|', 'btn|'.trans('panel::fields.outStorage')."|ProductOut"));
        $this->addGrid('outStorageRecord')->actions('all', explode('|', 'btn|'.trans('panel::fields.outStorageRecord')."|ProductOut"));
    	$this->addStylesToGrid();
    	$this->grid->edit('edit', trans('panel::fields.edit'), 'modify|delete');
    	$this->grid->row(function ($row) {
    		$row->cell('outQty')->value = ProdQty::where("inid", $row->cell('id')->value)->where('pid', $row->cell('pid')->value)->sum('qty');
    		$row->cell('extantQty')->value = $row->cell('qty')->value - ProdQty::where("inid", $row->cell('id')->value)->where('pid', $row->cell('pid')->value)->sum('qty');
    		if ($row->cell('extantQty')->value == 0)
    		{
    			$row->cell('outStorage')->value->with('name', 'disabled');
    		}
    		if (ProdOrderRel::firstOrNew(['pqid'=>$row->cell('id')->value])->exists || ProdQty::firstOrNew(['inid'=>$row->cell('id')->value])->exists) {
    			$row->cell('_edit')->value->with('actions', ['modify']);
    		}
    	});
        return $this->returnView(null, true, 'modify|delete');
    }

    public function edit($entity) {
    	$this->edit = \DataEdit::source(new ProdQty());
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->productInOutEdit('in');
        //dd($this->edit->model->pid);
       	$product = Product::find($this->edit->model->pid);
       	if ($product) {
       		$this->productCommonQty($product);
       	}
    	
    	$this->addDecimalFormat('weight');
        $this->addIngeterFormat('qty')->onchange("changeQty(this, ".ProdQty::where('pid', $this->edit->model->pid)->whereNotNull('inid')->sum('qty').", {$this->edit->model->qty})");
        
        $this->edit->link('panel/'.$this->entity.'/all',trans("panel::fields.back"), "TR");
        return $this->edit->view('panelViews::edit', array(
        		'title'          => $this->entity ,
        		'edit' => $this->edit,
        ));
    }
    
    public function in($entity) {
    	$this->edit = \DataEdit::source(new ProdQty());
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	$this->edit->label(trans("panel::fields.product").trans("panel::fields.inStorage"));
    	
    	/*
    	 * Input::get('id')----->product_info.id
    	 */
    	$product = Product::find(Input::get('id'));
    	
    	$this->addEdit('pid', 'hidden')->insertValue(Input::get('id'));
    	$this->productCommonQty($product);
    	$this->addDecimalFormat('weight');
    	$this->addIngeterFormat('qty');
    	if (Input::get('qty') != '') {
        	$this->edit->model->inRegDt = date('Y-m-d H:i:s');
        	$this->edit->model->inBy = auth()->user()->id;
        	if ($this->edit->status == 'create') {
        		$this->edit->model->lockedQty = 0;
        		$product->qty = ProdQty::where('pid', $product->id)->whereNull('inid')->sum('qty') + Input::get('qty');
        		$product->extantQty = $product->qty;
        		$product->save();
        	}
        	
        }
        if ($this->edit->status == 'modify')
    		$this->edit->submit(trans('panel::fields.save'), 'BL');
        if ($this->edit->status == 'create')
    		$this->edit->submit(trans('panel::fields.inStorage'), 'BL');
    	$this->edit->link('panel/'.$this->entity.'/all',trans("panel::fields.back"), "TR");
    	return $this->edit->view('panelViews::edit', array(
    			'title'          => $this->entity ,
    			'edit' => $this->edit,
    	));
    }
}
