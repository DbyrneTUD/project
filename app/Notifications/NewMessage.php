<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessage extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Message $message)
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
        $message = $this->message;
        $url = url('/trips/'.$message->trip_id.'/messages');

        return (new MailMessage)
            ->subject('New Chat Message')
            ->greeting('Hello!')
            ->line("{$message->user->name} sent you a new chat message.")
            ->action('Open Chat', $url)
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
        $message = $this->message;

        return [
            'message' => "{$message->user->name} sent you a new chat message.",
            'url' => '/trips/'.$message->trip_id.'/messages',
        ];
    }
}
