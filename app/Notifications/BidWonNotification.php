<?php

namespace App\Notifications;

use App\Models\Bid;
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
            ->subject('Congratulations, You Won the Bid!')
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have won the bid for the project: ' . $this->bid->project->name)
            ->line('Your bid amount: $' . number_format($this->bid->amount, 2))
            ->action('Accept Bid', route('bids.accept', ['bid' => $this->bid->id]))
            // ->action('Reject Bid', route('bids.reject', ['bid' => $this->bid->id]))
            ->line('You must accept or reject this bid within 24 hours. Otherwise, the bid will be automatically canceled.')
            ->line('Thank you for using our platform!')
            ->salutation('Regards, Jobs at Studio Five');
        }
}
