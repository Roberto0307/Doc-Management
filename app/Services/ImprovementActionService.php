<?php

namespace App\Services;

use App\Models\ImprovementAction;
use App\Models\ImprovementActionStatus;

class ImprovementActionService
{
    public function initialStateAssignment()
    {
        $improvementActionStatus = ImprovementActionStatus::where('title', 'proposal')->value('id');

        return $improvementActionStatus;
    }

    /* public function markAsFinished(ImprovementAction $improvementActionModel): bool
    {
        $statusFinishedId = ImprovementActionStatus::where('title', 'finished')->value('id');

        if (! $statusFinishedId) {
            return false;
        }

        return $improvementActionModel->update([
            'improvement_action_status_id' => $statusFinishedId,
        ]);
    } */ // Se reemplaza por el de abajo que es mas general y reutilizable

    public function statusChangesInImprovementActions(ImprovementAction $improvementActionModel, string $status): bool
    {
        $statusProposalId = ImprovementActionStatus::where('title', 'proposal')->value('id');
        $statusChangeId = null;
        if ($status === 'finished') {
            $statusChangeId = ImprovementActionStatus::where('title', 'finished')->value('id');
        } elseif ($status === 'in execution') {
            if ($improvementActionModel->improvement_action_status_id === $statusProposalId) {
                $statusChangeId = ImprovementActionStatus::where('title', 'in execution')->value('id');
            }
        }

        if ($statusChangeId === null) {
            return false;
        }

        return $improvementActionModel->update([
            'improvement_action_status_id' => $statusChangeId,
        ]);
    }
}
