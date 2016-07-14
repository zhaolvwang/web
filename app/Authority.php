<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Authority extends Model {

    protected $table = 'authority';

    public function group()
    {
        return $this->hasMany('App\Group');
    }
    
    public function admins()
    {
    	return $this->hasManyThrough('App\Admin', 'App\Group', 'authId', 'groupId');
    }
}
