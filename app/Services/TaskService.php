<?php

namespace App\Services;

use App\Models\ImprovementActionTask;
use App\Models\ImprovementActionTaskComment;
use App\Models\ImprovementActionTaskFile;
use App\Models\ImprovementActionTaskStatus;
use Filament\Notifications\Notification;

class TaskService
{
    public function createComment(ImprovementActionTask $taskModel, array $data)
    {
        ImprovementActionTaskComment::create([
            'improvement_action_task_id' => $taskModel->id,
            'comment' => $data['comment'],
        ]);

        $this->updateTaskStatus($taskModel);

        Notification::make()
            ->title('Comment saved successfully')
            ->success()
            ->send();
    }

    public function createFiles(ImprovementActionTask $taskModel, array $data): void
    {
        foreach ($data['attachments'] ?? [] as $path) {
            $fileName = $data['title'][$path] ?? basename($path);

            ImprovementActionTaskFile::create([
                'improvement_action_task_id' => $taskModel->id,
                'file_path' => $path,
                'file_name' => $fileName,
            ]);
        }

        $this->updateTaskStatus($taskModel);
        Notification::make()
            ->title('Support files uploaded successfully')
            ->success()
            ->send();
    }

    public function closeTask(ImprovementActionTask $taskModel)
    {

        $statusChangeId = null;
        if ($taskModel->deadline >= now()) {
            $statusChangeId = ImprovementActionTaskStatus::where('title', 'completed')->value('id');
        } elseif ($taskModel->deadline < now()) {
            $statusChangeId = ImprovementActionTaskStatus::where('title', 'extemporaneous')->value('id');
        }

        if ($statusChangeId === null) {
            return false;
        }

        return $taskModel->update([
            'improvement_action_task_status_id' => $statusChangeId,
        ]);
    }

    public function updateTaskStatus(ImprovementActionTask $taskModel)
    {
        $statusPending = ImprovementActionTaskStatus::where('title', 'pending')->value('id');
        $statusInExecution = ImprovementActionTaskStatus::where('title', 'in execution')->value('id');
        $statusChangeId = null;
        if ($taskModel->improvement_action_task_status_id === $statusPending) {
            $statusChangeId = $statusInExecution;
        }

        if ($statusChangeId === null) {
            return false;
        }

        return $taskModel->update([
            'improvement_action_task_status_id' => $statusChangeId,
        ]);
    }
}
