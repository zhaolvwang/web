<?php 

namespace App\Http\Controllers;

use App\CoopManu;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Input;

class CoopManuController extends CrudController{

    public function all($entity){
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        $this->filter = \DataFilter::source(new CoopManu());
        $this->addFilter('cname', 'coopManu');
        $this->addFilter('item', 'coopManuType', 'select')->options(array(""=>'请选择'.trans('panel::fields.coopManuType')) + CoopManu::groupBy('item')->lists('item', 'item')->all());
        $this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->addGrid('cname', 'coopManu');
        $this->addGrid('item', 'coopManuType');
        $this->addGrid('logo1')->image('', explode('|', 'btn|'.trans('panel::fields.logo')."|".$entity));
        $this->addGrid('website');
        $this->addGrid('logo')->style('display:none');
        $this->addGrid('logoName')->style('display:none');
        $this->addStylesToGrid();
        $this->grid->row(function ($row) {
        	$row->cell('logo')->style('display:none');
        	$row->cell('logoName')->style('display:none');
        	$row->cell('logo1')->value->with('uri', $row->cell('logo')->value ? 'public/uploads/coopManu/'.$row->cell('logo')->value : '')
        	->with('fileName', $row->cell('logoName')->value ? $row->cell('logoName')->value : "无")
        	; 
        });
        return $this->returnView(null, null, 'modify|delete');
    }
    
    public function  edit($entity){
    	$this->edit = \DataEdit::source(new CoopManu());
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
       	$this->edit->label(trans("panel::fields.edit").trans("panel::fields.coopManu"));
       
       $this->addEdit('cname', 'text', 'coopManu')->rule('required');
       $this->addEdit('item', 'text', 'coopManuType')->rule('required');
       $id = $this->edit->model->id ? $this->edit->model->id : rand(1000, 9999);
       $uploadUrl = 'public/uploads/coopManu/';
       $this->addEdit('logo', 'image')->move($uploadUrl, md5($id))->preview(0, 0); 
       $this->addEdit('website');
        //dd($_FILES);
        if (!empty($_FILES['logo']['name']))
        {
        	$logoName = explode(",", $_FILES['logo']['name']);
        	$this->edit->model->logoName = $logoName[0];
        }
        else {
        	$this->addEdit('logoName', 'hidden');
        }
        return $this->returnEditView();
    }    
}
