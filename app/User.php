<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['uname', 'mobile', 'password', 'cmpy'];
    
    /**
     * 不可以被批量賦值的屬性。
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    public function getRememberToken()
    {
    	return $this->remember_token;
    }
    
    public function setRememberToken($value)
    {
    	$this->remember_token = $value;
    }
    
    public function getRememberTokenName()
    {
    	return 'remember_token';
    }
    
    /*
     * 检测用户是否已完善资料
     */
    public function isPerfectUser() {
    	if (
    			empty(trim($this->idenNum)) ||
    			empty(trim($this->fax)) ||
    			empty(trim($this->postalcode)) ||
    			empty(trim($this->address)) ||
    			empty(trim($this->qq)) ||
    			empty(trim($this->taxNum)) ||
    			empty(trim($this->orgCode)) ||
    			empty(trim($this->regPic)) ||
    			empty(trim($this->taxPic)) ||
    			empty(trim($this->orgPic)) ||
    			empty(trim($this->bank)) ||
    			empty(trim($this->bcNum)) ||
    			empty(trim($this->bankAddr))
    		) {
    		return false;
    	}
    	else {
    		return true;
    	}
    }
}
