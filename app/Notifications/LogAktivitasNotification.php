<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LogAktivitasNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $log;
    public $message;
    public $url;

    /**
     * Create a new notification instance.
     */
    public function __construct($log, $message, $url)
    {
        $this->log = $log;
        $this->message = $message;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'log_id' => $this->log->id ?? null,
            'pengajuan_id' => $this->log->kendaraan->pengajuan_id ?? null,
            'message' => $this->message,
            'url' => $this->url,
        ];
    }
}
