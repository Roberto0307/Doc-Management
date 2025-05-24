<?php

namespace App\Services;

use App\Models\ImprovementActionTask;
use App\Models\ImprovementActionTaskComment;
use App\Models\ImprovementActionTaskFile;
use App\Models\ImprovementActionTaskStatus;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class TaskService
{
    public function createComment(ImprovementActionTask $taskModel, array $data): void
    {
        ImprovementActionTaskComment::create([
            'improvement_action_task_id' => $taskModel->id,
            'comment' => Str::limit(strip_tags($data['comment']), 255),

        ]);

        $this->updateTaskStatus($taskModel);

        $this->taskNotification('Comment saved successfully');
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

        $this->taskNotification('Support files uploaded successfully');
    }

    public function closeTask(ImprovementActionTask $taskModel): bool
    {
        $statusTitle = now()->lessThanOrEqualTo($taskModel->deadline) ? 'completed' : 'extemporaneous';
        $statusId = ImprovementActionTaskStatus::byTitle($statusTitle)?->id;

        return $statusId ? $taskModel->update(['improvement_action_task_status_id' => $statusId]) : false;
    }

    private function updateTaskStatus(ImprovementActionTask $taskModel): bool
    {
        $currentStatusId = $taskModel->improvement_action_task_status_id;
        $pendingStatusId = ImprovementActionTaskStatus::byTitle('pending')?->id;
        $inExecutionStatusId = ImprovementActionTaskStatus::byTitle('in_execution')?->id;

        if ($currentStatusId === $pendingStatusId && $inExecutionStatusId !== null) {
            return $taskModel->update(['improvement_action_task_status_id' => $inExecutionStatusId]);
        }

        return false;
    }

    private function taskNotification(string $message): void
    {
        Notification::make()
            ->title($message)
            ->success()
            ->send();
    }
}
