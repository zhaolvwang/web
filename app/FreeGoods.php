<?php namespace App;

class FreeGoods extends BaseModel {

    protected $table = 'free_find_goods';

    /* public function user()
    {
    	return $this->belongsTo('App\User', 'uid');
    } */
    
    public static function boot()
    {
    	parent::boot();
    	
    	
    }
    
    
}
