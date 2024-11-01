<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BidAccepted extends Notification
{
    use Queueable;

    public $project;

    public function __construct($project)
    {
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $email = (new MailMessage)
            ->subject('Your Bid has been Accepted!')
            ->line('Congratulations! Your bid for the project "' . $this->project->name . '" has been accepted.')
            ->action('View Project', url('/projects/' . $this->project->slug))
            ->line('Thank you for using our platform!');

        // Add CC to "doni@studiofivecorp.com" for project creator
        if ($notifiable->id === $this->project->created_by) {
            $email->cc('doni@studiofivecorp.com');
        }

        return $email;
    }
}
