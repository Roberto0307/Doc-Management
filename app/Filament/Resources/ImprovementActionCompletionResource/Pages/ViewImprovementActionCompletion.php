<?php

namespace App\Filament\Resources\ImprovementActionCompletionResource\Pages;

use App\Filament\Resources\ImprovementActionCompletionResource;
use App\Filament\Resources\ImprovementActionResource;
use App\Models\ImprovementAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewImprovementActionCompletion extends ViewRecord
{
    protected static string $resource = ImprovementActionCompletionResource::class;

    public function getSubheading(): ?string
    {
        $improvementActionModel = ImprovementAction::findOrFail(request()->route('improvementactionId'));

        return $improvementActionModel->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            ImprovementActionResource::getUrl('view', ['record' => request()->route('improvementactionId')]) => 'Improvement Action',
            false => 'Completion',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [

            Action::make('back')
                ->label('Return')
                ->url(fn (): string => ImprovementActionResource::getUrl('view', ['record' => request()->route('improvementactionId')]))
                ->button()
                ->color('gray'),

        ];
    }
}
