<?php

namespace App\Notifications\Admin;

use App\Models\ProviderSchedule;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class NewTicketBooked extends Notification
{
    use Queueable;
    private $ticket;
    private $schedule;
    private $seats;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, ProviderSchedule $schedule, $seats = [])
    {
        $this->ticket = $ticket;
        $this->schedule = $schedule;
        $this->seats = $seats;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $all_seats = '';
        foreach($this->seats as $v){
            $all_seats .= $v['total_seats'].' '.ucwords($v['seat_type']).' Seats'. ', ';
        }
        return (new MailMessage)
                    ->line(new HtmlString('<h4>New Ticket has been booked!</h4>'))
                    ->line(new HtmlString('<p>Following are the details of ticket!</p>'))
                    ->line(new HtmlString('<p>Ticket#: <strong>'.$this->ticket->ticket_no.'</strong></p>'))
                    ->line(new HtmlString('<p>Seat(s): <strong>'.$all_seats.'</strong></p>'))
                    ->line(new HtmlString('<p>From: <strong>'.$this->schedule->route_from.'</strong></p>'))
                    ->line(new HtmlString('<p>To: <strong>'.$this->schedule->route_to.'</strong></p>'))
                    ->line(new HtmlString('<p>Booking Date: <strong>'.get_date($this->ticket->booking_date).'</strong></p>'))
                    ->line(new HtmlString('<p>Total Amount: <strong>'.get_price($this->ticket->total_amount).'</strong></p>'))
                    ->line('Thank You!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'link' =>  '/tickets/?ticket_no='.$this->ticket->ticket_no,
            'route' => 'admin.tickets',
            'type' => 'ticket_added',
            'icon' => 'ticket',
            'color' => '#5fba26',
            'msg' => 'New Ticket #'.$this->ticket->ticket_no.' Added'
        ];
    }
}
