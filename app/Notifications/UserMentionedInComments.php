<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MentionNotification extends Notification
{
    use Queueable;

    /**
     * The Comment instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * Create a new notification instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        // The instance `$this->model` represent the `Comment` model.
        $username = $this->model->user->nome;
        $modelId = $this->model->getKey();

        $message = "<strong>@{ $username }</strong> ti ha menzionato in un commento";

        // You could (and probably should) use a route name here with the function `route()`.
        $link = "/comment/show/{ $modelId }";

        return [
            'message' => $message,
            'link' => $link,
            'type' => 'mention'
        ];
    }
}
