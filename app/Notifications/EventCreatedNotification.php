<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventCreatedNotification extends Notification
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'event_id' => $this->event->id,
            'message' => "Un nouvel événement a été créé : " . $this->event->title,
        ];
    }

    /**
     * Définir les données à diffuser via WebSockets (Pusher).
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'event_id' => $this->event->id,
            'message' => "Un nouvel événement a été créé : " . $this->event->title,
        ]);
    }

}
