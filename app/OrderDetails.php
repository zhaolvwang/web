<?php namespace App;

class OrderDetails extends BaseModel {

    protected $table = 'order_details';

    public function enterBy()
    {
    	return $this->belongsTo('App\Admin', 'regBy');
    }
    
    public function orderInfo()
    {
    	return $this->belongsTo('App\OrderInfo', 'oiid');
    }
    
    public static function boot()
    {
    	parent::boot();
    	
    	
    }
    
    
}
