<?php

namespace App\Notifications\Admin;

use App\Models\ProviderSchedule;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class TransactionIdAdded extends Notification
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
                    ->line(new HtmlString('<h4>Transaction ID has been added for Ticket# '.$this->ticket->ticket_no.'!</h4>'))
                    ->line(new HtmlString('<p>Following are the details of transaction!</p>'))
                    ->line(new HtmlString('<p>Ticket#: <strong>'.$this->ticket->ticket_no.'</strong></p>'))
                    ->line(new HtmlString('<p>Transaction ID: <strong>'.$this->ticket->transaction_id.'</strong></p>'))
                    ->line(new HtmlString('<br/><p>Please check the transaction id and confirm the booking of this ticket as soon as possible. Click below button to directly view the ticket details.</p>'))
                    ->line(' ')
                    ->action('View Ticket', route('admin.tickets.view', $this->ticket->ticket_no))
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
            'link' =>  '/tickets/view/'.$this->ticket->ticket_no,
            'route' => 'admin.tickets.view',
            'type' => 'transaction_added',
            'icon' => 'dollar',
            'color' => '#d62929',
            'msg' => 'Transaction Added For Ticket #'.$this->ticket->ticket_no
        ];
    }
}
