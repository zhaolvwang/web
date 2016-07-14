<?php 

namespace App\Http\Controllers;

use \Serverfireteam\Panel\CrudController;

use Illuminate\Http\Request;
use App\User;
use App\CoopManu;
use Illuminate\Support\Facades\Input;
use App\PaymentInfo;
use App\OrderInfo;
use App\ContractInfo;

class PaymentInfoController extends CrudController{

    public function all($entity){
        if(!parent::all($entity)) return view('panelViews::hasNoAuthority');
        $this->filter = \DataFilter::source(PaymentInfo::with('order'));
        $this->addFilter('order.oinum', 'oinum');
        $this->addFilter('paynum');
        //$this->addFilter('created_at', 'upload_at', 'daterange')->format('Y-m-d', 'en');
        $this->commonSearch();
        
        $this->grid = \DataGrid::source($this->filter);
        $this->addGrid('paynum');
        $this->addGrid('{{ $order->oinum }}', 'oinum');
        $this->addGrid('arrivalDt');
        $this->addGrid('arrivalAmount');
        $this->addGrid('payMode');
        $this->addGrid('checkBy');
        $this->addGrid('created_at', 'entered_at');
        $this->addStylesToGrid();
        return $this->returnView(null, null, 'modify|delete');
    }
    
    public function edit($entity) {
    	$paymentInfo = new PaymentInfo();
    	$this->edit = \DataEdit::source($paymentInfo);
    	if(!parent::edit($entity)) return view('panelViews::hasNoAuthority');
        $this->initEntity(trans('panel::fields.paymentInfo'));
       
        /*
         * Input::get('id')----->order_info.id
         */
        $orderInfo = OrderInfo::findOrNew(Input::get('id') ? Input::get('id') : $this->edit->model->oiid);
        $this->addEdit('oiid', 'hidden')->insertValue($orderInfo->id);
   		if ($this->edit->status == 'modify' || $this->edit->status == 'show')
        {
        	$this->addEdit('paynum')->readonly();
        }
        elseif ($this->edit->status == 'create') {
        	$this->addEdit('paynum1', 'text', 'paynum')->placeholder(trans('panel::fields.sysAutoCreate'))->readonly();
        }
        $oiid = $this->addCommonOinum($orderInfo, \App\PaymentInfo::lists('oiid')->all());
        $this->required('arrivalDt', null, 'date')->format('Y-m-d', 'zh-CN');
        $this->addDecimalFormat('arrivalAmount')->rule('required');
        $this->addEditSelect('payMode', new PaymentInfo())->rule('required');
        $this->addEdit('checkBy');
        $uploadUrl = 'public/uploads/voucher/'.$oiid;
       	$this->addEdit('voucherPic', 'image')->move($uploadUrl, 'voucher_'.md5($oiid))->preview(0, 0)->rule('required');
        if (isset($_FILES['voucherPic']['name']))
        {
        	$annexName = explode(",", $_FILES['voucherPic']['name']);
        	$this->edit->model->voucherPicName = $annexName[0];
        }
        /*
         * 生成付款凭证号
         */
        if (isset($_POST['paynum1']))
        {
        	if (Input::get('paynum1') == '')
        	{
        		$this->edit->model->paynum = 'P'.time();
        	}
        }
        
        
        return $this->returnEditView();
    }    
}
