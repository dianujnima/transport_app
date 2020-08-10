<?php

namespace App\Models;

class Contact extends MainModel
{
    protected $table = 'contacts';

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
