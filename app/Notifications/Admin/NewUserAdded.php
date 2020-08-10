<?php

namespace App\Notifications\Admin;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class NewUserAdded extends Notification
{
    use Queueable;
    private $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                    ->line(new HtmlString('<h4>New User Added!</h4>'))
                    ->line(new HtmlString('<p>Following are the details from user!</p>'))
                    ->line(new HtmlString('<p>Name: <strong>'.$this->user->full_name.'</strong></p>'))
                    ->line(new HtmlString('<p>Email: <strong>'.$this->user->email.'</strong></p>'))
                    ->line(new HtmlString('<p>User Type: <strong>'.$this->user->user_type.'</strong></p>'))
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
            'link' =>  '/users',
            'route' => 'admin.users',
            'type' => 'user_added',
            'icon' => 'user',
            'color' => '#5fba26',
            'msg' => 'New User Added'
        ];
    }
}
