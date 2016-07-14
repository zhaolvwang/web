<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminsRolePermission extends Model
{
    //
    public $table = 'admins_role_permission';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

}
