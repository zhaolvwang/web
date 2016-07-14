<?php 

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;

class UserController extends CrudController{

    public function all($entity){
       	if(!parent::all($entity)) return view('panelViews::hasNoAuthority');

        /** Simple code of  filter and grid part , List of all fields here : http://laravelpanel.com/docs/master/crud-fields


			$this->filter = \DataFilter::source(new \App\Category);
			$this->filter->add('name', 'Name', 'text');
			$this->filter->submit('search');
			$this->filter->reset('reset');
			$this->filter->build();

			$this->grid = \DataGrid::source($this->filter);
			$this->grid->add('name', 'Name');
			$this->grid->add('code', 'Code');
			$this->addStylesToGrid();

        */
        //dd($entity);
        $this->filter = \DataFilter::source(new User());
        $this->addFilter('mobile');
        $this->addFilter('uname');
        $this->addFilter('cmpy');
        $this->addFilter('email');
        $this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->grid->add('id','ID', true)->style("width:80px");
        $this->addGrid('mobile');
        $this->addGrid('uname');
        $this->addGrid('cmpy');
        $this->addGrid('email');
        $this->addStylesToGrid();
        return $this->returnView(null, null, 'show|modify');
    }
    
    public function edit($entity) {
    	$this->edit = \DataEdit::source(new User());
        if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        
        $this->initEntity(trans('panel::fields.user'));
        $groupId = auth()->user()->groupId;
        if ($this->edit->status == 'modify')
        {
        	$this->addEdit('mobile')->readonly();
        	$this->edit->model->password = \Hash::make('123456');
        }
        elseif ($this->edit->status == 'create') {
        	$this->addEdit('mobile')->rule('required|mobile');
        }
        elseif ($this->edit->status == 'delete') {
        	
        }
        elseif ($this->edit->status == 'show') {
        	$this->addEdit('mobile')->rule('required');
        }
       	
       	
       	$this->addEdit('uname')->rule('required');
       	$this->addEdit('cmpy')->rule('required');
       	$this->edit->add('password1', trans("panel::fields.password"), 'password')->placeholder('留空不修改密码');	//编辑时，密码可为空
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
       				$this->edit->model->password = User::find($this->edit->model->id)->password;
       		}
       	}
       	$this->addEdit('email')->rule('email');
       	$this->addEdit('tel');
       	$this->addEdit('sex', 'radiogroup')->options(['男'=>'男', '女'=>'女']);
       	$this->addEdit('idenNum')->rule('idenNum');
       	$this->addEdit('fax');
       	$this->addEdit('postalcode');
       	$this->addEdit('address');
       	$this->addEdit('qq');
       	$this->addEdit('taxNum');
       	$this->addEdit('orgCode');
		
		$id = $this->edit->model->id;
		$uploadUrl = 'public/uploads/images/'.$id;
		$this->addEdit('regPic', 'image')->move($uploadUrl, 'reg_'.md5($id))->preview(0, 0);
		$this->addEdit('taxPic', 'image')->move($uploadUrl, 'tax_'.md5($id))->preview(0, 0);
		$this->addEdit('orgPic', 'image')->move($uploadUrl, 'org_'.md5($id))->preview(0, 0);
		
		$this->addEdit('bank');
		$this->addEdit('bcNum');
		$this->addEdit('bankAddr');
		$this->addEdit('duty');
		$this->addEdit('department', 'text', 'depmt');
		$this->addEdit('boss');
		$this->addEdit('industry');
		$this->addEdit('prods');
		$this->addEditSelect('saleMode', new User());
        return $this->returnEditView();
    }    
}
