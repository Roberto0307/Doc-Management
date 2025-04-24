<?php

namespace App\Notifications;

use App\Models\Record;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecordCreatedNotice extends Notification
{
    use Queueable;

    private $record;

    /**
     * Create a new notification instance.
     */
    public function __construct(Record $record)
    {
        $this->record = $record;
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
        return (new MailMessage)
            ->subject('Document record')
            ->greeting('Hi '.$notifiable->name.',')
            ->line('You have successfully created a new record! "'.$this->record->title.'"')
            ->action(
                'Manage your files',
                route('filament.dashboard.resources.files.index',
                    ['record' => $this->record->id])
            );

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(User $notifiable)
    {
        return FilamentNotification::make()
            ->title($this->record->title)
            ->body('Created a new record!')
            ->icon('heroicon-o-clock')
            ->color('info')
            ->status('info')
            ->getDatabaseMessage();
    }
}
