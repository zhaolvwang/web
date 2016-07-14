<?php namespace App;

class TextPage extends BaseModel {

    protected $table = 'text_page';

    public function regByAdmin()
    {
    	return $this->belongsTo('App\Admin', 'regBy');
    }
    
    public function pColumn()
    {
    	return $this->belongsTo('App\TextPage', 'pid');
    }
    
    public static function boot()
    {
    	parent::boot();
    	
    	
    }
    
    
}
