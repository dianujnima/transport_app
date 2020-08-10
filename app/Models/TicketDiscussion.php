<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TicketDiscussion extends MainModel
{
    use SoftDeletes;

    protected $table = 'tickets_discussions';
    
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function ticket(){
        return $this->belongsTo('App\Ticket', 'ticket_id');
    }

    public function support(){
        return $this->belongsTo('App\Admin', 'user_id');
    }
}
