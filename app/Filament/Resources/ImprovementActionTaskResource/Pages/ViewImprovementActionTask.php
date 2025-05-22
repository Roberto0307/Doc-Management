<?php

namespace App\Filament\Resources\ImprovementActionTaskResource\Pages;

use App\Filament\Resources\ImprovementActionResource;
use App\Filament\Resources\ImprovementActionTaskResource;
use App\Services\AuthService;
use App\Services\TaskService;
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
                ->authorize(fn ($record) => app(AuthService::class)->canCloseTask($record))
                ->action(function ($record) {
                    app(TaskService::class)->closeTask($record);
                    redirect(ImprovementActionResource::getUrl('view', [
                        'record' => $record->improvement_action_id,
                    ]));
                }),

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
