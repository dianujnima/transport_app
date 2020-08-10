<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportTicketAdded extends Notification
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
        return (new MailMessage)
                    ->line('New Support ticket click on the link below to go to ticket.')
                    ->action('Ticket #'.$this->ticket->hashid, route('admin.tickets.view', $this->ticket->hashid))
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
            'link' =>  'support-tickets/view/'.$this->ticket->hashid,
            'route' => 'tickets.view',
            'type' => 'ticket_added',
            'icon' => 'ticket',
            'color' => '#d87017',
            'msg' => 'New ticket added ('.$this->ticket->subject.')'
        ];
    }
}
