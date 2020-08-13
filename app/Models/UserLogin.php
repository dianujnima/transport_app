<?php

namespace App\Models;

class UserLogin extends MainModel
{
    protected $table = 'user_logins';

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}