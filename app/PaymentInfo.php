<?php namespace App;

class PaymentInfo extends BaseModel {

    protected $table = 'payment_info';

    public function order()
    {
    	return $this->belongsTo('App\OrderInfo', 'oiid');
    }
    
    public static function boot()
    {
    	parent::boot();
    	
    	PaymentInfo::created(function ($paymentInfo){
    		OrderInfo::findOrNew($paymentInfo->oiid)->update(['oiStatus'=>'已付款']);
    		ProdOrderRel::where('oiid', $paymentInfo->oiid)->update(['isPay'=>1]);
    	});
    }
    
    
}
