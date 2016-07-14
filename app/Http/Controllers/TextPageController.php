<?php 

namespace App\Http\Controllers;

use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\TextPage;

class TextPageController extends CrudController{

    public function all($entity){
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        $this->filter = \DataFilter::source(TextPage::with('regByAdmin', 'pColumn'));
        $this->addFilter('title');
        $this->addFilter('pColumn.title', 'pColumn');
        $layer = TextPage::groupby('layer')->get()->lists('id','layer')->all();
        foreach ($layer as $k=>&$v) {
        	$v = ($k == 1 ? '顶级栏目' : $k.'级栏目');
        }
        $this->filter->add('layer', trans('panel::fields.columnType'), 'select')->options(array(""=>'请选择'.trans('panel::fields.columnType')) + $layer);
        //$this->addFilter('created_at', 'upload_at', 'daterange')->format('Y-m-d', 'en');
        $this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->addGrid('title');
        $this->addGrid('pColumn');
        $this->addGrid('columnType');
        $this->addGrid('{{ $regByAdmin->first_name }}', 'admin');
        $this->addGrid('created_at');
        $this->addGrid('pid')->style('display:none');
        $this->addGrid('layer', '')->style('display:none');
        $this->addStylesToGrid();
        $this->grid->row(function ($row) {
        	$row->cell('pid')->style('display:none');
        	$row->cell('layer')->style('display:none');
        	$row->cell('pColumn')->value = $row->cell('pid')->value ? TextPage::findOrNew($row->cell('pid')->value)->title : '-';
        	$row->cell('columnType')->value = $row->cell('layer')->value == 1 ? '顶级栏目' : $row->cell('layer')->value.'级栏目';
        	if($row->cell('layer')->value == 0)
        		$row->cell('columnType')->value = '-';
        });
        return $this->returnView(null, null, 'modify|delete');
    }
    
    public function edit($entity) {
    	$this->edit = \DataEdit::source(new TextPage());
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	
        $this->initEntity(trans('panel::fields.textPage'));
        $alloyNum = array(''=>"");
        foreach (TextPage::lists('title', 'id')->all() as $k=>$v)
        {
        	$alloyNum[$k] = $v;
        }
        $this->addEdit('title');
        $this->edit->add('type', '是否栏目', 'select')->options(['是', '否']);
        $this->addEdit('pid', 'select', 'pColumn')->options($alloyNum)->attributes(['class'=>'s2example']);
        if ($this->edit->model->id == 26) {
        	$this->addEdit('content', 'redactor2')->attributes(['style'=>'height:800px']);
        }
        else {
        	$this->addEdit('content', 'redactor')->attributes(['style'=>'height:800px']);
        }
        
        $this->addEdit('regBy', 'hidden')->insertValue(auth()->user()->id);
        if (isset($_POST['pid']))
        {
        	if (Input::get('pid') != '') {
        		$pLayer = TextPage::findOrNew(Input::get('pid'))->layer;
        		$this->edit->model->layer =  $pLayer+ 1;
        		if ($pLayer == 1) {
        			$this->edit->model->topid = TextPage::findOrNew(Input::get('pid'))->id;
        		}
        		else {
        			$this->edit->model->topid = TextPage::findOrNew(Input::get('pid'))->topid;
        		}
        	}
        	else {
        		if (Input::get('type') == 1) {
        			$this->edit->model->layer = 0;
        		}
        	}
        }
        return $this->returnEditView();
    }    
}
