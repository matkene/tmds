<?php

namespace App\Repositories;

use App\Models\AuditLog;

class ReportRepository
{
    public function allActivities()
    {
        $allActivities = AuditLog::orderBy('created_at', 'DESC')->get();

        return [
            'logName' => $allActivities->log_name,
            'description' => $allActivities->description,
            'created_at' => $allActivities->created_at,
            'updated_at' => $allActivities->updated_at,
        ];
    }
}
