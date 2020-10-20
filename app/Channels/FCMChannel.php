<?php
namespace App\Channels;

use Illuminate\Notifications\Notification;

class FCMChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        define('API_ACCESS_KEY', config('app.fcm_api_key'));

        if(empty(API_ACCESS_KEY)){
            return;
        }

        $message = $notification->toFCM($notifiable);
        $device_tokens = $notifiable->device_tokens->toarray();
        $fcm_msg = array(
            'body' => $message['msg'],
            'title' => $message['title'],
            'sound' => "default",
            'color' => "#203E78",
        );
        $fcm_fields = array(
            'registration_ids' => $device_tokens,
            'priority' => 'high',
            'notification' => $fcm_msg
        );

        $headers = array(
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcm_fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        // echo $result . "\n\n";
    }
}