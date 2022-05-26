<?php

namespace Classiebit\Eventmie\Notifications;
use Classiebit\Eventmie\Notifications\CustomDb;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public  $mail_data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($mail_data)
    {
        $this->mail_data =  (object) $mail_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // if mail creds available then send mail+database notification
        if(checkMailCreds())
            return ['mail', CustomDb::class];
            
        // else only database notification
        return [CustomDb::class];
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
                ->subject($this->mail_data->mail_subject)
                ->markdown('eventmie::mail.booking', ['mail_data'=>$this->mail_data]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'notification'  => $this->mail_data,
            'n_type'        => $this->mail_data->n_type,
        ];
        
    }
}
