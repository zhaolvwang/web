<?php
namespace App\Http\Controllers;

use Serverfireteam\Panel\CrudController;
use App\Product;
use App\ProdQty;
use App\MyFnc\Permission;
use App\App;

class ProductStatusController extends CrudController {

    public function all($entity) {
    	if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
    	
        $this->filter = \DataFilter::source(ProdQty::with("product", 'inBy')->where('stockStatus', '入库')->whereNull('inid'));
    	$this->filter->add('product.pnum', trans('panel::fields.pnum'), 'text');
        $this->filter->add('product.batchNum', trans('panel::fields.batchNum'), 'text');
        $this->filter->add('product.pname', trans('panel::fields.pname'), 'text');
        $this->addFilterSelect('stockStatus', new ProdQty());
        $this->addFilterSelect('saleStatus', new ProdQty());
    	$this->commonSearch();
    	
    	$this->grid = \DataGrid::source($this->filter);
        $this->grid->add('{{ $product->pnum }}', trans('panel::fields.pnum'), true);
        $this->grid->add('{{ $product->batchNum }}', trans('panel::fields.batchNum'), true);
        $this->grid->add('{{ $product->pname }}', trans('panel::fields.pname'));
        $this->addGrid('weight');
        $this->addGrid('qty', 'inQty');
        $this->addGrid('wOutQty');
        $this->addGrid('aOutQty');
        $this->addGrid('lockedQty');
    	//$this->addGrid('stockStatus');
    	$this->addGrid('saleStatus');
    	$this->addGrid('{{ $product->unit }}')->style('display:none');
    	$this->addStylesToGrid();
    	$this->grid->row(function ($row) {
    		$row->cell('{{ $product->unit }}')->style("display:none");
    		$row->cell('weight')->value = $row->cell('weight')->value.$row->cell('{{ $product->unit }}')->value.'/件';
    		$row->cell('wOutQty')->value = ProdQty::where('stockStatus', '待出库')->where('inid', $row->cell('id')->value)->sum('qty');
    		$row->cell('aOutQty')->value = ProdQty::where('stockStatus', '已出库')->where('inid', $row->cell('id')->value)->sum('qty');
    	});
        return $this->returnView(null, false, 'modify');
    }

    public function edit($entity) {
        $this->edit = \DataEdit::source(new ProdQty());
        if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->initEntity(trans('panel::fields.product').trans("panel::fields.status"));
        $product = Product::find($this->edit->model->pid);
		$this->productCommonQty($product);
		$this->addEdit('weight')->readonly();
		if ($this->edit->model->inid)
		{
			$this->edit->add('qty', trans('panel::fields.outQty'), 'text')->readonly();
			//$this->addEdit('stockStatus', 'select')->options(['待出库'=>'待出库', '已出库'=>'已出库']);
		}
		else {
			$this->addEdit('qty')->readonly();
			//$this->addEdit('stockStatus', 'select')->options(['入库'=>'入库']);
		}
		$this->addEditSelect('saleStatus', new ProdQty());
        return $this->returnEditView();
    }
}
