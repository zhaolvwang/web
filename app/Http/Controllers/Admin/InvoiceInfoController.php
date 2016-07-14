<?php 

namespace App\Http\Controllers;

use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;
use App\User;
use App\CoopManu;
use Illuminate\Support\Facades\Input;
use App\InvoiceInfo;
use App\OrderInfo;

class InvoiceInfoController extends CrudController{

    public function all($entity){
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        $this->filter = \DataFilter::source(InvoiceInfo::with('order'));
        $this->addFilter('order.oinum', 'oinum');
        $this->addFilter('created_at', 'upload_at', 'daterange')->format('Y-m-d', 'en');
        $this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->addGrid('{{ $order->oinum }}', 'oinum');
        $this->addGrid('annexName');
        $this->addGrid('created_at', 'upload_at');
        $this->addStylesToGrid();
        return $this->returnView(null, null, 'modify|delete');
    }
    
    public function  edit($entity){
    	$invoiceInfo = new InvoiceInfo();
    	$this->edit = \DataEdit::source($invoiceInfo);
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
       	$this->edit->label(trans("panel::fields.upload").trans("panel::fields.invoice"));
       
       	/*
       	 * Input::get('id')----->order_info.id
       	 */
       	$orderInfo = OrderInfo::findOrNew(Input::get('id') ? Input::get('id') : $this->edit->model->oiid);
        //$this->addEdit('oiid', 'hidden')->insertValue($orderInfo->id)->updateValue($orderInfo->id);
        
    	$oiid = $this->addCommonOinum($orderInfo, \App\InvoiceInfo::lists('oiid')->all());
    	
        $this->addEdit('regBy', 'hidden')->insertValue(auth()->user()->id)->updateValue(auth()->user()->id);
        $uploadUrl = 'public/uploads/invoice/'.$oiid;
        $this->edit->add('annex', trans('panel::fields.invoice').trans('panel::fields.upload'), 'image')->preview(0, 0)->move($uploadUrl, 'invoice_'.md5($oiid));
        
        if (!empty($_FILES['annex']['name']))
        {
        	$annexName = explode(",", $_FILES['annex']['name']);
        	$this->edit->model->annexName = $annexName[0];
        }
        else {
        	$this->addEdit('annexName', 'hidden');
        }
        return $this->returnEditView();
    }    
}
