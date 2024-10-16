<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BidWonNotification extends Notification
{
    use Queueable;

    protected $bid;

    public function __construct($bid)
    {
        $this->bid = $bid;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('You Won the Bid!')
            ->greeting('Congratulations!')
            ->line('You have won the bid for the project: ' . $this->bid->project->name)
            ->action('View Project', url('/projects/' . $this->bid->project->id));
    }
}
