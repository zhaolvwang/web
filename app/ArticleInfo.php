<?php namespace App;

class ArticleInfo extends BaseModel {

    protected $table = 'article_info';

    public function regByAdmin()
    {
    	return $this->belongsTo('App\Admin', 'regBy');
    }
    
    public static function boot()
    {
    	parent::boot();
    	
    	
    }
    
    
}
