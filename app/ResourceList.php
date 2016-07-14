<?php namespace App;

class ResourceList extends BaseModel {

    protected $table = 'resource_list';

    public function user()
    {
    	return $this->belongsTo('App\User', 'uid');
    }
    
}
