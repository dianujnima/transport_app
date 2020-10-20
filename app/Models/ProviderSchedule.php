<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderSchedule extends MainModel
{
    use SoftDeletes;
    
    protected $table = 'provider_schedules';

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

    public function provider(){
        return $this->belongsTo('App\Models\Provider');
    }

    public function seats(){
        return $this->hasMany('App\Models\ScheduleSeat', 'schedule_id')->orderBy('cost', 'asc');
    }

    public function availableSeats(){
        return $this->hasMany('App\Models\ScheduleSeat', 'schedule_id')->whereStatus("available");
    }
}