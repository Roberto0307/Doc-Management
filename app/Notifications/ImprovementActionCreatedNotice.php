<?php

namespace App\Notifications;

use App\Models\ImprovementAction;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ImprovementActionCreatedNotice extends Notification
{
    use Queueable;

    private $improvementAction;

    /**
     * Create a new notification instance.
     */
    public function __construct(ImprovementAction $improvementAction)
    {
        $this->improvementAction = $improvementAction;
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
            ->subject('Create new Improvement Action')
            ->greeting('Hi '.$notifiable->name.',')
            ->line('The introduction to the notification. '.$this->improvementAction->title)
            ->line('Segundo parrafal  '.$this->improvementAction->description)
            ->action('Notification Action', url('/'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(User $notifiable): array
    {
        return FilamentNotification::make()
            ->title($this->improvementAction->title)
            ->body('Created a new Improvement Action!')
            ->icon('heroicon-o-rectangle-stack')
            ->color('primary')
            ->status('primary')
            ->getDatabaseMessage();
    }
}
