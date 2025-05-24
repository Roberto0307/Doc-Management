<?php

namespace App\Traits;

trait HasVersioning
{
    protected function calculateNewVersion($lastVersion, bool $hasApprovalAccess): string
    {
        return $lastVersion
            ? ($hasApprovalAccess
                ? ((int) $lastVersion->version + 1).'.00'
                : bcadd($lastVersion->version, '0.01', 2))
            : ($hasApprovalAccess ? '1.00' : '0.01');
    }
}
