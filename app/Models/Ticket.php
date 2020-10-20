<?php

namespace App\Models;

class Ticket extends MainModel
{
    protected $table = 'bookings';
    protected $casts = ['schedule_data' => 'object'];

    public function provider(){
        return $this->belongsTo('App\Models\Provider');
    }

    public function schedule(){
        return $this->belongsTo('App\Models\ProviderSchedule', 'schedule_id');
    }

    public function seats(){
        return $this->hasMany('App\Models\TicketSeat', 'booking_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function scopeGetTicket($query, $ticket_no){
        return $query->where('ticket_no', $ticket_no)->firstOrFail();
    }
}