<?php

namespace App\Notifications;

use App\Models\Bid;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BidWonNotification extends Notification
{
    use Queueable;

    protected $bid;

    public function __construct(Bid $bid)
    {
        $this->bid = $bid;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $projectSlug = $this->bid->project->slug; // Using slug for project

        return (new MailMessage)
            ->subject('Congratulations, You Won the Bid for "' . $this->bid->project->name . '"!')
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have won the bid for the project: ' . $this->bid->project->name)
            ->line('Your bid amount: $' . number_format($this->bid->amount, 2))
            ->action('View Project', url('/projects/' . $projectSlug)) // Corrected URL to use project slug
            ->line('Please respond to this bid within 24 hours to confirm your interest. After this period, the bid may be automatically released.')
            ->line('Thank you for using our platform!')
            ->salutation('Regards, Jobs at Studio Five');
        }
}
