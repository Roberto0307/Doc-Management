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

    public function markAsFinished(ImprovementAction $improvementActionModel): bool
    {
        $statusFinishedId = ImprovementActionStatus::where('title', 'finished')->value('id');

        if (! $statusFinishedId) {
            return false;
        }

        return $improvementActionModel->update([
            'improvement_action_status_id' => $statusFinishedId,
        ]);
    }
}
