<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportTicketUpdated extends Notification
{
    use Queueable;
    private $ticket, $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, $type)
    {
        $this->ticket = $ticket;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        if($this->type == 'status'){
            $msg = 'Status for ticket #'.$this->ticket->hashid.' has changed to '.$this->ticket->status;
        }else{
            $msg = 'Priority for ticket #'.$this->ticket->hashid.' has changed to '.$this->ticket->priority;
        }
        return [
            'link' =>  'support-tickets/view/'.$this->ticket->hashid,
            'route' => 'tickets.view',
            'type' => 'ticket_udpated',
            'icon' => 'ticket-alt',
            'color' => '#d87017',
            'msg' => $msg
        ];
    }
}
