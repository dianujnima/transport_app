<?php

namespace App\Models;

class TicketSeat extends MainModel
{
    protected $table = 'booking_seats';

    public function ticket(){
        return $this->belongsTo('App\Models\Ticket', 'booking_id');
    }

    public function schedule(){
        return $this->belongsTo('App\Models\ProviderSchedule', 'schedule_id');
    }
}