<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Models\AgeRelaxation;
use App\Models\AppliedPosts;
use App\Models\Candidate\CandidateProfile;
use App\Models\cities;
use App\Models\District;
use App\Models\Province;
use App\Models\QuotaSetting;
use App\Models\User;
use App\Services\AgeRelaxationService;
use App\Services\GetDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CandidateDashboardController extends Controller
{
   public function index()
    {
        $userId = auth()->id();
        $userData = Cache::remember("candidate_full_data_{$userId}", 100, function () use ($userId) {
            return User::with([
                'candidateProfile.city.district.province',
                'AppliedPosts' => function ($query) {
                    $query->with('post');
                },
            ])->find($userId);
        });


        if (!$userData?->candidateProfile) {
            return Inertia::render('Dashboard/Candidate/Index', [
                'posts' => [],
                'error' => 'Candidate profile not found. Please complete your profile first.',
                'myJobsApplication' => [],
                'testCities' => [],
            ]);
        }

        $myJobsApplication = GetDataService::getUserWithProfile($userId);

        $relaxationDays = AgeRelaxationService::calculateRelaxationDays(
            $userData->candidateProfile,
            $userData->ageRelaxation
        );

        $posts = Cache::remember("eligible_posts_{$userId}", 300, function () use ($userData, $relaxationDays) {
            return $this->getEligiblePosts($userData->candidateProfile, $relaxationDays);
        });


        $testCities = Cache::remember('test_cities_all', 1800, fn () => GetDataService::getTestCities());
        return Inertia::render('Dashboard/Candidate/Index', [
            'posts' => $posts->toArray(),
            'testCities' => $testCities,
            'myJobsApplication' => $myJobsApplication ? $myJobsApplication->toArray() : [],
        ]);
    }

    private function getEligiblePosts(CandidateProfile $profile, int $relaxationDays): Collection
    {

        $birthDate = Carbon::parse($profile->date_of_birth);
        $currentAge = $birthDate->diffInDays(Carbon::now()) / 365.25;
        $effectiveAge = $currentAge - ($relaxationDays / 365.25);
        $currentAge = $birthDate->diffInDays(Carbon::now()) / 365.25;

             return Post::select(['id', 'name', 'min_age', 'max_age', 'post_gender','bps', 'induction_phase_id'])
            ->with([
                'quotaSetting:id,post_id,province_id,open_merit,merit,women,special_persons',
                'inductionPhase:id,title,end_date'
            ])
            ->where(function ($query) use ($profile) {
                $query->where('post_gender', $profile->gender)
                    ->orWhere('post_gender', 'both');
            })
            ->where('min_age', '<=', $currentAge)
            ->where('max_age', '>=', $effectiveAge)
            ->whereHas('quotaSetting', function ($query) use ($profile) {
                $query->where(function ($q) use ($profile) {
                    // Province-specific quotas
                    $q->where('province_id', $profile->city->district->province_id)
                        ->where(function ($subQ) use ($profile) {
                            $subQ->where('open_merit', '>', 0)
                                ->orWhere('merit', '>', 0);
                            if ($profile->gender === 'Female') {
                                $subQ->orWhere('women', '>', 0);
                            }
                            if ($profile->disability === '1') {
                                $subQ->orWhere('special_persons', '>', 0);
                            }
                        });
                })->orWhere(function ($q) {
                    $q->whereNull('province_id')->where('open_merit', '>', 0);
                });
            })
            ->get()
            ->map(function ($post) {
                // Manually serialize to array to ensure deadline is included
                $postData = $post->toArray();
                $postData['deadline'] = $post->inductionPhase ? Carbon::parse($post->inductionPhase->end_date)->format('d M Y') : 'N/A';
                return $postData;
            });
    }

public function apply(Request $request)
{
    $validated = $request->validate([
        'postId' => 'required|exists:posts,id',
        'testCity1' => 'required',
        'testCity2' => 'required',
    ]);
        $userId = auth()->id();
    AppliedPosts::updateOrCreate(
        [
            'user_id' => $userId,
            'post_id' => $validated['postId']
        ],
        [
            'preferred_city_id' => $validated['testCity1'],
            'alternative_city_id' => $validated['testCity2'],
            'applied_at' => now(),
            'updated_at' => now(),
        ]
    );
    return redirect()->back()->with('success', 'Application submitted successfully!');
}

}
