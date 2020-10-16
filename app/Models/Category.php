<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Category extends MainModel
{
    protected $table = 'categories';
    // protected $casts = ['seat_types' => 'object'];

    public function scopeAllActive($query){
        return $query->whereIsActive(1);
    }
}