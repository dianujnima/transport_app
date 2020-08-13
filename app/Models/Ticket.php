<?php

namespace App\Models;

class Ticket extends MainModel
{
    protected $table = 'bookings';

    protected $casts = [
        'seat_nos' => 'array',
        'seat_ids' => 'array',
    ];

    public function provider(){
        return $this->belongsTo('App\Models\Provider');
    }

    public function schedule(){
        return $this->belongsTo('App\Models\ProviderSchedule', 'schedule_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function scopeGetTicket($query, $ticket_no){
        return $query->where('ticket_no', $ticket_no)->firstOrFail();
    }
}