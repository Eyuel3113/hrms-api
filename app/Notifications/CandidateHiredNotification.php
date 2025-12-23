<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CandidateHiredNotification extends Notification
{
    use Queueable;

    public $candidateName;
    public $jobTitle;

    /**
     * Create a new notification instance.
     */
    public function __construct($candidateName, $jobTitle)
    {
        $this->candidateName = $candidateName;
        $this->jobTitle = $jobTitle;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Congratulations! You are Hired')
                    ->greeting('Hello ' . $this->candidateName . ',')
                    ->line('We are pleased to inform you that you have been hired for the position of ' . $this->jobTitle . '.')
                    ->line('We will contact you soon with further details regarding your onboarding.')
                    ->salutation('Regards, HRMS Team');
    }
}
