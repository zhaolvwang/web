<?php namespace App;

class MetalMarket extends BaseModel {

    protected $table = 'metal_market';

    /* public function user()
    {
    	return $this->belongsTo('App\User', 'uid');
    } */
    
    public static function boot()
    {
    	parent::boot();
    	
    	
    }
    
    
}
