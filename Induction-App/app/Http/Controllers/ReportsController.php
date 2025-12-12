<?php

namespace App\Http\Controllers;

use App\Models\AppliedPosts;
use App\Models\center;
use App\Models\CentersAllotment;
use App\Models\cities as City;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportsController extends Controller
{
    public function index()
    {
        return Inertia::render('Reports/Index');
    }

    public function getData(Request $request)
    {
        $type = $request->input('type');
        $perPage = $request->input('per_page', 15); // Default 15 items per page
        $data = [];

        switch ($type) {
            case 'multi_post_applicants':
                $data = $this->getMultiPostApplicants($perPage, $request);
                break;
            case 'center_utilization':
                $data = $this->getCenterUtilization(); // Usually small enough to not need pagination, but can add if needed
                break;
            case 'city_preference_stats':
                $data = $this->getCityPreferenceStats();
                break;
            case 'post_distribution':
                $data = $this->getPostDistribution();
                break;
            case 'unallocated_list':
                $data = $this->getUnallocatedList($perPage);
                break;
            case 'center_load_balance':
                $data = $this->getCenterLoadBalance();
                break;
            case 'city_wise_summary':
                $data = $this->getCityWiseSummary();
                break;
            case 'duplicate_check':
                $data = $this->getDuplicateCheck();
                break;
            case 'overall_summary':
                $data = $this->getOverallSummary();
                break;
            default:
                $data = ['message' => 'Invalid report type'];
        }

        return response()->json($data);
    }

    private function getMultiPostApplicants($perPage, Request $request)
    {
        // Base query for users with > 1 approved application
        $userIdsQuery = AppliedPosts::where('status', 'approved')
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1');

        // Apply filters to the base query if possible, or filter later
        // Since we need to filter by user attributes (CNIC, Email) or Allocation attributes (Center, City),
        // it's efficient to filter the User query.

        $userIds = $userIdsQuery->pluck('user_id');

        $query = User::whereIn('id', $userIds)
            ->with(['candidateProfile', 'contact', 'appliedPosts.post', 'appliedPosts.allotment.center.cities']);

        // Filter by Name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by Email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Filter by CNIC
        if ($request->filled('cnic')) {
            $query->whereHas('candidateProfile', function ($q) use ($request) {
                $q->where('cnic', 'like', '%' . $request->cnic . '%');
            });
        }

        // Filter by Roll Number (This is tricky as roll number is in allotment)
        if ($request->filled('roll_no')) {
            $query->whereHas('appliedPosts.allotment', function ($q) use ($request) {
                $q->where('roll_number', 'like', '%' . $request->roll_no . '%');
            });
        }

        // Filter by Center Name
        if ($request->filled('center')) {
            $query->whereHas('appliedPosts.allotment.center', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->center . '%');
            });
        }

        // Filter by City
        if ($request->filled('city')) {
            $query->whereHas('appliedPosts.allotment.center.cities', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->city . '%');
            });
        }

        return $query->paginate($perPage)
            ->through(function ($user) {
                $posts = $user->appliedPosts->map(function ($app) {
                    return [
                        'post_name' => $app->post->name,
                        'center_name' => $app->allotment->center->name ?? 'Not Allocated',
                        'center_id' => $app->allotment->center->id ?? null, // For clickable center
                        'city_name' => $app->allotment->center->cities->name ?? 'N/A',
                        'status' => $app->allotment ? 'Allocated' : 'Pending',
                        'roll_number' => $app->allotment->roll_number ?? 'N/A',
                    ];
                });

                // Check if all allocated centers are the same
                $centers = $posts->pluck('center_name')->filter(fn($c) => $c !== 'Not Allocated')->unique();
                $is_same_center = $centers->count() <= 1;

                return [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'cnic' => $user->candidateProfile->cnic ?? 'N/A',
                    'mobile' => $user->contact->mobile ?? 'N/A',
                    'posts_count' => $posts->count(),
                    'posts' => $posts,
                    'is_same_center' => $is_same_center,
                ];
            });
    }

    private function getCenterUtilization()
    {
        // ... (keep existing logic for now, maybe add pagination later if centers > 100)
        return center::with('cities')
            ->withCount('allotments')
            ->get()
            ->map(function ($center) {
                return [
                    'center_name' => $center->name,
                    'city_name' => $center->cities->name ?? 'N/A',
                    'capacity' => $center->capacity,
                    'allocated' => $center->allotments_count,
                    'utilization' => $center->capacity > 0 ? round(($center->allotments_count / $center->capacity) * 100, 2) : 0,
                ];
            });
    }

    private function getPostDistribution()
    {
        // Use 'AppliedPosts' as defined in Post model
        return Post::withCount(['AppliedPosts' => function($q) {
            $q->where('status', 'approved');
        }])->paginate(15)->through(function($post) {
            return [
                'post_name' => $post->name,
                'count' => $post->applied_posts_count // Laravel snake_cases the count
            ];
        });
    }

    private function getUnallocatedList($perPage)
    {
        return AppliedPosts::where('status', 'approved')
            ->doesntHave('allotment')
            ->with(['user', 'post', 'preferredCity'])
            ->paginate($perPage)
            ->through(function ($app) {
                return [
                    'candidate_name' => $app->user->name,
                    'post_name' => $app->post->name,
                    'preferred_city' => $app->preferredCity->name ?? 'N/A',
                    'applied_at' => $app->created_at->format('Y-m-d'),
                ];
            });
    }

    private function getCenterLoadBalance()
    {
        // Pagination for collection is manual
        $data = $this->getCenterUtilization(); // This returns a collection
        $sorted = $data->sortByDesc('utilization')->values();
        return $this->paginateCollection($sorted, 15);
    }

    private function getCityWiseSummary()
    {
        return City::withCount(['AppliedPosts' => function($q) {
            $q->where('status', 'approved');
        }])->paginate(15)->through(function($city) {
            return [
                'city_name' => $city->name,
                'applicants_count' => $city->applied_posts_count
            ];
        });
    }

    private function getDuplicateCheck()
    {
        $query = CentersAllotment::select('roll_number')
            ->groupBy('roll_number')
            ->havingRaw('COUNT(*) > 1');
            
        $duplicates = $query->get();
        
        return $this->paginateCollection($duplicates->map(function($item) {
            return ['roll_number' => $item->roll_number];
        }), 15);
    }
    
    private function paginateCollection($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return new \Illuminate\Pagination\LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    private function getOverallSummary()
    {
        return [
            'total_applicants' => AppliedPosts::where('status', 'approved')->count(),
            'total_allocated' => CentersAllotment::count(),
            'total_centers' => center::where('is_active', true)->count(),
            'total_posts' => Post::count(),
        ];
    }
}
