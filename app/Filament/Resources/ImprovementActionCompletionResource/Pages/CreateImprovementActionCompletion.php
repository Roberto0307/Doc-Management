<?php

namespace App\Filament\Resources\ImprovementActionCompletionResource\Pages;

use App\Filament\Resources\ImprovementActionCompletionResource;
use App\Filament\Resources\ImprovementActionResource;
use App\Models\ImprovementAction;
use App\Models\ImprovementActionCompletionFile;
use App\Services\ImprovementActionService;
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

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {

        $completion = static::getModel()::create([
            'real_impact' => $data['real_impact'],
            'result' => $data['result'],
            'improvement_action_id' => $this->improvementActionId,
        ]);

        // Guardamos los archivos (si los hay)
        if (! empty($data['attachments']) && is_array($data['attachments'])) {
            foreach ($data['attachments'] as $path) {
                ImprovementActionCompletionFile::create([
                    'improvement_action_completion_id' => $completion->id,
                    'file_path' => $path,
                    'file_name' => $data['title'][$path] ?? 'Sin nombre',
                ]);
            }
        }
        app(ImprovementActionService::class)->statusChangesInImprovementActions($this->improvementActionModel, 'finished');
        app(ImprovementActionService::class)->clothingDateInImprovementActions($this->improvementActionModel);

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
