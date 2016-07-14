<?php 

namespace App\Http\Controllers;

use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\FreeGoods;

class FreeGoodsController extends CrudController{

    public function all($entity){
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        $this->filter = \DataFilter::source(new FreeGoods);
        $this->addFilter('name');
        $this->addFilter('mobile');
        //$this->addFilter('created_at', 'upload_at', 'daterange')->format('Y-m-d', 'en');
        $this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->addGrid('name');
        $this->addGrid('mobile');
        $this->addGrid('service');
        $this->grid->add('descp', '年限');
        $this->addStylesToGrid();
        return $this->returnView(null, null, 'modify|delete');
    }
    
    public function  edit($entity){
    	$this->edit = \DataEdit::source(new FreeGoods());
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->initEntity('客户经理免费找货');
        $this->addEdit('name');
        $this->addEdit('mobile');
        $this->addEdit('service');
        $this->addEdit('descp', 'text', 'years');
        $this->addEdit('qq');
        $uploadUrl = 'public/uploads/freeGoods';
        $this->edit->add('avatar', trans('panel::fields.avatar').trans('panel::fields.upload'), 'image')->preview(0, 0)->move($uploadUrl, md5(date('His')));
        if (!empty($_FILES['avatar']['name']))
        {
        	$annexName = explode(",", $_FILES['avatar']['name']);
        	$this->edit->model->avatarName = $annexName[0];
        }
        else {
        	$this->addEdit('avatarName', 'hidden');
        }
        
        return $this->returnEditView();
    }    
}
