<?php

namespace App\Models;

class Provider extends MainModel
{
    protected $table = 'providers';

    protected $casts = [
        'contact_person_info' => 'object',
    ];

    public function user(){
        return $this->belongsTo('App\Models\Admin');
    }
}