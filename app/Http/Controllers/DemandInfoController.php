<?php 

namespace App\Http\Controllers;

use App\DemandInfo;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;
use App\User;
use App\CoopManu;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use App\Product;

class DemandInfoController extends CrudController{

    public function all($entity){
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        $this->filter = \DataFilter::source(DemandInfo::with('user')->where(function($query){
        	$query->where('diStatus', '待抢单')->orWhere('grabBy', auth()->user()->id);
        })->where('relTms', '<', 3));
        $this->addFilter('dinum');
        $this->addFilter('relTms');
        $this->addFilterSelect('diStatus', new DemandInfo());
        $this->addFilter('created_at', null, 'daterange')->format('Y-m-d', 'en');
        $this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->addGrid('dinum');
        $this->addGrid('{{ $user->cmpy }}', 'cmpy')->style('display:none;');
        $this->addGrid('{{ $user->uname }}', 'contact')->style('display:none;');
        $this->grid->add('status', '采购商信息');
        $this->addGrid('content', 'deCnt');
        $this->addGrid('created_at');
        $this->addGrid('relTms');
        $this->addGrid('diStatus');
        $this->addGrid('grab')->actions('grab', explode('|', 'btn|'.trans('panel::fields.grab')));
        $this->addGrid('release')->actions('release', explode('|', 'btn|'.trans('panel::fields.release')));
        $this->addGrid('id')->style('display:none;');
        $this->addGrid('uid')->style('display:none;');
        $this->addGrid('regBy')->style('display:none;');
        $this->addGrid('mobile')->style('display:none;');
        $this->addStylesToGrid();
        $this->grid->edit('edit', trans('panel::fields.edit'), 'modify');
        $this->grid->row(function ($row) {
        	$row->cell('id')->style('display:none');
        	$row->cell('uid')->style('display:none');
        	$row->cell('regBy')->style('display:none');
        	$row->cell('mobile')->style('display:none');
        	$row->cell('{{ $user->cmpy }}')->style('display:none');
        	$row->cell('{{ $user->uname }}')->style('display:none');
        	if ($row->cell('diStatus')->value != '待抢单') {
        		$row->cell('grab')->value->with('name', 'disabled');
        	}
        	else {
        		$row->cell('_edit')->value = '';
        		$row->cell('release')->value->with('name', 'disabled');
        	}
        	$userInfo = User::firstOrNew(['mobile'=>$row->cell('mobile')->value]);
        	if(!$userInfo->exists) {
        		$row->cell('status')->value = $row->cell('mobile')->value.'<span style="color:red;">（采购商未注册，抢单后，请务必先注册采购商）</span>';
        	}
        	else {
        		if (!$row->cell('uid')->value)
        			DemandInfo::findOrNew($row->cell('id')->value)->update(['uid'=>$userInfo->id]);
        		$row->cell('status')->value = $userInfo->cmpy.'，'.$userInfo->uname.'，'.$row->cell('mobile')->value;
        	}
        	
        });
        $this->grid->link('panel/'.$this->entity.'/edit', trans('panel::fields.add'), "TR", ['class'=>'btn btn-primary']);
        return view('panelViews::all', array(
        		'grid' 	      => $this->grid,
        		'filter' 	      => $this->filter,
        		'title'          => $this->entity ,
        		'current_entity' => $this->entity,
        		'method' => $this->routeMethod,
        		'import_message' => (\Session::has('import_message')) ? \Session::get('import_message') : '',
        ));
    }
    
