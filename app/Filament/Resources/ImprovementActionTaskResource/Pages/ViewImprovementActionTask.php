<?php

namespace App\Filament\Resources\ImprovementActionTaskResource\Pages;

use App\Filament\Resources\ImprovementActionResource;
use App\Filament\Resources\ImprovementActionTaskResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewImprovementActionTask extends ViewRecord
{
    protected static string $resource = ImprovementActionTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('finish_task')
                ->label('End task')
                ->button()
                ->color('success')
                /* ->authorize(fn($record) => app(AuthService::class)->canFinishAction(
                    $record->responsible_id,
                    $record->improvement_action_status_id,
                    'improvement'
                )) */
                ->url(fn ($record) => ImprovementActionResource::getUrl('improvement_action_completions.create', [
                    'improvementactionId' => $record->id,
                ])),

            Action::make('back')
                ->label('Return')
                ->url(fn ($record): string => ImprovementActionResource::getUrl('view', [
                    'record' => $record->improvementAction->id,
                ]))
                ->button()
                ->color('gray'),

        ];
    }
}
