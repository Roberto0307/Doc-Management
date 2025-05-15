<?php

namespace App\Filament\Resources\ImprovementActionCompletionResource\Pages;

use App\Filament\Resources\ImprovementActionCompletionResource;
use App\Filament\Resources\ImprovementActionResource;
use App\Models\ImprovementAction;
use App\Models\ImprovementActionStatus;
use App\Services\ImprovementActionService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateImprovementActionCompletion extends CreateRecord
{
    protected static string $resource = ImprovementActionCompletionResource::class;

    public ?ImprovementAction $improvementActionModel = null;

    public ?int $improvementActionId = null;

    public function mount(): void
    {
        parent::mount();

        $this->improvementActionId = request()->route('improvementactionId');

        // Asegúrate de obtener el modelo real desde el ID
        $improvementAction = ImprovementAction::findOrFail($this->improvementActionId);

        // Guarda el modelo completo si lo vas a usar para el título o breadcrumbs
        $this->improvementActionModel = $improvementAction;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['improvement_action_id'] = $this->improvementActionId;
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $data['improvement_action_id'] = $this->improvementActionId;

        $completion = static::getModel()::create($data);

        $updateStatusImprovementAction = app(ImprovementActionService::class)->markAsFinished($this->improvementActionModel);

        if (! $updateStatusImprovementAction) {
            Notification::make()
                ->title('The status of the improvement action could not be updated')
                ->danger()
                ->send();
        }

        return $completion;
    }



    /* *********** */

    protected function getRedirectUrl(): string
    {
        return ImprovementActionResource::getUrl('view', ['record' => $this->improvementActionId]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }

    public function getSubheading(): ?string
    {
        return $this->improvementActionModel->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            ImprovementActionResource::getUrl('view', ['record' => $this->improvementActionId]) => 'Improvement Action',
            false => 'Completion',
        ];
    }
}
