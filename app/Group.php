<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {

    protected $table = 'admins_group';

    public function authIds()
    {
    	return $this->belongsTo('App\Authority', 'authId');
    }
    
}
