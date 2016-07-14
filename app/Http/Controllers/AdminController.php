<?php

namespace App\Http\Controllers;

use Serverfireteam\Panel\CrudController;
use \Illuminate\Http\Request;
use \App\Admin;
use \App\Group;
use Illuminate\Support\Facades\Input;
use App\Authority;
use App\Department;
use App\MyFnc\Permission;

class AdminController extends CrudController{
    
    public function all($entity){
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');

        $authority = Authority::all();
        $groupId = auth()->user()->groupId;
        $admins = new Admin();
        $a = $admins::with("groupIds", 'regByAdmin')->where('groupId', '!=', 0);
        if($groupId)
        {
        	$a->where('aauthId', auth()->user()->aauthId)->where('groupId', ">", ($groupId > 5 ? 9 : 5));
        	$authId = isset($_GET['authId']) ? $_GET['authId'] : auth()->user()->aauthId;
        	$label = $authority[$authId-1]['name'];
        	//$a->where('aauthId', '=', $authId);
        }
        else {
        	$label = '';
        	
        }
        
        $this->filter = \DataFilter::source($a);
        
        $this->filter->label($label);
        //$this->filter->add('id', 'ID', 'text')->clause('where');
        $this->filter->add('account', trans("panel::fields.account"), 'text');
        $this->filter->add('first_name', trans("panel::fields.FirstName"), 'text');
        if($groupId == 0)
        {
        	$this->filter->add('aauthId', trans("panel::fields.adminGroup"), 'select')->options(array(""=>trans('panel::fields.selectAdminGroup')) + Authority::lists('name', 'id')->all());
        }
        else {
        	$this->filter->add('aauthId', trans("panel::fields.adminGroup"), 'select')->options(array(""=>trans('panel::fields.selectAdminGroup')) + Authority::where('id', '!=', 1)->lists('name', 'id')->all());
        }
        $this->finalizeFilter();
        $this->filter->build();

        $this->grid = \DataGrid::source($this->filter);
        //$this->grid->link("","Articles", "TR");
        //$this->grid->add('id','ID', 'id')->style("width:100px");
        $this->grid->add('account', trans("panel::fields.account"), true);
        //$this->grid->add('{{ $groupIds->authIds->name }}',trans("panel::fields.adminGroup"));
        $this->grid->add('{{ $groupIds->gname }}',trans("panel::fields.level"));
        $this->grid->add('first_name',trans("panel::fields.FirstName"));
        $this->grid->add('{{ $regByAdmin->first_name }}',trans("panel::fields.creator"));
        //$this->grid->add('last_name',trans("panel::fields.LastName"));
        $this->grid->add('email',trans("panel::fields.email"));
        $this->grid->add('mobile',trans("panel::fields.mobile"));
        $this->grid->row(function ($row) {
        	//$groupId = Admin::find($row->cell('id')->value)->groupId;
        	//$row->cell('name')->value = Authority::find(Group::find($groupId)->authId)->name;
        	;
          
         }); 
        
        $this->addStylesToGrid("admins.id");
        return $this->returnView($authority, null, 'modify');
    }
    
