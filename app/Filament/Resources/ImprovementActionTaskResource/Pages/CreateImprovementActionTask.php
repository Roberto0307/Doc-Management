<?php

namespace App\Filament\Resources\ImprovementActionTaskResource\Pages;

use App\Filament\Resources\ImprovementActionResource;
use App\Filament\Resources\ImprovementActionTaskResource;
use App\Models\ImprovementAction;
use App\Services\ImprovementActionService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateImprovementActionTask extends CreateRecord
{
    protected static string $resource = ImprovementActionTaskResource::class;

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
        $data['improvement_action_task_status_id'] = 1;

        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {

        $task = static::getModel()::create([
            'improvement_action_id' => $this->improvementActionId,
            'title' => $data['title'],
            'detail' => $data['detail'],
            'responsible_id' => $data['responsible_id'],
            'start_date' => $data['start_date'],
            'deadline' => $data['deadline'],
            'improvement_action_task_status_id' => 1,
        ]);

        $updateStatusImprovementAction = app(ImprovementActionService::class)->statusChangesInImprovementActions($this->improvementActionModel, 'in execution');

        if (! $updateStatusImprovementAction) {
            Notification::make()
                ->title('The status of the improvement action could not be updated')
                ->danger()
                ->send();
        }

        return $task;
    }

    /* ******************* */

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
            false => 'Task',
        ];
    }
}
