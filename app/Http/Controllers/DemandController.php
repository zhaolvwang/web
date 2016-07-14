<?php 

namespace App\Http\Controllers;

use App\Demand;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;
use App\App;
use App\User;
use App\CoopManu;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use App\Product;

class DemandController extends CrudController{

    public function all($entity){
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        $this->filter = \DataFilter::source(Demand::with('user')->where(function($query){
        	$query->where('diStatus', '待抢单')->orWhere('grabBy', auth()->user()->id);
        })->where('relTms', '<', 3));
        $this->addFilter('dinum');
        $this->addFilter('relTms');
        $this->addFilterSelect('diStatus', new Demand());
        $this->addFilter('created_at', null, 'daterange')->format('Y-m-d', 'en');
        $this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->addGrid('dinum');
        $this->addGrid('{{ $user->cmpy }}', 'cmpy');
        $this->addGrid('{{ $user->uname }}', 'contact');
        $this->addGrid('{{ $user->mobile }}', 'mobile');
        $this->addGrid('created_at');
        $this->addGrid('relTms');
        $this->addGrid('diStatus');
        $this->addGrid('grab')->actions('grab', explode('|', 'btn|'.trans('panel::fields.grab')));
        $this->addGrid('release')->actions('release', explode('|', 'btn|'.trans('panel::fields.release')));
        $this->addStylesToGrid();
        $this->grid->row(function ($row) {
        	if ($row->cell('diStatus')->value != '待抢单') {
        		$row->cell('grab')->value->with('name', 'disabled');
        	}
        	else {
        		$row->cell('release')->value->with('name', 'disabled');
        	}

        });
        return $this->returnView(null, null, 'modify');
    }
    
    public function edit($entity){
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->edit = \DataEdit::source(new Demand());
        if ($this->edit->status == 'create')
        	return new Response('<script>window.history.go(-1);</script>');
        $this->initEntity(trans('panel::fields.demandInfo'));
        
        $userProfile = User::find($this->edit->model->uid);
        $this->addEdit('province1', 'hidden')->updateValue($this->edit->model->province);
        $this->addEdit('city1', 'hidden')->updateValue($this->edit->model->city);
        $this->addEdit('county1', 'hidden')->updateValue($this->edit->model->county);
        $this->addEdit('dinum')->readonly();
        $this->addEdit('cmpy')->updateValue($userProfile->cmpy)->readonly();
        $this->addEdit('contact')->updateValue($userProfile->uname)->readonly();
        $this->addEdit('mobile')->updateValue($userProfile->mobile)->readonly();
        $this->required('variety');
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
        $this->addEditSelect('disMode', new Demand())->rule('required');
        $this->addEdit('province', 'select', 'receivePlace')->rule('required');
        if (Input::get('city') != '')
        {
        	$this->edit->model->city = Input::get('city');
        	$this->edit->model->county = Input::get('county');
        }
        $this->addDecimalFormat('payDays')->rule('required')->unit = trans('panel::fields.days');
        $this->required('deliveryDt', null, 'date')->format('Y-m-d', 'zh-CN');
        //$uploadUrl = 'public/uploads/contract/'.$this->edit->model->id;
        //$this->edit->add('contract', trans('panel::fields.contract').trans('panel::fields.upload'), 'file')->rule('required|mimes:doc,docx,pdf,jpeg,jpg,png,JPEG,JPG,PNG')->move($uploadUrl, 'contract_'.md5($this->edit->model->created_at));
        $this->addDecimalFormat('freight')->rule('required');
        $this->addEdit('remarks', 'textarea');
        if ($this->edit->status == 'modify')
        {
        	$this->addIngeterFormat('relTms1', 'relTms')->updateValue($this->edit->model->relTms ? $this->edit->model->relTms : '0')->readonly();
        }
        else {
        	$this->addIngeterFormat('relTms')->updateValue($this->edit->model->relTms ? $this->edit->model->relTms : '0')->readonly();
        }
        
        //dd($_FILES);
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
	        	}
	        }
        }
        $this->addEditSelect('verifyStatus', new Demand());
        
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
    	$demandInfo = Demand::findOrNew(Input::get('id'));
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
    	$demandInfo = Demand::findOrNew(Input::get('id'));
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
}




