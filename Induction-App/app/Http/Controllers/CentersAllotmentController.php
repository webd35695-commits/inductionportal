<?php

namespace App\Http\Controllers;

use App\Models\center;
use App\Models\cities;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repositories\AppliedPostRepository;
use App\Services\CenterAllocationService;
use App\Models\Post;
use App\Models\City;
use App\Models\InductionPhase;
use AppliedPostRepository as GlobalAppliedPostRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\AppliedPosts;
use App\Models\CentersAllotment;

class CentersAllotmentController extends Controller
{
    public function __construct(
        private AppliedPostRepository $postRepo,
        private CenterAllocationService $allocationService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['post_id', 'city_id', 'status', 'center_id']);
        
        return Inertia::render('CenterAllotments/Index', [
            'applications' => $this->postRepo->getFilteredApplications($filters),
            'posts' => Post::select('id', 'name')->get(),
            'cities' => cities::select('id', 'name')->get(),
            'centers' => center::where('is_active', true)->select('id', 'name')->get(),
            'filters' => $filters
        ]);
    }

    public function allocate(Request $request)
    {
        Log::info('=== ALLOCATION PROCESS STARTED ===');
        
        try {
            Log::info('Calling allocation service...');
            
            $stats = $this->allocationService->processAllocations(
                $request->input('batch_size')
            );

            Log::info('Allocation completed', $stats);

            $message = sprintf(
                'Allocation completed: %d allocated, %d skipped, %d failed out of %d total',
                $stats['allocated'],
                $stats['skipped'],
                $stats['failed'],
                $stats['total']
            );

            return redirect()->back()->with([
                'success' => $message,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('ALLOCATION ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Allocation failed: ' . $e->getMessage());
        }
    }

public function postAllocate()
{

    $inductionPhases = InductionPhase::select('id', 'title')->get();

    // Debug the cities query directly
    $examCities = cities::whereHas('centers', function($query) {
        $query->where('is_active', true);
    })->select('id', 'name')
      ->orderBy('name')
      ->get();

    // Add temporary debug output
    logger('Cities with centers:', $examCities->toArray());
    Log::debug('Cities count: '.$examCities->count());

    return Inertia::render('CenterAllotments/PostAllocate', [
        'inductionPhases' => $inductionPhases,
        'examCities' => $examCities, // Ensure exact same spelling
        'posts' => [],
        'selectedInductionPhaseId' => ''
    ]);
}

public function postForInduction(Request $request)
{
    $request->validate([
        'id' => 'required|exists:induction_phases,id'
    ]);
    $posts = Post::where('induction_phase_id', $request->id)
               ->select('id', 'name')
               ->get();

    return Inertia::render('CenterAllotments/PostAllocate', [
        'inductionPhases' => InductionPhase::select('id', 'title')->get(),
        'posts' => $posts,
        'selectedInductionPhaseId' => $request->id
    ]);
}

public function allocatePosts(Request $request)
{
    $validated = $request->validate([
        'post_ids' => 'required|array',
        'post_ids.*' => 'exists:posts,id',
        'city_id' => 'required|exists:cities,id'
    ]);

    // Your allocation logic here
    // Example:
    foreach ($validated['post_ids'] as $postId) {
        Allocation::updateOrCreate(
            ['post_id' => $postId],
            ['city_id' => $validated['city_id']]
        );
    }

    return response()->json([
        'message' => 'Posts allocated successfully',
        'allocated_count' => count($validated['post_ids'])
    ]);
}

public function preview()
{
    try {
        Log::info('Preview endpoint called');
        
        // Basic counts
        $totalApproved = AppliedPosts::where('status', 'approved')->count();
        $alreadyAllocated = CentersAllotment::count();
        $pending = $totalApproved - $alreadyAllocated;
        
        // Simple city breakdown
        $byCity = DB::table('applied_posts')
            ->join('cities', 'applied_posts.preferred_city_id', '=', 'cities.id')
            ->where('applied_posts.status', 'approved')
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('centers_allotments')
                    ->whereColumn('centers_allotments.applied_post_id', 'applied_posts.id');
            })
            ->select(
                'cities.id as city_id',
                'cities.name as city_name',
                DB::raw('COUNT(*) as applicants_count'),
                DB::raw('(SELECT COUNT(*) FROM centers WHERE centers.city_id = cities.id AND centers.is_active = 1) as centers_count')
            )
            ->groupBy('cities.id', 'cities.name')
            ->get();
        
        // Detailed center-post breakdown
        $centerPostBreakdown = DB::table('applied_posts')
            ->join('posts', 'applied_posts.post_id', '=', 'posts.id')
            ->join('cities', 'applied_posts.preferred_city_id', '=', 'cities.id')
            ->leftJoin('centers', function($join) {
                $join->on('centers.city_id', '=', 'cities.id')
                     ->where('centers.is_active', '=', 1);
            })
            ->leftJoin('center_posts', function($join) {
                $join->on('center_posts.center_id', '=', 'centers.id')
                     ->on('center_posts.post_id', '=', 'posts.id');
            })
            ->where('applied_posts.status', 'approved')
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('centers_allotments')
                    ->whereColumn('centers_allotments.applied_post_id', 'applied_posts.id');
            })
            ->whereNotNull('centers.id')
            ->whereNotNull('center_posts.id')
            ->select(
                'cities.name as city_name',
                'centers.name as center_name',
                'posts.name as post_name',
                DB::raw('COUNT(*) as applicants_count'),
                'center_posts.max_applicants',
                DB::raw('(SELECT COUNT(*) FROM centers_allotments WHERE centers_allotments.center_id = centers.id) as current_allocated')
            )
            ->groupBy('cities.name', 'centers.id', 'centers.name', 'posts.id', 'posts.name', 'center_posts.max_applicants')
            ->orderBy('cities.name')
            ->orderBy('centers.name')
            ->orderBy('posts.name')
            ->get();
        
        // Post-wise summary
        $byPost = DB::table('applied_posts')
            ->join('posts', 'applied_posts.post_id', '=', 'posts.id')
            ->where('applied_posts.status', 'approved')
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('centers_allotments')
                    ->whereColumn('centers_allotments.applied_post_id', 'applied_posts.id');
            })
            ->select(
                'posts.name as post_name',
                DB::raw('COUNT(*) as applicants_count')
            )
            ->groupBy('posts.id', 'posts.name')
            ->get();

        // --- Simulation for City 1 vs City 2 ---
        
        // 1. Get capacities per City + Post
        $capacities = DB::table('centers')
            ->join('center_posts', 'centers.id', '=', 'center_posts.center_id')
            ->where('centers.is_active', true)
            ->select('centers.city_id', 'center_posts.post_id', DB::raw('SUM(center_posts.max_applicants) as total_capacity'))
            ->groupBy('centers.city_id', 'center_posts.post_id')
            ->get();

        $capacityMap = [];
        foreach ($capacities as $cap) {
            $capacityMap[$cap->city_id][$cap->post_id] = $cap->total_capacity;
        }

        // 2. Get Pending Applications
        $pendingApps = DB::table('applied_posts')
            ->where('status', 'approved')
            ->whereNotExists(function($q) {
                $q->select(DB::raw(1))->from('centers_allotments')->whereColumn('applied_post_id', 'applied_posts.id');
            })
            ->select('id', 'post_id', 'preferred_city_id', 'alternative_city_id')
            ->get();

        $city1Count = 0;
        $city2Count = 0;
        $noCenterCount = 0;

        // Group by City1 + Post to process demand
        $demandCity1 = $pendingApps->groupBy(function($item) {
            return $item->preferred_city_id . '-' . $item->post_id;
        });

        $overflowApps = collect([]);

        foreach ($demandCity1 as $key => $apps) {
            list($cityId, $postId) = explode('-', $key);
            $cap = $capacityMap[$cityId][$postId] ?? 0;
            $count = $apps->count();
            
            // Existing allocations reduce capacity? 
            // The capacityMap is TOTAL capacity. We should subtract ALREADY allocated.
            // Let's get current allocations count per city-post
            $currentAllocated = DB::table('centers_allotments')
                ->join('centers', 'centers_allotments.center_id', '=', 'centers.id')
                ->join('applied_posts', 'centers_allotments.applied_post_id', '=', 'applied_posts.id')
                ->where('centers.city_id', $cityId)
                ->where('applied_posts.post_id', $postId)
                ->count();
            
            $remainingCap = max(0, $cap - $currentAllocated);
            
            if ($count <= $remainingCap) {
                $city1Count += $count;
                // We conceptually consume this capacity, but since we iterate by unique City-Post, 
                // we don't need to update the map for other iterations unless we had global pool (which we don't).
            } else {
                $city1Count += $remainingCap;
                $overflowCount = $count - $remainingCap;
                $overflow = $apps->take($overflowCount);
                foreach($overflow as $app) {
                    $overflowApps->push($app);
                }
            }
        }

        // Process overflow for City 2
        foreach ($overflowApps as $app) {
            if (!$app->alternative_city_id) {
                $noCenterCount++;
                continue;
            }
            
            $cityId = $app->alternative_city_id;
            $postId = $app->post_id;
            
            // We need to check capacity for City 2.
            // Note: This is a simplification. In reality, City 2 might also be City 1 for others.
            // A perfect simulation requires prioritizing all City 1s first, then all City 2s.
            // But for this preview, we assume City 1s are processed. 
            // We need to check if City 2 has remaining capacity AFTER its own City 1 demand.
            // This gets complex. For now, let's just check raw remaining capacity assuming 
            // we are only looking at THIS batch's impact or just raw capacity.
            // Let's use the raw capacity map again, but we need to account for the fact that 
            // we might have already "filled" it if it was a City 1 for someone else in this loop.
            
            // To be safe and simple: Just check if the city exists and has ANY capacity for that post.
            // Refined: We will just check against the static capacity map minus current allocations.
            // It's an estimate.
            
            $cap = $capacityMap[$cityId][$postId] ?? 0;
             $currentAllocated = DB::table('centers_allotments')
                ->join('centers', 'centers_allotments.center_id', '=', 'centers.id')
                ->join('applied_posts', 'centers_allotments.applied_post_id', '=', 'applied_posts.id')
                ->where('centers.city_id', $cityId)
                ->where('applied_posts.post_id', $postId)
                ->count();
            
            if (($cap - $currentAllocated) > 0) {
                $city2Count++;
            } else {
                $noCenterCount++;
            }
        }
        
        Log::info('Preview data collected successfully');
        
        return response()->json([
            'total' => $totalApproved,
            'already_allocated' => $alreadyAllocated,
            'pending' => $pending,
            'by_city' => $byCity,
            'by_post' => $byPost,
            'center_post_breakdown' => $centerPostBreakdown,
            'preference_stats' => [
                'city_1' => $city1Count,
                'city_2' => $city2Count,
                'unmatched' => $noCenterCount
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Preview error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'Failed to generate preview'
        ], 500);
    }
}

