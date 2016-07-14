<?php 

namespace App\Http\Controllers;

use App\Demand;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;
use App\User;
use App\CoopManu;
use Illuminate\Support\Facades\Input;
use App\ResourceList;
use App\OrderInfo;
use App\DemandInfo;

class ResourceListController extends CrudController{

    public function all($entity){
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        $this->filter = \DataFilter::source(ResourceList::with('user'));
        $this->addFilter('annexName', 'resourceName');
        $this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->addGrid('{{ $user->mobile }}', 'uploader');
        $this->addGrid('annexName', 'resourceName');
        $this->addGrid('cmpy');
        $this->addGrid('contact');
        $this->addGrid('variety', 'saleVariety');
        $this->addGrid('coopManu', 'saleCoopManu');
        $this->addGrid('cmpyLocation');
        $this->addGrid('remarks', 'resRemarks');
        $this->addGrid('dlTms');
        $this->addGrid('reStatus', 'status');
        $this->addGrid('show')->href('', explode('|', 'btn|'.trans('panel::fields.show')."|".$entity));
        $this->addGrid('{{ substr($created_at, 0, 10) }}', 'upload_at');
        $this->addGrid('province')->style('display:none');
        $this->addGrid('city')->style('display:none');
        $this->addGrid('county')->style('display:none');
        $this->addGrid('annex')->style('display:none');
        $this->addGrid('uid')->style('display:none');
        $this->grid->row(function ($row) {
        	$row->cell('annex')->style('display:none');
        	$row->cell('uid')->style('display:none');
        	$row->cell('province')->style('display:none');
        	$row->cell('city')->style('display:none');
        	$row->cell('county')->style('display:none');
        	$row->cell('cmpyLocation')->value = $row->cell('province')->value.$row->cell('city')->value.$row->cell('county')->value;
        	$row->cell('show')->value->with('uri', $row->cell('annex')->value ? 'public/uploads/resource/'.$row->cell('id')->value."/".$row->cell('annex')->value : null)
        	->with('fileName', $row->cell('annexName')->value ? $row->cell('annexName')->value : "无")
        	;
        });
        $this->addStylesToGrid();
        return $this->returnView(null, null, 'modify|delete');
    }
    
    public function edit($entity) {
    	$this->edit = \DataEdit::source(new ResourceList());
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        
        $this->initEntity(trans('panel::fields.resource')); $this->addEdit('province1', 'hidden')->updateValue($this->edit->model->province);
        $this->addEdit('city1', 'hidden')->updateValue($this->edit->model->city);
        $this->addEdit('county1', 'hidden')->updateValue($this->edit->model->county);
        
        $this->addEdit('user.mobile', 'text', 'uploader')->readonly();
        $this->required('cmpy');
        $this->required('contact');
        $variety = array();
        $coopManu1 = $this->getOptionGroupData(new CoopManu(), 'cname');
        $coopManu = $this->getOptionGroupData(new CoopManu(), 'cname');
        foreach ($coopManu as $k=>$v) {
        	$variety[$k] = $k;
        }
        $this->addEdit('s2example-22', 'select', 'saleVariety')->options($variety)->attributes(['class'=>'s2example-22', 'multiple'=>'', 'style'=>'width:99%;', 'data-name'=>'variety']);
        $this->addEdit('variety', 'hidden');
        if ($this->edit->model->coopManu) {
        	foreach ($coopManu as $k=>&$v) {
        		if (!str_contains($this->edit->model->variety, $k)) {
        			unset($coopManu[$k]);
        		}
        		else {
        			foreach ($v as $k1=>&$v1) {
        				if (str_contains($this->edit->model->coopManu, $v1)) {
        					$v1 = [$v1, 'selected="selected"'];
        				}
        				else {
        					$v1 = [$v1, ''];
        				}
        			}
        			
        		}
        	} 
        }
        else {
        	$coopManu = array();
        }
        //dd(Input::all());
        $this->addEdit('s2example-2', 'select', 'saleCoopManu')->options($coopManu)->attributes(['class'=>'s2example-2', 'multiple'=>'', 'style'=>'width:99%;']);
        $this->addEdit('coopManu', 'hidden');
        $this->addEdit('remarks', 'textarea', 'resRemarks');
        $this->addEdit('dlTms')->readonly();
    	$this->addEdit('county', 'select', 'cmpyLocation')->rule('required');
        if (Input::get('county') != '')
        {
        	$this->edit->model->city = Input::get('city');
        	$this->edit->model->county = Input::get('county');
        }
        $uploadUrl = 'public/uploads/resource/'.$this->edit->model->uid;
        $this->edit->add('annex', trans('panel::fields.uploadResource'), 'file')->move($uploadUrl, 'resource_'.md5($this->edit->model->id));
        if (!empty($_FILES['annex']['name']))
        {
        	$annexName = explode(",", $_FILES['annex']['name']);
        	$this->edit->model->annexName = $annexName[0];
        }
        else {
        	$this->addEdit('annexName', 'hidden');
        }
        $this->addEditSelect('reStatus', new ResourceList, 'status');
        $this->addEdit('user.id', 'hidden');
        return $this->returnEditView($coopManu1, 'all', 'coopManu');
    }    
}
