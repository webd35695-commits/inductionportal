<?php

namespace App\Services;

use App\Models\AppliedPosts;
use App\Models\Candidate\CandidateProfile as CandidateCandidateProfile;
use App\Models\cities;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class GetDataService
{
    public static function getTestCities(): Collection
{
    return cities::where('test_center',1)->get(['id','name']);
}
    public static function getUserWithProfile($user_id)
{
$detail = User::with('candidateProfile', 'AppliedPosts.post')->find($user_id);
    return $detail;
}

public static function allApplications()
{
    try {
        $allApplications = AppliedPosts::with('user.candidateProfile')->get();
        return $allApplications;
    } catch (\Exception $e) {
        \Log::error('Error fetching applications: ' . $e->getMessage());
        return collect([]); // Return an empty collection on error
    }
}
}
