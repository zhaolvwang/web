<?php namespace App;

class PGBill extends BaseModel {

    protected $table = 'pickup_goods_bill';

    /* public function user()
    {
    	return $this->belongsTo('App\User', 'uid');
    } */
    
    public static function boot()
    {
    	parent::boot();
    	
    	
    }
    
    
}
