<?php

namespace App\Http\Controllers\Api;

use App\Models\ProviderSchedule;
use App\Models\ScheduleSeat;
use App\Models\Ticket;
use App\Notifications\Admin\NewTicketBooked;
use App\Notifications\Admin\TransactionIdAdded;
use Illuminate\Http\Request;

class ApiTicketsController extends ApiController {

    public function search(Request $request) {
        $tickets = ProviderSchedule::with('category', 'availableSeats')->orderBy('date', 'desc');

        if(!empty($request->from)){
            $tickets->whereRouteFrom($request->from);
        }
        if(!empty($request->to)){
            $tickets->whereRouteTo($request->to);
        }

        if(!empty($request->date)){
            $tickets->where('date', $request->date);
        }

        if(!empty($request->type)){
            $tickets->whereType($request->type);
        }

        if(!empty($request->category)){
            $tickets->whereCategoryId($request->category);
        }

        if(!empty($request->operators) && safeCount($request->operators) > 0){
            $tickets->whereIn('operator_id',$request->operators);
        }

        if(!empty($request->timing_from) && !empty($request->timing_to)){
            $tickets->whereBetween('arrival_time', [$request->timing_from, $request->timing_to]);
        }

        $data = array(
            'tickets' => $tickets->paginate(config('app.pagination.limit'))
        );
        return api_response(true, $data);
    }

    public function book_ticket(Request $request) {
        $validation = \Validator::make($request->all(), [
            'seats' => ['required', 'array', 'max:10'],
            'schedule_id' => ['required', 'string'],
        ]);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }

        $schdule = ProviderSchedule::hashidFind($request->schedule_id);
        $scheduleSeat = ScheduleSeat::whereInHashids($request->seats);
        $seats = $scheduleSeat->get();
        $total_amount = 0;
        foreach($seats as $seat){
            if($seat->status !== 'available'){
                return api_response(false, null, 'Seat# '.$seat->seat_no.' is not available anymore!');
            }
            $total_amount += $schdule->ticket_cost;
        }

        $booking_ticket = new Ticket();
        $booking_ticket->ticket_no = strtoupper(\Str::random(5)).time();
        $booking_ticket->provider_id = $schdule->provider_id;
        $booking_ticket->schedule_id = $schdule->id;
        $booking_ticket->user_id = $this->current_user()->id;
        $booking_ticket->seat_ids = $seats->pluck('id');
        $booking_ticket->seat_nos = $seats->pluck('seat_no');
        $booking_ticket->total_amount = $total_amount;
        $booking_ticket->per_seat_amount = $schdule->ticket_cost;
        $booking_ticket->booking_date = $schdule->date;
        $booking_ticket->status = 'hold';
        $booking_ticket->save();

        $scheduleSeat->update(['status' => 'booked']);

        $admins = \App\Models\Admin::notifiables();
        foreach($admins as $admin){
            $admin->notify(new NewTicketBooked($booking_ticket, $schdule));
        }

        $data = array('ticket_no' => $booking_ticket->ticket_no);
        
        return api_response(true, $data, 'Your order has been booked but its on hold please pay via easypaisa franchise within 15 minutes and enter transaction id in the field below to confirm. Otherwise you order will the automatically cancelled!');
    }

    public function update_ticket(Request $request){
        $ticket = Ticket::getTicket($request->ticket_no);
        $ticket->transaction_id = $request->transaction_id;
        $ticket->transaction_data = $request->data ?? null;
        $ticket->transaction_method = $request->method ?? null;
        $ticket->save();

        $admins = \App\Models\Admin::notifiables();
        foreach($admins as $admin){
            $admin->notify(new TransactionIdAdded($ticket));
        }

        return api_response(true, null, 'Thanks for ordering with us you will get the confirmation email and notification shortly once we checked the transaction!');
    }

    public function all_tickets() {
        $user = $this->current_user();
        $tickets = Ticket::with(['schedule', 'provider', 'schedule.category'])->has('schedule')->whereUserId($user->id);
        $data = array(
            'tickets' => $tickets->paginate(config('app.pagination.limit'))
        );
        return api_response(true, $data);
    }

}

