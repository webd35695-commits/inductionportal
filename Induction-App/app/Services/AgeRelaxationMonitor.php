<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AgeRelaxationMonitor
{
    public static function logCalculation(int $userId, int $relaxationDays, float $executionTime)
    {
        Log::info('Age relaxation calculated', [
            'user_id' => $userId,
            'relaxation_days' => $relaxationDays,
            'execution_time_ms' => $executionTime,
            'memory_usage' => memory_get_usage(true),
        ]);
    }
}