    public function edit($entity){
    	$this->edit = \DataEdit::source(new Admin());
        if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        if(!$this->initEntity(trans("panel::fields.admin"), \Session::get('preNum'))) view('panelViews::hasNoAuthority');
        if ($this->edit->status == 'modify' || $this->edit->status == 'show')
        {
        	$this->edit->add('account',trans("panel::fields.account"), 'text')->attributes(array('readonly'=>'readonly'));
        }
        elseif ($this->edit->status == 'create') {
        	$this->edit->add('account1',trans("panel::fields.account"), 'text')->attributes(array('readonly'=>'readonly'))->placeholder(trans('panel::fields.sysAutoCreate'));
        }
        elseif ($this->edit->status == 'delete') {
        	
        }
        
        $this->edit->add('first_name', trans("panel::fields.FirstName"), 'text')->rule('required|unique:admins,first_name,'.$this->edit->model->id);
        /*
         * 系统管理员拥有全部权限，其它管理只能新建该组别的人员(除该组别管理员)
         */
        $group = [];
        switch (auth()->user()->groupId) {
        	case 0:
        		$group = Group::lists('gname', 'id')->all();
        		break;
        	case 1:
        		$group = Group::where('id', '!=', 1)->lists('gname', 'id')->all();
        		break;
        		
        	default:
        		$group = Group::whereNotBetween('id', [1,  (auth()->user()->groupId > 5 ? 9 : 5)])->where('authId', '=', auth()->user()->aauthId)->lists('gname', 'id')->all();
        		break;
        }
        $this->edit->add('groupId',trans("panel::fields.level"), 'select')->options($group)->onchange('changeGroup(this,'.ConstantTool::BROKER_ID.')');
        if (auth()->user()->groupId == 0)	//系统管理员只能由超级管理员创建
        	$this->addEditSelect2('regBy', Admin::lists('first_name', 'id')->all(), 'creator')->rule('required');
        /*
         * END
         */
        $emailField = $this->edit->add('email',trans("panel::fields.email"), 'text')->rule('required|email|unique:admins,email,'.$this->edit->model->id);
        //$emailField->has_error = ' has-error';
        //$emailField->messages = array("ddd");
        if ($this->edit->status == 'modify')
        {
        	$this->edit->add('password1', trans("panel::fields.password"), 'password')->placeholder('留空不修改密码');	//编辑时，密码可为空
        }
        elseif ($this->edit->status == 'create') {
        	$this->edit->add('password1', trans("panel::fields.password"), 'password')->rule('required');	//新建时，密码不可为空
        }
       
        //$this->edit->add('last_name', trans("panel::fields.LastName"), 'text');
        $this->edit->add('mobile', trans("panel::fields.mobile"), 'text');
        $this->edit->add('depmt', trans("panel::fields.depmt"), 'select')->options(Department::lists('name', 'id')->all());
        $this->edit->add('pic1', trans("panel::fields.headPic"), 'image')->move(ConstantTool::AVATAR_URL);
        $this->edit->add('honesty', trans("panel::fields.honesty"), 'textarea');
        $this->edit->add('status', trans("panel::fields.accountStatus"), 'radiogroup')->options(array(trans("panel::fields.normal"), trans("panel::fields.locked")));


        /*
         * 生成员工编号
         */
        if (isset($_POST['account1']))
        {
        	if (Input::get('account1') == '')
        	{
        		$groupId = Input::get('groupId');
        		$authId = ($groupId == 0 ? 0 : Group::find($groupId)->authId);
        		$maxAccount = Admin::where('aauthId', $authId)->max('account');
        		//dd($maxAccount);
        		$maxAccount = $maxAccount ? $maxAccount : 0;
        		$maxNum = (int)substr($maxAccount, 2, strlen($maxAccount)) + 1;
        		$zeroCount = strlen(ConstantTool::ACCOUNT_FORMAT) - strlen((int)substr($maxAccount, 2, strlen($maxAccount)));
        		$preNum = Authority::find(Group::find($groupId)->authId)->preNum;
        		$account = $preNum;
        		for ($i=0; $i<$zeroCount; $i++) {
        			$account .= '0';
        		}
        		$this->edit->model->account = $account.$maxNum;
        		$this->edit->model->aauthId = $authId;
        		if (auth()->user()->groupId != 0)
        			$this->edit->model->regBy = auth()->user()->id;
        	}
        }
        
        
        /*
         * END
         */
        /*
         * 修改密码，留空不修改
         */
        if (isset($_POST['password1']))
        {
        	if ($_POST['password1'] != '')
        	{
        		$this->edit->model->password = \Hash::make($_POST['password1']);
        	}
        	else {
        		if ($this->edit->model->id)
        			$this->edit->model->password = Admin::find($this->edit->model->id)->password;
        	}
        }
        /*
         * END
         */
        return $this->returnEditView();
    }
}
