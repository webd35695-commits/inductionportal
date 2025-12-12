<?php

namespace App\Jobs;

use App\Http\Controllers\AgeRelaxationService as ControllersAgeRelaxationService;
use App\Models\User;
use App\Services\AgeRelaxationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CalculateUserEligibilityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private int $userId,
        private array $newPosts = []
    ) {}

    public function handle()
    {
        $user = User::with(['candidateProfile', 'ageRelaxation'])->find($this->userId);
        
        if (!$user?->candidateProfile) {
            return;
        }

        $relaxationDays = ControllersAgeRelaxationService::calculateRelaxationDays(
            $user->candidateProfile, 
            $user->ageRelaxation
        );

        // Cache user's eligibility data
        Cache::put("user_eligibility_{$this->userId}", [
            'relaxation_days' => $relaxationDays,
            'calculated_at' => now(),
        ], now()->addHours(6));

        // Optionally notify user of new eligible posts
        if (!empty($this->newPosts)) {
            // Send notification logic here
        }
    }
}