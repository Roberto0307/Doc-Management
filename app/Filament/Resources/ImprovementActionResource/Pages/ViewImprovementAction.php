<?php

namespace App\Filament\Resources\ImprovementActionResource\Pages;

use App\Filament\Resources\ImprovementActionResource;
use App\Services\AuthService;
use App\Services\ImprovementActionService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewImprovementAction extends ViewRecord
{
    protected static string $resource = ImprovementActionResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('view')
                ->label('View action completion')
                ->button()
                ->color('primary')
                ->authorize(fn ($record) => app(AuthService::class)->canViewActionCompletion($record->improvement_action_status_id))
                ->url(fn ($record) => ImprovementActionResource::getUrl('improvement_action_completions.view', [
                    'improvementactionId' => $record->id,
                    'record' => $record->improvementActionCompletion->id,
                ])),

            Action::make('finish')
                ->label('End action')
                ->button()
                ->color('success')
                ->authorize(fn ($record) => app(AuthService::class)->canFinishAction(
                    $record,
                    'improvement'
                ))
                ->url(fn ($record) => ImprovementActionResource::getUrl('improvement_action_completions.create', [
                    'improvementactionId' => $record->id,
                ])),

            Action::make('cancel')
                ->label('Cancel')
                ->button()
                ->color('danger')
                ->authorize(fn ($record) => app(AuthService::class)->canCancelAction(
                    $record,
                    'improvement'
                ))
                ->form([
                    Textarea::make('reason_for_cancellation')
                        ->label('Reason for cancellation')
                        ->required()
                        ->rule('string')
                        ->placeholder('Write the reason for cancellation'),
                ])
                ->action(function ($record, array $data) {
                    app(ImprovementActionService::class)->canceledStateAssignment($record, $data);
                    redirect(ImprovementActionResource::getUrl('index'));
                }),
            // ->action(fn($record) => app(ImprovementActionService::class)->canceledStateAssignment($record)),

            Action::make('back')
                ->label('Return')
                ->url(fn (): string => ImprovementActionResource::getUrl('index'))
                ->button()
                ->color('gray'),

        ];
    }
}
