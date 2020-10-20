<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use App\Models\Category;
use App\Models\City;
use App\Models\Provider;
use App\Models\ProviderSchedule;
use App\Models\ScheduleSeat;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Admin\TicketCancelled;
use App\Notifications\Admin\TicketTransactionVerified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends AdminController {

    public function index(Request $request) {
        $tickets = Ticket::with(['schedule', 'schedule.category', 'seats', 'user'])->has('schedule');
        $from = $request->from ?? date('Y-m-01');
        $to = $request->to ?? date('Y-m-t');
        $user = auth('admin')->user();

        if(!empty($request->ticket_no)){
            $tickets->whereTicketNo($request->ticket_no);
        }else{
            if(!empty($from) && !empty($to)){
                $tickets->whereBetween('booking_date', [$from, $to]);
            }
        }

        if(!$user->is_admin){
            $tickets->whereProviderId($user->provider_id);
        }

        $data = array(
            'title' => 'All Tickets',
            'from' => $from,
            'to' => $to,
            'tickets' => $tickets->orderBy('booking_date', 'asc')->get()
        );
        return view('admin.tickets.all_tickets')->with($data);
    }

    public function verify(Request $request) {
        $ticket = Ticket::with('schedule')->getTicket($request->ticket_no);

        $user = auth('admin')->user();
        if(!$user->is_admin){
            $ticket->whereProviderId($user->provider_id);
        }

        $ticket->status = 'booked';
        $ticket->booked_at = date('Y-m-d H:i:s');
        $ticket->save();

        User::find($ticket->user_id)->notify(new TicketTransactionVerified($ticket));

        return response()->json([
            'success' => 'Ticket has been booked',
            'redirect' => route('admin.tickets'),
        ]);
    }

    public function cancel(Request $request) {
        $rules = [
            'cancel_reason' => ['required', 'string', 'min:10'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }
        $ticket = Ticket::getTicket($request->ticket_no);
        $user = auth('admin')->user();
        if(!$user->is_admin){
            $ticket->whereProviderId($user->provider_id);
        }
        $ticket->status = 'cancelled';
        $ticket->cancelled_at = date('Y-m-d H:i:s');
        $ticket->cancelled_reason = $request->cancel_reason;
        $ticket->save();

        User::find($ticket->user_id)->notify(new TicketCancelled($ticket));

        return response()->json([
            'success' => 'Ticket has been cancelled',
            'redirect' => route('admin.tickets'),
        ]);
    }
}
