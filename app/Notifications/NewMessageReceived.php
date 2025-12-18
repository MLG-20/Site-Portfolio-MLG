<?php

namespace App\Notifications; // <--- Vérifie que c'est bien écrit comme ça

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageReceived extends Notification
{
    use Queueable;

    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nouveau message sur ton Portfolio !')
                    ->greeting('Salut Mamadou,')
                    ->line('Tu as reçu un nouveau message de : ' . $this->message->name)
                    ->line('Sujet : ' . $this->message->subject)
                    ->line('"' . $this->message->message . '"')
                    ->action('Voir le message dans le Dashboard', url('/admin/messages'));
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
}