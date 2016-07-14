<?php namespace App;

class IntegralInfo extends BaseModel {

    protected $table = 'integral_info';

    public function user()
    {
    	return $this->belongsTo('App\User', 'uid');
    }
    
    public static function boot()
    {
    	parent::boot();
    	
    }
    
    
}
