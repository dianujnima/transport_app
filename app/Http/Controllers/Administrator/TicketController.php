<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use App\Notifications\SupportTicketMessageAdded;
use App\Notifications\SupportTicketUpdated;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends AdminController {

    public function index(Request $request) {
        $type = $request->type;
        $tickets = Ticket::with(['user']);

        if($type == 'closed'){
            $tickets->whereStatus('closed');
        }else{
            $tickets->where('status', '!=', 'closed');
        }

        $all_tickets = Ticket::all();

        $data = array(
            'title' => 'Support Tickets',
            'tickets' => $tickets->orderby('updated_at')->get(),
            'total_tickets' => $all_tickets->count(),
            'pending_tickets' => $all_tickets->where('status', '!=', 'closed')->count(),
            'closed_tickets' => $all_tickets->where('status', 'closed')->count(),
        );
        return view('admin.tickets.all_tickets')->with($data);
    }

    public function view(Request $request) {
        $ticket = Ticket::with(['user'])->hashidFind($request->ticket_id);
        $data = array(
            'title' => 'Update Ticket #'.$ticket->hashid,
            'ticket' => $ticket,
            'discussions' => \App\Models\TicketDiscussion::with(['user'])->whereTicketId($ticket->id)->orderby('created_at', 'DESC')->get()
        );

        return view('admin.tickets.update_ticket')->with($data);
    }

    public function send_message(Request $request){
        $ticket = Ticket::hashidFind($request->ticket_id);
        
        $ticket_discussion = new \App\Models\TicketDiscussion();
        $ticket_discussion->msg = $request->msg;
        $ticket_discussion->user_id = auth('admin')->user()->id;
        $ticket_discussion->ticket_id = $ticket->id;
        $ticket_discussion->is_support = 1;
        $ticket_discussion->save();

        \App\Models\User::find($ticket->user_id)->notify(new SupportTicketMessageAdded($ticket->hashid));

        return response()->json([
            'success' => 'Message have been updated',
            'reload' => true,
        ]);
    }

    public function updateStatus(Request $request){
        $ticket_id = $request->ticket_id;
        $type = $request->type;
        $value = $request->value;

        $ticket = Ticket::hashidFind($ticket_id);

        if($type == 'status'){
            $ticket->status = $value;
            if($value == 'closed'){
                $ticket->closed_at = now();
                $ticket->closed_by = auth('admin')->user()->id;
            }
        }else{
            $ticket->priority = $value;
        }
        $ticket->save();

        \App\Models\User::find($ticket->user_id)->notify(new SupportTicketUpdated($ticket, $type));
        
        return response()->json([
            'msg' => 'Ticket #'.$ticket_id.'\'s '.$request->type.' have been updated',
        ]);
    }

    public function delete(Request $request)
    {        
        $ticket = Ticket::hashidFind($request->ticket_id);
        $ticket->delete();
        return response()->json([
            'success' => 'Support ticket deleted successfully',
            'remove_tr' => true
        ]);
    }

}
