<?php

namespace App\Models;

class ProviderSchedule extends MainModel
{
    protected $table = 'provider_schedules';

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

    public function seats(){
        return $this->hasMany('App\Models\ScheduleSeat', 'schedule_id');
    }

    public function availableSeats(){
        return $this->hasMany('App\Models\ScheduleSeat', 'schedule_id')->whereStatus("available");
    }
}