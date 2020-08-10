<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends MainModel
{
    use SoftDeletes;

    protected $table = 'tickets';

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function assigned_user(){
        return $this->belongsTo('App\Admin', 'assigned_to');
    }

    public function discussions(){
        return $this->hasMany('App\TicketDiscussion', 'ticket_id');
    }
}
