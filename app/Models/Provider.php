<?php

namespace App\Models;

class Provider extends MainModel
{
    protected $table = 'providers';

    protected $appends = ['image_url', 'hashid'];

    protected $casts = [
        'contact_person_info' => 'object',
    ];

    public function user(){
        return $this->belongsTo('App\Models\Admin')->whereIsActive(1);
    }

    public function getImageUrlAttribute(){
        return $this->image ? check_file($this->image, 'provider') : null;
    }

    public function admin_user(){
        return $this->belongsTo('App\Models\Admin');
    }
}