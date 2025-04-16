<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\File;
use App\Models\User;
use App\Models\Status;
use Filament\Notifications\Notification as FilamentNotification;

class FileStatusUpdated extends Notification
{
    use Queueable;

    private $file;
    private $status;
    private $responses;


    /**
     * Create a new notification instance.
     */
    public function __construct(File $file,  $status, $responses = null)
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
            ->greeting('Hi ' . $notifiable->name . ',')
            ->line('The status of the document "' . $this->file->title . '" has changed to: ' . strtoupper($this->status));

        // Solo agregar la línea si $this->responses no es null ni vacío
        if (!empty($this->responses)) {
            $mailMessage->line('Important state information: ' . $this->responses);
        }

        return $mailMessage->action('View Document', url('/storage/' . $this->file->file_path));
    }

    /**
     * Guardar la notificación en la base de datos.
     */
    public function toDatabase(User $notifiable)
    {

        $statusTitle = Status::titleFromDisplayName( $this->status );

        $color = Status::colorFromTitle($statusTitle);

        return FilamentNotification::make()
                ->title( $this->file->title )
                ->body( 'Document status: ' . strtoupper($this->status) )
                ->icon('heroicon-o-document-text')
                ->color($color)
                ->status($color)
                ->getDatabaseMessage();
    }

}
