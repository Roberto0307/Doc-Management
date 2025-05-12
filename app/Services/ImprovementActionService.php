<?php

namespace App\Services;

use App\Models\ImprovementActionStatus;

class ImprovementActionService
{
    public function initialStateAssignment()
    {
        $improvementActionStatus = ImprovementActionStatus::where('title', 'proposal')->value('id');

        return $improvementActionStatus;
    }
}
