<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue; // Optional: implement if queueing needed
use Illuminate\Notifications\Messages\MailMessage;

class SystemNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $type; // e.g., 'info', 'success', 'warning', 'error'
    public $link;

    /**
     * Create a new notification instance.
     *
     * @param string $title
     * @param string $message
     * @param string $type
     * @param string|null $link
     */
    public function __construct($title, $message, $type = 'info', $link = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->link = $link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'link' => $this->link,
        ];
    }
}
