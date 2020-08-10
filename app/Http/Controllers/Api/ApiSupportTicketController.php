<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Notifications\SupportTicketAdded;
use App\Notifications\SupportTicketMessageAdded;
use App\Ticket;
use App\TicketDiscussion;
use Illuminate\Http\Request;

class ApiSupportTicketController extends ApiController {

    public function index() {
        $data = array(
            'tickets' => Ticket::with(['user', 'assigned_user', 'discussions'])->whereUserId($this->current_user()->id)
            ->orderby('updated_at', 'desc')
            ->paginate(getPaginationPerPage())
        );
        return api_response(true, $data);
    }

    public function view(Request $request) {
        $ticket = Ticket::with(['user', 'assigned_user', 'discussions'])->hashidOrFail($request->ticket_id);
        $data = array(
            'ticket' => $ticket,
        );
        return api_response(true, $data);
    }

    public function save(Request $request){
        $validation = \Validator::make($request->all(), [
            'subject' => ['required', 'string', 'max:180'],
            'type' => ['required', 'string', 'in:general,payment,technical'],
            'msg' => ['required', 'string'],
        ]);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }

        $ticket = new Ticket();
        $ticket->subject = $request->subject;
        $ticket->msg = $request->msg;
        $ticket->status = 'open';
        $ticket->user_id = $this->current_user()->id;
        $ticket->priority = 'low';
        $ticket->type = $request->type;
        $ticket->save();

        $admins = \App\Admin::notifiables();

        foreach($admins as $admin){
            $admin->notify(new SupportTicketAdded($ticket));
        }

        $ticket = $ticket->with(['user', 'assigned_user', 'discussions'])->first();
        $data = array(
            'ticket' => $ticket,
        );
        return api_response(true, $data, 'Support Ticket has been added. Our team will contact you soon');
    }


    public function send_message(Request $request){
        $validation = \Validator::make($request->all(), [
            'ticket_id' => ['required', 'string'],
            'msg' => ['required', 'string'],
        ]);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }

        $ticket = Ticket::hashidFind($request->ticket_id);
        $ticket_discussion = new TicketDiscussion();
        $ticket_discussion->msg = $request->msg;
        $ticket_discussion->user_id = $this->current_user()->id;
        $ticket_discussion->ticket_id = $ticket->id;
        $ticket_discussion->save();

        $admins = \App\Admin::notifiables();
        
        foreach($admins  as $admin){
            $admin->notify(new SupportTicketMessageAdded($request->ticket_id));
        }
        return api_response(true, ['msg' => $ticket_discussion], 'Message have been sent');
    }

    public function deleteMsg(Request $request)
    {        
        $discussion = TicketDiscussion::hashidOrFail($request->msg_id);
        $discussion->delete();
        return api_response(true, null, 'Support ticket deleted successfully');
    }

}

