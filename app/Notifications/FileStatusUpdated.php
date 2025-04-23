<?php

namespace App\Notifications;

use App\Models\File;
use App\Models\Status;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FileStatusUpdated extends Notification
{
    use Queueable;

    private $file;

    private $status;

    private $responses;

    /**
     * Create a new notification instance.
     */
    public function __construct(File $file, Status $status, $responses = null)
    {
        //
        $this->file = $file;
        $this->status = $status;
        $this->responses = $responses;
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
        $mailMessage = (new MailMessage)
            ->subject('Document status')
            ->greeting('Hi '.$notifiable->name.',')
            ->line('The status of the document "'.$this->file->title.'"')
            ->line('has changed to: '.ucfirst(strtolower($this->status->label)));

        // Solo agregar la línea si $this->responses no es null ni vacío
        if (! empty($this->responses)) {
            $mailMessage->line('Important state information: '.$this->responses);
        }

        return $mailMessage->action('See details', url('/dashboard/files?record_id='.$this->file->record_id));
    }

    /**
     * Guardar la notificación en la base de datos.
     */
    public function toDatabase(User $notifiable)
    {
        return FilamentNotification::make()
            ->title($this->file->title)
            ->body('Document status: '.strtoupper($this->status->label))
            ->icon($this->status->iconName())
            ->color($this->status->colorName())
            ->status($this->status->colorName())
            ->getDatabaseMessage();
    }
}
