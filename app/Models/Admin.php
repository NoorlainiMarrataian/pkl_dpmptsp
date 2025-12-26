<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin'; 
    protected $primaryKey = 'admin_id'; 
    public $timestamps = true;
    protected $fillable = ['username', 'password'];
    protected $hidden = ['password'];
}
