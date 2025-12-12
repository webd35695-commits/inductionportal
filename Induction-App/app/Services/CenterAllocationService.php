<?php 
namespace App\Services;

use App\Models\{AppliedPosts, center, CentersAllotment, CenterPost};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CenterAllocationService
{
    private const BATCH_SIZE = 1000;
    private const MAX_RETRIES = 3;

    public function processAllocations(int $batchSize = null): array
    {
        Log::info('=== processAllocations START ===', ['batch_size_input' => $batchSize]);
        
        $batchSize = $batchSize ?? self::BATCH_SIZE;
        $stats = ['total' => 0, 'allocated' => 0, 'skipped' => 0, 'failed' => 0];

        Log::info('Starting DB transaction', ['batch_size' => $batchSize]);

        try {
            DB::transaction(function () use ($batchSize, &$stats) {
                // Get distinct users who have approved applications without allotment
                $userIds = AppliedPosts::approved()
                    ->withoutAllotment()
                    ->distinct()
                    ->pluck('user_id'); // Get all user IDs first (or chunk them if too many)
                
                $count = $userIds->count();
                Log::info('Found applicants to process', ['count' => $count]);
                
                if ($count === 0) {
                    Log::warning('No applicants found for allocation');
                    return;
                }
                
                // Process users in chunks
                $userIds->chunk($batchSize)->each(function ($chunk) use (&$stats) {
                    foreach ($chunk as $userId) {
                        $this->processUserAllocations($userId, $stats);
                    }
                });
            });
            
            Log::info('Transaction completed successfully', $stats);
        } catch (\Exception $e) {
            Log::error('Transaction failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
        
        return $stats;
    }

    private function processUserAllocations(int $userId, array &$stats): void
    {
        Log::info("Processing allocations for user {$userId}");

        try {
            // Fetch all pending applications for this user
            $applications = AppliedPosts::approved()
                ->withoutAllotment()
                ->where('user_id', $userId)
                ->with(['post', 'preferredCity', 'alternativeCity'])
                ->get();

            if ($applications->isEmpty()) {
                return;
            }

            $stats['total'] += $applications->count();

            // Group by preferred city (usually all same, but handle mixed)
            $applicationsByCity = $applications->groupBy('preferred_city_id');

            foreach ($applicationsByCity as $cityId => $cityApps) {
                $this->allocateGroup($cityApps, $cityId, $stats);
            }

        } catch (\Exception $e) {
            $stats['failed'] += $applications->count(); // Mark all as failed for this user
            Log::error("Allocation failed for user {$userId}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function allocateGroup($applications, $cityId, array &$stats): void
    {
        $remainingApps = $applications;

        while ($remainingApps->isNotEmpty()) {
            // Find the best center for the remaining applications
            $bestCenter = $this->findBestFitCenter($remainingApps, $cityId);

            if (!$bestCenter) {
                // Try alternative city for remaining apps
                $firstApp = $remainingApps->first();
                if ($firstApp->alternative_city_id && $firstApp->alternative_city_id != $cityId) {
                     Log::info("Trying alternative city for user {$firstApp->user_id}");
                     $this->allocateGroup($remainingApps, $firstApp->alternative_city_id, $stats);
                     return;
                }

                Log::warning("No center found for remaining apps of user {$firstApp->user_id}");
                $stats['skipped'] += $remainingApps->count();
                return;
            }

            // Allocate supported posts to this center
            foreach ($remainingApps as $key => $app) {
                if ($this->centerSupportsPost($bestCenter, $app->post_id)) {
                    $this->createAllotment($app, $bestCenter);
                    $stats['allocated']++;
                    $remainingApps->forget($key);
                }
            }
        }
    }

    /**
     * Find the center that can host the MOST of the given applications
     */
    private function findBestFitCenter($applications, $cityId): ?center
    {
        if (!$cityId) return null;

        // Get all active centers in the city
        $centers = center::where('city_id', $cityId)
            ->where('is_active', true)
            ->withCount('allotments')
            ->get();

        $bestCenter = null;
        $maxSupported = -1;
        $minLoad = PHP_INT_MAX;

        foreach ($centers as $center) {
            $supportedCount = 0;

            foreach ($applications as $app) {
                if ($this->centerSupportsPost($center, $app->post_id) && $this->hasCapacity($center, $app->post_id)) {
                    $supportedCount++;
                }
            }

            if ($supportedCount > 0) {
                // Prioritize: 1. Most posts supported, 2. Least current load
                if ($supportedCount > $maxSupported || ($supportedCount == $maxSupported && $center->allotments_count < $minLoad)) {
                    $maxSupported = $supportedCount;
                    $minLoad = $center->allotments_count;
                    $bestCenter = $center;
                }
            }
        }

        return $bestCenter;
    }

    private function centerSupportsPost(center $center, int $postId): bool
    {
        // Check if center has this post assigned
        return CenterPost::where('center_id', $center->id)
            ->where('post_id', $postId)
            ->exists();
    }

    private function hasCapacity(center $center, int $postId): bool
    {
        $centerPost = CenterPost::where('center_id', $center->id)
            ->where('post_id', $postId)
            ->first();
        
        if (!$centerPost) return false;

        $maxApplicants = $centerPost->max_applicants ?? $center->capacity;
        
        // We need to check total allotments for this center vs its global capacity
        // AND potentially post-specific limits if you have them.
        // Assuming simple capacity model for now:
        return $center->allotments_count < $maxApplicants;
    }

    /**
     * Create the allotment record with a roll number
     */
    private function createAllotment(AppliedPosts $application, center $center): void
    {
        Log::info("Creating allotment", [
            'application_id' => $application->id,
            'center_id' => $center->id,
            'user_id' => $application->user_id
        ]);
        
        $rollNumber = $this->generateRollNumber($application, $center);
        
        CentersAllotment::create([
            'user_id' => $application->user_id,
            'applied_post_id' => $application->id,
            'center_id' => $center->id,
            'roll_number' => $rollNumber,
            'status' => 'allotted',
        ]);
    }

    /**
     * Generate a unique roll number
     * Format: POST-CENTER-SEQUENCE
     * Example: LEC-HYD01-0001
     */
    private function generateRollNumber(AppliedPosts $application, center $center): string
    {
        // Get post abbreviation
        $postAbbr = $application->post->post_abbreviation ?? 'POST';
        
        // Generate center code (first 3 letters of city + center ID)
        $cityCode = strtoupper(substr($center->cities->name ?? 'CTR', 0, 3));
        $centerCode = str_pad($center->id, 2, '0', STR_PAD_LEFT);
        
        // Get next sequence number for this center and post
        $sequence = CentersAllotment::where('center_id', $center->id)
            ->count() + 1;
        
        $sequenceNum = str_pad($sequence, 4, '0', STR_PAD_LEFT);
        
        return "{$postAbbr}-{$cityCode}{$centerCode}-{$sequenceNum}";
    }
}