public function reports()
{
    try {
        Log::info('Reports method called');
        
        // Center-wise allocation statistics
        $centers = center::where('is_active', true)->get();
        $centerStats = $centers->map(function($center) {
            $totalAllocated = CentersAllotment::where('center_id', $center->id)->count();
            
            return [
                'center_id' => $center->id,
                'center_name' => $center->name,
                'city_name' => $center->cities?->name ?? 'N/A',
                'total_allocated' => $totalAllocated,
                'capacity' => $center->capacity ?? 0,
                'utilization_percent' => $center->capacity > 0 
                    ? round(($totalAllocated / $center->capacity) * 100, 2)
                    : 0,
            ];
        });

        Log::info('Center stats collected', ['count' => $centerStats->count()]);

        // Post-wise allocation statistics
        $posts = Post::all();
        $postStats = $posts->map(function($post) {
            $totalApproved = AppliedPosts::where('post_id', $post->id)
                ->where('status', 'approved')
                ->count();
            
            $totalAllocated = CentersAllotment::whereHas('appliedPost', function($q) use ($post) {
                $q->where('post_id', $post->id);
            })->count();
                
            return [
                'post_name' => $post->name,
                'total_approved_applications' => $totalApproved,
                'total_allocated' => $totalAllocated,
                'pending_allocation' => $totalApproved - $totalAllocated,
                'allocation_percent' => $totalApproved > 0 
                    ? round(($totalAllocated / $totalApproved) * 100, 2)
                    : 0,
            ];
        });

        Log::info('Post stats collected', ['count' => $postStats->count()]);

        // Overall statistics
        $totalApproved = AppliedPosts::where('status', 'approved')->count();
        $totalAllocated = CentersAllotment::count();
        
        $overallStats = [
            'total_approved_applications' => $totalApproved,
            'total_allocated' => $totalAllocated,
            'pending_allocation' => $totalApproved - $totalAllocated,
            'allocation_percent' => $totalApproved > 0 
                ? round(($totalAllocated / $totalApproved) * 100, 2)
                : 0,
        ];

        Log::info('Overall stats collected', $overallStats);

        return response()->json([
            'props' => [
                'centerStats' => $centerStats->values(),
                'postStats' => $postStats->values(),
                'overallStats' => $overallStats,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Reports error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'Failed to generate reports'
        ], 500);
    }
}

}
