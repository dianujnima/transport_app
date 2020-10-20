<?php

namespace App\Notifications\Admin;

use App\Models\ProviderSchedule;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class TicketTransactionVerified extends Notification
{
    use Queueable;
    private $ticket;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database', \App\Channels\FCMChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line(new HtmlString('<h4>Transaction verification has been approved for Ticket# '.$this->ticket->ticket_no.'!</h4>'))
                    ->line(new HtmlString('<p>Following are the details of you ticket!</p>'))
                    ->line(new HtmlString('<p>Ticket#: <strong>'.$this->ticket->ticket_no.'</strong></p>'))
                    ->line(new HtmlString('<p>Booking Date: <strong>'.get_date($this->ticket->schedule->booking_date).'</strong></p>'))
                    ->line(new HtmlString('<p>Route: <strong>'.$this->ticket->schedule->route_from.' - '.$this->ticket->schedule->route_to.'</strong></p>'))
                    ->line(new HtmlString('<p>Arrival Time: <strong>'.get_fulltime($this->ticket->schedule->arrival_time, 'h:i A').' - '.$this->ticket->schedule->to.'</strong></p>'))
                    ->line(new HtmlString('<br/><p>Please be on time at the station to avoid any mishap and delays.</p>'))
                    ->line(' ')
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
            'type' => 'transaction_verified',
            'icon' => 'check',
            'color' => '#009100',
            'msg' => 'Ticket #'.$this->ticket->ticket_no.' Booking Verified'
        ];
    }

    public function toFCM($notifiable)
    {
        return [
            "title" => 'Ticket # '.$this->ticket->ticket_no.' Booking Verified',
            "msg" => 'Your ticket from '.$this->ticket->schedule->route_from.' to '.$this->ticket->schedule->route_to.' for '.get_date($this->ticket->schedule->booking_date).' at '.get_fulltime($this->ticket->schedule->arrival_time, 'h:i A').' has been verified. Please be on time at the station to avoid any mishap and delays.'
        ];
    }
}
