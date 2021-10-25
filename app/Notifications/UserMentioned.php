<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Models\Reply;
use App\Models\Thread;

class UserMentioned extends Notification
{
    use Queueable;

    protected $reply;

    /**
     * Create a new notification instance.
     *
     * @ return void
     * @param $reply
     */
    public function __construct($reply)//
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


    // /**
    // * Get the mail representation of the notification.
    //*
    // * @param  mixed  $notifiable
    //* @return \Illuminate\Notifications\Messages\MailMessage
    //*/
    //public function toMail($notifiable)
    //{
    // return (new MailMessage)
    //               ->line('The introduction to the notification.')
    //               ->action('Notification Action', url('/'))
    //               ->line('Thank you for using our application!');
    //}
   

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable) //
    {

        //dd($this->reply);

        return [
            //'message' => $this->reply->owner->name . 'mentioned you in ' . $this->reply->thread->title,
            //'message' => $this->reply['user_id'] . 'mentioned you in ' . $this->reply['thread_title'],
            'message' => $this->reply['user_name'] . ' mentioned you in ' . $this->reply['thread_title'],
            //'link' => $this->reply->path() 
            //'link' => '/threads/' . $this->reply['thread_id'] . '/replies'
            'link' => $this->reply['thread_path']
        ];
    }
}
