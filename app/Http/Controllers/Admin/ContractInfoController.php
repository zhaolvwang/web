<?php 

namespace App\Http\Controllers;

use App\Demand;
use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;
use App\App;
use App\User;
use App\CoopManu;
use Illuminate\Support\Facades\Input;
use App\ContractInfo;
use App\OrderInfo;

class ContractInfoController extends CrudController{

    public function all($entity){
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        $this->filter = \DataFilter::source(ContractInfo::with('order'));
        $this->addFilter('order.oinum', 'oinum');
        $this->addFilter('created_at', 'upload_at', 'daterange')->format('Y-m-d', 'en');
        $this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->addGrid('{{ $order->oinum }}', 'oinum');
        $this->addGrid('annexName');
        $this->addGrid('isReturn');
        $this->addGrid('conStatus');
        $this->addGrid('created_at', 'upload_at');
        //$this->addGrid('grab')->actions('grab', explode('|', 'btn|'.trans('panel::fields.grab')."|ProductOut"));
        $this->addStylesToGrid();
        return $this->returnView(null, null, 'modify|delete');
    }
    
    public function edit($entity) {
    	$contractInfo = new ContractInfo();
    	$this->edit = \DataEdit::source($contractInfo);
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->initEntity(trans('panel::fields.contract'));
       
        $orderInfo = OrderInfo::findOrNew($this->edit->model->oiid);
        $oiid = $this->addCommonOinum($orderInfo, \App\ContractInfo::lists('oiid')->all());
        $uploadUrl = 'public/uploads/contract/'.$oiid;
        //dd($uploadUrl);
        $this->edit->add('annex', trans('panel::fields.contract').trans('panel::fields.upload'), 'file')->rule('required|mimes:doc,docx,pdf,jpeg,jpg,png,JPEG,JPG,PNG')->move($uploadUrl, 'contract_'.md5($oiid));
        //dd($_FILES);
        $this->addEditSelect('isReturn', $contractInfo);
        $this->addEditSelect('conStatus', $contractInfo);
        if (isset($_FILES['annex']['name']))
        {
        	$annexName = explode(",", $_FILES['annex']['name']);
        	$this->edit->model->annexName = $annexName[0];
        }
        
        return $this->returnEditView();
    }    
}
