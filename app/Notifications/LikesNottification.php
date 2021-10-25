<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LikesNottification extends Notification
{
    use Queueable;

    protected $reply;

      /**
     * Create a new notification instance.
     *
     * @ return void
     * @param $reply
     */
    public function __construct($reply)//a
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        //return ['mail'];
        return ['database'];
    }

            /**
             * Get the mail representation of the notification.
             *
             * @ param  mixed  $notifiable
             * @ return \Illuminate\Notifications\Messages\MailMessage
             */
            //public function toMail($notifiable)
            //{
                //return (new MailMessage)
                            //->line('The introduction to the notification.')
                            //->action('Notification Action', url('/'))
                            //->line('Thank you for using our application!');
            //}

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */

    public function toArray($notifiable) //Likes Notification
    {
        return [
            'message' => $this->reply['user_name'] . 'Likes one of your thread in:' . $this->reply['thread_title'],
            'link' => $this->reply['thread_path'] //a
        ];
    }
}
