<?php
namespace App\Repositories;

use App\Models\AppliedPosts;
use Illuminate\Support\Collection;

class AppliedPostRepository
{
    public function getFilteredApplications(array $filters): Collection
    {
        $query = AppliedPosts::withRelations()
            ->with(['allotment.center'])
            ->filterByPost($filters['post_id'] ?? null)
            ->filterByCity($filters['city_id'] ?? null)
            ->approved();

        // Filter by allocation status
        if (isset($filters['status'])) {
            if ($filters['status'] === 'allocated') {
                $query->has('allotment');
            } elseif ($filters['status'] === 'not_allocated') {
                $query->doesntHave('allotment');
            }
        }

        // Filter by center
        if (isset($filters['center_id'])) {
            $query->whereHas('allotment', function($q) use ($filters) {
                $q->where('center_id', $filters['center_id']);
            });
        }

        return $query->get()->map($this->formatApplication());
    }

    private function formatApplication(): callable
    {
        return fn($app) => [
            'id' => $app->id,
            'candidate_name' => $app->user?->candidateProfile?->name ?? 'N/A',
            'post_name' => $app->post?->name ?? 'N/A',  
            'roll_number' => $app->allotment?->roll_number ?? 'Not Allotted',
            'center_name' => $app->allotment?->center?->name ?? 'Not Allotted',
            'status' => $app->allotment?->status ?? 'pending',
            'preferred_city' => $app->preferredCity?->name ?? null,
            'alternative_city' => $app->alternativeCity?->name ?? null,
        ];
    }
}