<?php
namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Admin extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'admins';
        protected $remember_token_name      = 'remember_token';


    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }
    
    public function getRememberToken(){
        return $this->remember_token;
    }
    
    public function  setRememberToken($value){
         $this->remember_token =  $value;
    }

    public function getReminderEmail(){  
        $email = \Input::only('email');
        return $email['email'];            
    }


    public function getRememberTokenName(){
        return $this->remember_token_name;
    }
        
        protected $fillable = array('first_name', 'last_name', 'email', 'password');
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	/*
	 * Table Relationship
	 */
	public function groupIds()
	{
		return $this->belongsTo('App\Group', 'groupId');
	}
	
	public function regByAdmin()
	{
		return $this->belongsTo('App\Admin', 'regBy');
	}
	
	public function depmts()
	{
		return $this->belongsTo('App\Department', 'depmt');
	}
	
	
	
	/*
	 * END
	 */
}