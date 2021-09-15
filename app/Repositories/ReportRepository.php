<?php

namespace App\Repositories;

use App\Models\AuditLog;

class ReportRepository
{
    public function allActivities()
    {
        return AuditLog::orderBy('created_at', 'DESC')->get();
    }
}
