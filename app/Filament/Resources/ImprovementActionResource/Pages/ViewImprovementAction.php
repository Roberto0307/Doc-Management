<?php

namespace App\Filament\Resources\ImprovementActionResource\Pages;

use App\Filament\Resources\ImprovementActionResource;
use App\Services\AuthService;
use Filament\Actions\Action;
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
                ->url(fn (): string => ImprovementActionResource::getUrl('index')),

            Action::make('finish')
                ->label('End action')
                ->button()
                ->color('success')
                ->authorize(fn ($record) => app(AuthService::class)->canFinishAction(
                    $record->responsible_id,
                    $record->improvement_action_status_id,
                    'improvement'
                ))
                ->action(function ($record, array $data) {
                    redirect(ImprovementActionResource::getUrl('improvement_action_completions.create', [
                        'improvementactionId' => $record->id,
                    ]));
                }),
                /* ->url(fn ($record): string => ImprovementActionResource::getUrl('improvement_action_completions.create', $record->id)), */
            Action::make('back')
                ->label('Return')
                ->url(fn (): string => ImprovementActionResource::getUrl('index'))
                ->button()
                ->color('gray'),

        ];
    }
}
