<?php

namespace App\Services;

use App\Models\ImprovementAction;
use App\Models\ImprovementActionStatus;

class ImprovementActionService
{
    public function initialStateAssignment()
    {
        //$improvementActionStatus = ImprovementActionStatus::where('title', 'proposal')->value('id');
        $improvementActionStatus = ImprovementActionStatus::byTitle('proposal')?->id;
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

    // public function statusChangesInImprovementActions(ImprovementAction $improvementActionModel, string $status): bool
    // {
    //     $statusProposalId = ImprovementActionStatus::where('title', 'proposal')->value('id');

    //     if ($status === 'finished') {
    //         $statusChangeId = ImprovementActionStatus::where('title', 'finished')->value('id');
    //     } elseif ($status === 'in execution') {
    //         if ($improvementActionModel->improvement_action_status_id === $statusProposalId) {
    //             $statusChangeId = ImprovementActionStatus::where('title', 'in execution')->value('id');
    //         }
    //     }

    //     if (! $statusChangeId) {
    //         return false;
    //     }

    //     return $improvementActionModel->update([
    //         'improvement_action_status_id' => $statusChangeId,
    //     ]);
    // }

    public function statusChangesInImprovementActions(ImprovementAction $improvementActionModel, string $status): bool
    {
        $statusChangeId = ImprovementActionStatus::byTitle($status)?->id;
        $proposalId = ImprovementActionStatus::byTitle('proposal')?->id;

        if (!$statusChangeId) {
            return false;
        }

        // Solo permitir cambio a "in execution" si el estado actual es "proposal"
        if ($status === 'in execution' && $improvementActionModel->improvement_action_status_id !== $proposalId) {
            return false;
        }

        return $improvementActionModel->update(['improvement_action_status_id' => $statusChangeId]);
    }


}
