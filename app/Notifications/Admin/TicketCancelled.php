<?php

namespace App\Notifications\Admin;

use App\Channels\FCMChannel;
use App\Models\ProviderSchedule;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class TicketCancelled extends Notification
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
        return ['mail', 'database', FCMChannel::class];
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
                    ->line(new HtmlString('<h4>Your Ticket# '.$this->ticket->ticket_no.' has been cancelled!</h4>'))
                    ->line(new HtmlString('<p>Following is the reason for cancellation by provider!</p>'))
                    ->line(new HtmlString('<p>Cancelled At: <strong>'.get_fulltime($this->ticket->cancelled_at).'</strong></p>'))
                    ->line(new HtmlString('<p>Reason#: <strong>'.$this->ticket->cancelled_reason.'</strong></p>'))
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
            'type' => 'transaction_cancelled',
            'icon' => 'times',
            'color' => '#cc0000',
            'msg' => 'Ticket #'.$this->ticket->ticket_no.' Cancelled'
        ];
    }

    public function toFCM($notifiable)
    {
        return [
            "title" => 'Ticket # '.$this->ticket->ticket_no.' has been Cancelled',
            "msg" => 'Your ticket has been cancelled by provider and the reason given is: '.$this->ticket->cancelled_reason.'.'
        ];
    }
}
