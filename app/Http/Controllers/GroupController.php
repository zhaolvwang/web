<?php

namespace App\Http\Controllers;

use Serverfireteam\Panel\CrudController;
use \Illuminate\Http\Request;
use App\Authority;
use App\Group;
use App\MyFnc\Permission;
use Illuminate\Support\Facades\Input;

class GroupController extends CrudController {
    
    public function all($entity) {
    	if(!parent::all($entity)) return view('panelViews::hasNoAuthority');

        $this->filter = \DataFilter::source(Group::with('authIds')->where('id', '!=', 0));
        //$this->filter->add('id', 'ID', 'text')->attributes(array("style"=>"width:100px"));
        $this->filter->add('gname', trans("panel::fields.name"), 'text');
        $this->filter->add('authId', trans("panel::fields.adminGroup"), 'select')->options(array(""=>trans('panel::fields.selectAdminGroup')) + Authority::lists('name', 'id')->all());
        $this->finalizeFilter();
        $this->filter->build();
                
        $this->grid = \DataGrid::source($this->filter);
        $this->grid->add('id','ID', true)->style("width:100px");
        $this->grid->add('gname',trans("panel::fields.name"));
        $this->grid->add('{{ $authIds->name }}',trans("panel::fields.adminGroup"), 'authId');
        
        $this->addStylesToGrid();
        return $this->returnView(null, null, 'modify');
    }
    
    public function edit($entity) {
    	$this->edit = \DataEdit::source(new Group());
        if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->initEntity(trans("panel::fields.authority"), strtolower(\Session::get('preNum')));
        
        //$this->edit->link("","Articles", "TR")->back();
        $authorityName = Authority::lists('name', 'id')->all();
        $authorityDescp = Authority::lists('descp', 'id')->all();
        $authorityFunc = Authority::lists('func', 'id')->all();
        $this->edit->add('gname','名称', 'text')->rule('required')->allValue($this->edit->model->gname ? $this->edit->model->gname : Input::get('gname'));
        $authId = Input::get('authId') ? Input::get('authId') : ($this->edit->model->authId ? $this->edit->model->authId : 1);
        $this->edit->add('authId',trans("panel::fields.adminGroup"),'select')->options($authorityName)->onchange("changeAuthority(this, ".($this->edit->model->id ? $this->edit->model->id : 0).")")
        	->insertValue($authId)
        	->updateValue($authId)
        	->showValue(Authority::findOrNew($this->edit->model->authId)->name);
        
        
        $b = explode("|", $authorityFunc[$authId]);
        foreach ($b as $k1=>$v1) {
        	$a = explode(",", $v1);
        	$func = explode("|", $authorityDescp[$authId]);
        	$this->edit->add("authority[".($authId.$k1)."]", explode(":", $a[0])[0], 'checkboxgroup')->options(array_splice($a, 1))->extra(isset($func[$k1]) ? $func[$k1] : '')->attributes(['class'=>'authority'.$authId]);
        	 
        }
       
        /*
         * 0（系统组）|0（经纪人组）|0（仓库组）|0（财务组）|0（物流组）
         * 例：0|0011,1,11111,1111,1111,1111|0|0|0
         * 0011,1,11111,1111,1111,1111表示经纪人组的数据，查看数据表authority中的经纪人组的func：组员管理,增,删,查,改|采购商,查|需求信息,增,删,查,改,抢单|订单管理,增,删,查,改|合同管理,增,删,查,改|采购报价管理,增,删,查,改
         * 每一个数字表示一个功能，0：冇权限，1：有权限
         */
        $authority = array();
        //print_r($authorityName);exit;
        if (isset($_POST['authority'])) {
        	foreach ($authorityFunc as $k=>$v) {
        		if ($k == Input::get('authId')) {
        			$authority_group_array = [];
        			foreach (explode("|", $v) as $k1=>$v1) {
        				$group = explode(",", $v1, 2)[1];
        				$authority_group = "";
        				foreach (explode(",", $group) as $k2=>$v2) {
        					if (isset($_POST['authority'][$k.$k1][$k2])) {
        						$authority_group .= '1';
        					}
        					else {
        						$authority_group .= '0';
        					}
        				}
        				array_push($authority_group_array, $authority_group);
        			}
        			array_push($authority, implode(",", $authority_group_array));
        		}
        		else {
        			array_push($authority, 0);
        		}
        	}
        	$this->edit->model->authority = implode("|", $authority);
        }
        
        //
        return $this->returnEditView();
    }
}
