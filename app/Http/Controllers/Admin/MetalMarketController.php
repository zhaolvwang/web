<?php 

namespace App\Http\Controllers;

use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\MetalMarket;

class MetalMarketController extends CrudController{

    public function all($entity) {
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        $this->filter = \DataFilter::source(new MetalMarket());
        $this->addFilter('pname', 'name');
        $this->addFilter('writer');
        //$this->addFilter('created_at', 'upload_at', 'daterange')->format('Y-m-d', 'en');
        $this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->addGrid('pname', 'name');
        $this->addGrid('topPrc');
        $this->addGrid('lowPrc');
        $this->addGrid('topPrc1');
        $this->addGrid('avgPrc');
        $this->addGrid('upDown');
        //$this->addGrid('avgPrc3');
        $this->addGrid('avgPrc5');
        $this->addGrid('dt');
        $this->addGrid('upDownType')->style('display:none');
        $this->addStylesToGrid();
        $this->grid->row(function ($row) {
        	$row->cell('upDownType')->style('display:none');
        	if ($row->cell('upDownType')->value) {
        		$row->cell('upDown')->value = "+".$row->cell('topPrc')->value;
        	}
        	else {
        		$row->cell('upDown')->value = "-".$row->cell('topPrc')->value;
        	}
        	if (!$row->cell('lowPrc')->value) {
        		$row->cell('topPrc')->value = "";
        	}
        		
        });
        return $this->returnView(null, null, 'modify');
    }
    
    public function edit($entity) {
    	$this->edit = \DataEdit::source(new MetalMarket());
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->initEntity(trans('panel::fields.metalMarket'));
        $this->addEdit('pname', 'text', 'name')->rule('required');
        if ($this->edit->model->lowPrc) {
        	$this->addDecimalFormat('topPrc');
        	$this->addDecimalFormat('lowPrc');
        }
        else {
        	$this->addEdit('topPrc1');
        }
        $this->addDecimalFormat('avgPrc');
        $this->addEdit('upDownType', 'select')->options(['跌', "涨"]);
        $this->addDecimalFormat('upDown');
        if (!$this->edit->model->lowPrc) {
        	//$this->addDecimalFormat('avgPrc3');
        	$this->addDecimalFormat('avgPrc5');
        }
        $this->addEdit('dt', 'date')->format('Y-m-d', 'zh-CN');
        
        return $this->returnEditView();
    }    
}
