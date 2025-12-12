<?php

namespace App\Repositories;

use App\Jobs\CalculateUserEligibilityJob;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PostRepository
{
    public function getEligiblePostsForUser(int $userId): Collection
    {
        $eligibilityData = Cache::get("user_eligibility_{$userId}");
        
        if (!$eligibilityData) {
            // Dispatch job to calculate in background
            CalculateUserEligibilityJob::dispatch($userId);
            return new Collection();
        }

        return $this->queryEligiblePosts($eligibilityData);
    }

    private function queryEligiblePosts(array $eligibilityData): Collection
    {
        // Optimized query using pre-calculated data
        return Post::eligibleWithRelaxation($eligibilityData)->get();
    }
}