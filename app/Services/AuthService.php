<?php

namespace App\Services;

use App\Models\File;
use App\Models\ImprovementActionStatus;
use App\Models\ImprovementActionTask;
use App\Models\ImprovementActionTaskStatus;
use App\Models\Record;
use App\Models\Status;
use App\Models\SubProcess;
use App\Models\User;

/**
 * Servicio de Autentificación
 */
class AuthService
{
    public function canApproveAndReject(User $user, ?int $subProcessId): bool
    {
        return $user->hasRole('super_admin') || $user->isLeaderOfSubProcess($subProcessId);
    }

    public function canPending(User $user, File $file): bool
    {
        return $user->hasRole('super_admin') || $file->user_id === $user->id;
    }

    public function getLeaderToSubProcess(?int $subProcessId): ?User
    {
        return SubProcess::with('leader')->find($subProcessId)?->user;
    }

    public function canAccessSubProcessId(int|string|null $subProcessId): bool
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return true;
        }
        if (is_null($subProcessId)) {
            return false;
        }

        return $user->validSubProcess($subProcessId);
    }

    public function validatedData(array $data, array $preserve = []): array
    {
        $user = auth()->user();
        $record = Record::with('subProcess')->findOrFail($data['record_id']);

        $hasApprovalAccess = $this->canApproveAndReject($user, $record->sub_process_id ?? null);
        $statusApproved = Status::byTitle('approved');
        $statusDraft = Status::byTitle('draft');

        $lastVersion = File::where('record_id', $data['record_id'])->orderByDesc('version')->first();

        $newVersion = $lastVersion
            ? ($hasApprovalAccess ? ((int) $lastVersion->version + 1).'.00' : bcadd($lastVersion->version, '0.01', 2))
            : ($hasApprovalAccess ? '1.00' : '0.01');

        return array_merge($data, [
            'status_id' => in_array('status_id', $preserve) ? ($data['status_id'] ?? null) : ($hasApprovalAccess ? $statusApproved->id : $statusDraft->id),
            'version' => in_array('version', $preserve) ? ($data['version'] ?? null) : $newVersion,
            'user_id' => in_array('user_id', $preserve) ? ($data['user_id'] ?? null) : $user->id,
            'decided_by_user_id' => in_array('decided_by_user_id', $preserve) ? ($data['decided_by_user_id'] ?? null) : ($hasApprovalAccess ? auth()->id() : null),
            'decision_at' => in_array('decision_at', $preserve) ? ($data['decision_at'] ?? null) : ($hasApprovalAccess ? now() : null),
        ]);
    }

    /* Acciones */
    public function canFinishAction(int $responsibleId, int $statusId, string $module): bool
    {
        if ($module === 'improvement') {
            $expectedStatusId = ImprovementActionStatus::where('title', 'in execution')->value('id');

            if ($statusId !== $expectedStatusId) {
                return false;
            }

            return auth()->id() === $responsibleId;
        }

        if ($module === 'corrective/preventive') {
            // lógica futura para otros módulos
            return false;
        }

        return false;
    }

    public function canViewActionCompletion(int $statusId)
    {
        $expectedStatusId = ImprovementActionStatus::where('title', 'finished')->value('id');

        return $statusId === $expectedStatusId;
    }

    public function canCreateTask(int $responsibleId, int $statusId): bool
    {
        $statusProposal = ImprovementActionStatus::where('title', 'proposal')->value('id');
        $statusInExecution = ImprovementActionStatus::where('title', 'in execution')->value('id');

        if (auth()->id() === $responsibleId && ($statusId === $statusProposal || $statusId === $statusInExecution)) {
            return true;
        }

        return false;
    }

    public function canCloseTask(ImprovementActionTask $taskModel)
    {
        $responsibleTaskId = $taskModel->responsible_id;
        $statusInExecutionId = ImprovementActionStatus::where('title', 'in execution')->value('id');
        if (auth()->id() === $responsibleTaskId && $taskModel->improvement_action_task_status_id === $statusInExecutionId) {
            return true;
        }

        return false;
    }

    public function canTaskUploadFollowUp(ImprovementActionTask $taskModel)
    {
        $responsibleTaskId = $taskModel->responsible_id;
        $statusCompletedId = ImprovementActionTaskStatus::where('title', 'completed')->value('id');
        $statusExtemporaneousId = ImprovementActionTaskStatus::where('title', 'extemporaneous')->value('id');
        if (auth()->id() === $responsibleTaskId && ($taskModel->improvement_action_task_status_id === $statusCompletedId || $taskModel->improvement_action_task_status_id === $statusExtemporaneousId)) {
            return false;
        }

        return true;
    }
}
