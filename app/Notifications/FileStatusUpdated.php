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

    private $changeReason;

    /**
     * Create a new notification instance.
     */
    public function __construct(File $file, Status $status, $changeReason = null)
    {
        //
        $this->file = $file;
        $this->status = $status;
        $this->changeReason = $changeReason;
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

        // Solo agregar la línea si $this->changeReason no es null ni vacío
        if (! empty($this->changeReason)) {
            $mailMessage->line('Important state information: '.$this->changeReason);
        }

        return $mailMessage->action(
            'See details',
            route('filament.dashboard.resources.records.files.list',
                ['recordId' => $this->file->record_id])
        );
    }

    /**
     * Guardar la notificación en la base de datos.
     */
    public function toDatabase(User $notifiable)
    {
        return FilamentNotification::make()
            ->title($this->file->title)
            ->body('Document status: '.ucfirst(strtolower($this->status->label)))
            ->icon($this->status->iconName())
            ->color($this->status->colorName())
            ->status($this->status->colorName())
            ->getDatabaseMessage();
    }
}
