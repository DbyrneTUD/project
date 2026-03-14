<?php

namespace App\Notifications;

use App\Models\LiftRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LiftAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected LiftRequest $liftRequest)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $lr = $this->liftRequest;
        $url = url('/groups/'.$lr->group_id.'/requests/'.$lr->id);

        return (new MailMessage)
            ->subject('Lift Request Accepted')
            ->greeting('Hello!')
            ->line("Your lift request you posted in the group: {$lr->group->name} has been accepted")
            ->line("From: {$lr->origin}")
            ->line("To: {$lr->destination}")
            ->action('View Request', $url)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        $lr = $this->liftRequest;

        return [
            'message' => "Your lift request from {$lr->origin} to {$lr->destination} that you posted in the group: {$lr->group->name} has been accepted.",
            'url' => '/groups/'.$lr->group_id.'/requests/'.$lr->id,
        ];
    }
}