    public function edit($entity){
    	$this->edit = \DataEdit::source(new DemandInfo());
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->initEntity(trans('panel::fields.demandInfo'));
        if ($this->edit->status == 'create') {
        	/* $users = array(''=>"");
        	dd(User::all());
        	foreach (User::all() as $v)
        	{
        		$users[$v->id] = $v->cmpy." - ".$v->uname." - ".$v->mobile;
        	}
        	 */
        	$this->addEdit('dinum1', 'text', 'dinum')->placeholder(trans('panel::fields.sysAutoCreate'))->readonly();
        	//$this->addEdit('uid', 'select', 'user')->options($users)->attributes(['class'=>'s2example'])->rule('required');
        	$this->addIngeterFormat('mobile', 'user', 'text')->attributes(['placeholder'=>'请输入客户手机号码'])->rule('required|mobile');
        	$this->addEdit('regBy', 'hidden')->insertValue(auth()->user()->id); 
        	//dd(11);
        }
        else {
        	$this->addEdit('dinum')->readonly();
        	$userProfile = User::firstOrNew(['mobile'=>$this->edit->model->mobile]);
        	if ($userProfile->exists) {
        		$this->addEdit('cmpy')->allValue($userProfile->cmpy)->readonly();
        		$this->addEdit('contact')->allValue($userProfile->uname)->readonly();
        		$this->addEdit('mobile')->allValue($userProfile->mobile)->readonly();
        	}
        	else {
        		/*
        		 * 采购商未注册就发布需求
        		 */
        		return response('<script>alert("请先注册采购商后，才能编辑需求信息！");window.location.href="'.url('panel/DemandInfo/all').'"</script>');
        	}
        }
        	//return new Response('<script>window.history.go(-1);</script>');
        
        $this->addEdit('province1', 'hidden')->updateValue($this->edit->model->province);
        $this->addEdit('city1', 'hidden')->updateValue($this->edit->model->city);
        $this->addEdit('county1', 'hidden')->updateValue($this->edit->model->county);
        
        $this->required('pname');
        $this->addEdit('variety', 'select')->options(Product::getEnumValues('matType'))->rule('required');
        $alloyNum = array(''=>"");
        foreach (Product::getEnumValues('alloyNum') as $k=>$v)
        {
        	$alloyNum[$k] = $v;
        }
        $this->required('alloyNum', null, 'select')->options($alloyNum)->attributes(['class'=>'s2example']);
        $this->addEditSelect('processStatus')->rule('required');
        $this->addEdit('standard')->rule('required');
        $this->addSelectOptionGroup('coopManu', null, new CoopManu(), 'cname')->rule('required');
        $this->addDecimalFormat('unitWeight')->rule('required')->unit = trans('panel::fields.ton');
        $this->addDecimalFormat('intentPrc')->rule('required')->unit = trans('panel::fields.yuan_ton');
        $this->addDecimalFormat('qty', 'quantity')->rule('required')->unit = trans('panel::fields.ton');
        $this->addEditSelect('disMode', new DemandInfo())->rule('required');
        $this->addEdit('county', 'select', 'receivePlace')->rule('required');
        if (Input::get('county') != '')
        {
        	$this->edit->model->province = Input::get('province');
        	$this->edit->model->city = Input::get('city');
        	$this->edit->model->county = Input::get('county');
        }
        $this->addEdit('payDays')->rule('required')->unit = trans('panel::fields.days');
        $this->required('deliveryDt', null, 'date')->format('Y-m-d', 'zh-CN');
        //$uploadUrl = 'public/uploads/contract/'.$this->edit->model->id;
        //$this->edit->add('contract', trans('panel::fields.contract').trans('panel::fields.upload'), 'file')->rule('required|mimes:doc,docx,pdf,jpeg,jpg,png,JPEG,JPG,PNG')->move($uploadUrl, 'contract_'.md5($this->edit->model->created_at));
        $this->addDecimalFormat('freight')->rule('required');
         $this->edit->add('content', '需求内容', 'textarea');
        if ($this->edit->status == 'modify')
        {
        	$this->addIngeterFormat('relTms1', 'relTms')->updateValue($this->edit->model->relTms ? $this->edit->model->relTms : '0')->readonly();
        }
        else {
        	$this->addIngeterFormat('relTms')->updateValue($this->edit->model->relTms ? $this->edit->model->relTms : '0')->readonly();
        }
        
        
        /* if (isset($_FILES['contract']['name']))
         {
         $contractName = explode(",", $_FILES['contract']['name']);
         $this->edit->model->contractName = $contractName[0];
         } */
        if ($this->edit->model->diStatus == '已抢单')
        {
	        $this->addEdit('release', 'radiogroup')->options(['否', '是']);
	         
	        if (Input::get('release'))
	        {
	        	$this->edit->model->relTms = $this->edit->model->relTms + 1;
	        	if ($this->edit->model->relTms == 3) {
	        		$this->edit->model->diStatus = '释放';
	        		$this->edit->model->grabBy = null;
	        		$this->edit->model->grabDt = null;
	        	}
	        	else {
	        		$this->edit->model->diStatus = '待抢单';
	        		$this->edit->model->grabBy = null;
	        		$this->edit->model->grabDt = null;
	        	}
	        }
        }
         
        $this->addEditSelect('verifyStatus', new DemandInfo())->insertValue('审核通过');
        /*
         * 生成需求单编号
         */
        if (isset($_POST['dinum1']))
        {
        	if (Input::get('dinum1') == '')
        	{
        		$this->edit->model->diStatus = '已抢单';
        		$this->edit->model->grabBy = auth()->user()->id;
        		$this->edit->model->grabDt = date('Y-m-d H:i:s');
        		$this->edit->model->dinum = 'D'.time();
        		$this->edit->model->uid = User::firstOrCreate(['mobile'=>Input::get('mobile')])->id;
        	}
        }
        //dd($this->edit->model);
        return $this->returnEditView();
    }   

    /*
     * 经纪人抢单操作
     */
    public function grab($entity) {
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	 /*
         * Input::get('id')----->demand_info.id
         */
    	$demandInfo = DemandInfo::findOrNew(Input::get('id'));
    	if ($demandInfo->exists)
    	{
    		if ((!$demandInfo->grabBy) && (!$demandInfo->grabDt)) {
	    		$demandInfo->grabBy = auth()->user()->id;
	    		$demandInfo->grabDt = date('Y-m-d H:i:s');
	    		$demandInfo->diStatus = '已抢单';
	    		$demandInfo->save();
	    		return new Response('<script>alert("需要单：'.$demandInfo->dinum.'抢单成功！");window.location.href="'.url('panel/'.$entity.'/all').'"</script>');
    		}
    	}
    	return new Response('<script>window.history.go(-1);</script>');
    }
    
    /*
     * 经纪人释放需求操作
     */
    public function release($entity) {
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
    	/*
    	 * Input::get('id')----->demand_info.id
    	*/
    	$demandInfo = DemandInfo::findOrNew(Input::get('id'));
    	if ($demandInfo->exists)
    	{
    		if ($demandInfo->grabBy && $demandInfo->grabDt) {
    			if ($demandInfo->relTms < 2) {
    				$demandInfo->diStatus = '待抢单';
    				$demandInfo->increment('relTms');
    			}
    			else {
    				$demandInfo->diStatus = '释放';
    				if ($demandInfo->relTms == 2) 
    					$demandInfo->increment('relTms');
    			}
    			$demandInfo->grabBy = null;
    			$demandInfo->grabDt = null;
    			$demandInfo->save();
    			return new Response('<script>alert("需要单：'.$demandInfo->dinum.'释放成功！");window.location.href="'.url('panel/'.$entity.'/all').'"</script>');
    		}
    	}
    	return new Response('<script>window.history.go(-1);</script>');
    }
}




