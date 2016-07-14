<?php namespace App;

class DemandOffer extends BaseModel {

    protected $table = 'demand_offer';

    public function demand()
    {
    	return $this->belongsTo('App\DemandInfo', 'diid');
    } 
    
    public function user()
    {
    	return $this->belongsTo('App\User', 'uid');
    } 
    
    public static function boot()
    {
    	parent::boot();
    	
    	DemandOffer::saved(function ($demandOffer){
    		//dd($demandOffer);
    	});
    }
    
    
}
