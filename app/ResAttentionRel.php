<?php namespace App;

class ResAttentionRel extends BaseModel {

    protected $table = 'res_attention_rel';

    public function user()
    {
    	return $this->belongsTo('App\User', 'uid');
    }
    
    public function resource()
    {
    	return $this->belongsTo('App\ResourceList', 'rlid');
    }
    
    public static function boot()
    {
    	parent::boot();
    	
    	
    }
    
    
}
