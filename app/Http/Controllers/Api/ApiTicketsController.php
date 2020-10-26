<?php

namespace App\Http\Controllers\Api;

use App\Models\ProviderSchedule;
use App\Models\ScheduleSeat;
use App\Models\Ticket;
use App\Models\TicketSeat;
use App\Notifications\Admin\NewTicketBooked;
use App\Notifications\Admin\TransactionIdAdded;
use Illuminate\Http\Request;

class ApiTicketsController extends ApiController
{

    public function search(Request $request)
    {
        $tickets = ProviderSchedule::with('category', 'seats')->has('provider')->join('schedule_seats', 'schedule_seats.schedule_id', '=', 'provider_schedules.id')->select('provider_schedules.*', 'schedule_seats.cost as starting_at_cost', 'schedule_seats.seat_type')->groupBy('provider_schedules.id')->where('is_active', 1);

        if (!empty($request->from)) {
            $tickets->whereRouteFrom($request->from);
        }
        if (!empty($request->to)) {
            $tickets->whereRouteTo($request->to);
        }

        if (!empty($request->date)) {
            $tickets->where('date', $request->date);
        }

        if (!empty($request->type)) {
            $tickets->whereType($request->type);
        }

        if (!empty($request->category)) {
            $tickets->whereCategoryId($request->category);
        }

        if (!empty($request->timing_from) && !empty($request->timing_to)) {
            $tickets->whereBetween('arrival_time', [$request->timing_from, $request->timing_to]);
        }

        if (!empty($request->providers) && safeCount($request->providers) > 0) {
            $tickets->whereIn('provider_id', $request->providers);
        }

        if(!empty($request->orderby_seat_cost)){
            $tickets->orderBy('schedule_seats.cost', $request->orderby_seat_cost);
        }

        $data = array(
            'tickets' => $tickets->paginate(config('app.pagination.limit'))
        );
        return api_response(true, $data);
    }

    public function book_ticket(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'seats' => ['required', 'array', 'max:5'],
            'schedule_id' => ['required', 'string'],
        ]);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }

        $schedule = ProviderSchedule::hashidFind($request->schedule_id);
        $scheduleSeat = ScheduleSeat::whereScheduleId($schedule->id);
        $tickets = Ticket::whereScheduleId($schedule->id)->whereIn('status', ['hold', 'booked'])->get('id')->pluck('id');
        $bookedSeats = TicketSeat::whereScheduleId($schedule->id)->whereIn('booking_id', $tickets)->get();

        $seats = $scheduleSeat->get();
        $total_amount = 0;

        $newly_booked_seats = [];

        foreach ($request->seats as $k => $seat) {
            //getting the specific type of seats
            $seat_type = $seats->where('seat_type', $k)->first();

            //adding the already booked ticket seats with the current seats coming from api
            $already_booked = $bookedSeats->where('seat_type', $k)->sum('total_seats');
            if (($already_booked  + $seat) > $seat_type->total_seats) {
                $left_seats = $seat_type->total_seats - $already_booked;
                return api_response(false, null, 'There are only '.$left_seats.' seats left in '. ucwords($k) . ' type!');
            }
            // multiplying the number of seats with the actual cost of the seat
            $seats_costs = $seat_type->cost * $seat;

            $newly_booked_seats[] = array(
                'schedule_id' => $schedule->id,
                'total_seats' => $seat,
                'seat_cost' => $seat_type->cost,
                'total_cost' => $seats_costs,
                'seat_type' => $k
            );

            $total_amount += $seats_costs;
        }

        $schedule_data = $schedule;
        $schedule_data->seats = $seats;

        $booking_ticket = new Ticket();
        $booking_ticket->ticket_no = strtoupper(\Str::random(5)) . time();
        $booking_ticket->provider_id = $schedule->provider_id;
        $booking_ticket->schedule_id = $schedule->id;
        $booking_ticket->user_id = $this->current_user()->id;
        $booking_ticket->total_amount = $total_amount;
        $booking_ticket->booking_date = $schedule->date;
        $booking_ticket->status = 'hold';
        $booking_ticket->schedule_data = $schedule;
        $booking_ticket->save();
        foreach ($newly_booked_seats as $k => $v) {
            $newly_booked_seats[$k]['booking_id'] = $booking_ticket->id;
        }
        TicketSeat::insert($newly_booked_seats);
        
        //notifying all admins
        $admins = \App\Models\Admin::notifiables();
        foreach ($admins as $admin) {
            $admin->notify(new NewTicketBooked($booking_ticket, $schedule, $newly_booked_seats));
        }
        //notifying provider
        $provider_user_id = \App\Models\Provider::find($schedule->provider_id)->user_id;
        \App\Models\Admin::find($provider_user_id)->notify(new NewTicketBooked($booking_ticket, $schedule, $newly_booked_seats));

        $data = array('ticket_no' => $booking_ticket->ticket_no);

        return api_response(true, $data, 'Your order has been booked but its on hold please pay via easypaisa franchise within 15 minutes and enter transaction id in the field below to confirm. Otherwise you order will the automatically cancelled!');
    }

    public function update_ticket(Request $request)
    {
        $ticket = Ticket::getTicket($request->ticket_no);
        if($ticket->transaction_id !== null){
            return api_response(false, null, 'Transaction for this ticket already exists');
        }

        if($ticket->status == 'cancelled'){
            return api_response(false, null, 'Your order has been cancelled!');
        }

        $ticket->transaction_id = $request->transaction_id;
        $ticket->transaction_data = $request->data ?? null;
        $ticket->transaction_method = $request->method ?? 'easy_paisa';
        $ticket->transaction_at = date('Y-m-d H:i:s');
        $ticket->save();

        $admins = \App\Models\Admin::notifiables();
        foreach ($admins as $admin) {
            $admin->notify(new TransactionIdAdded($ticket));
        }

        //notifying provider
        $provider_user_id = \App\Models\Provider::find($ticket->provider_id)->user_id;
        \App\Models\Admin::find($provider_user_id)->notify(new TransactionIdAdded($ticket));

        return api_response(true, null, 'Thanks for ordering with us you will get the confirmation email and notification shortly once we checked the transaction!');
    }

    public function all_tickets(Request $request)
    {
        $user = $this->current_user();
        $tickets = Ticket::with(['schedule', 'provider', 'schedule.category', 'seats'])->has('schedule')->whereUserId($user->id);
        if(!empty($request->ticket_no)){
            $data = array(
                'ticket' => $tickets->whereTicketNo($request->ticket_no)->first()
            );
            return api_response(true, $data);
        }
        if (!empty($request->past_orders) && $request->past_orders == true) {
            $tickets = $tickets->where('booking_date', '<', date('Y-m-d'));
        }else{
            $tickets = $tickets->where('booking_date', '>=', date('Y-m-d'));
        }
        $data = array(
            'tickets' => $tickets->orderBy('booking_date', 'desc')->paginate(config('app.pagination.limit'))
        );
        return api_response(true, $data);
    }
}