<?php namespace App;

class InvoiceInfo extends BaseModel {

    protected $table = 'invoice_info';

    public function order()
    {
    	return $this->belongsTo('App\OrderInfo', 'oiid');
    }
    
    public static function boot()
    {
    	parent::boot();
    	
    	InvoiceInfo::created(function ($invoiceInfo){
    		OrderInfo::findOrNew($invoiceInfo->oiid)->update(['oiStatus'=>'已开票']);
    	});
    }
    
    
}
