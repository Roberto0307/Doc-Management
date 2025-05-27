<?php

namespace App\Services;

use App\Models\ImprovementAction;
use App\Models\ImprovementActionStatus;

class ImprovementActionService
{
    public function initialStateAssignment()
    {
        $improvementActionStatus = ImprovementActionStatus::byTitle('proposal')?->id;

        return $improvementActionStatus;
    }

    public function statusChangesInImprovementActions(ImprovementAction $model, string $status): bool
    {
        $statusChangeId = ImprovementActionStatus::byTitle($status)?->id;
        $proposalId = ImprovementActionStatus::byTitle('proposal')?->id;

        if (! $statusChangeId) {
            return false;
        }

        // Solo permitir cambio a "in execution" si el estado actual es "proposal"
        if ($status === 'in_execution' && $model->improvement_action_status_id !== $proposalId) {
            return false;
        }

        return $model->update(['improvement_action_status_id' => $statusChangeId]);
    }

    public function clothingDateInImprovementActions(ImprovementAction $model)
    {
        return $model->update(['actual_closing_date' => now()->format('Y-m-d')]);
    }
}
