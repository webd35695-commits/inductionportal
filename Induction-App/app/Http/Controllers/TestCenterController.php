<?php

namespace App\Http\Controllers;

use App\Models\InductionPhase;
use App\Models\City;
use App\Models\Post;
use App\Models\Center;
use App\Models\CenterPost;
use App\Models\CentersAllotment;
use App\Models\cities;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TestCenterController extends Controller
{
    public function index()
    {
        $inductionPhases = InductionPhase::where('status', 'Active')->get(['id', 'title']);
        $canAssign = true; // Set to true as requested

        return Inertia::render('TestCenters/Index', [
            'inductionPhases' => $inductionPhases,
            'canAssign' => $canAssign,
        ]);
    }

    public function getCities(Request $request)
    {
        try {
            $inductionPhaseId = $request->input('induction_phase_id');

            if (!$inductionPhaseId) {
                return response()->json(['error' => 'Induction phase ID is required'], 400);
            }

            $cities = cities::whereHas('centers', function ($query) {
                $query->where('is_active', 1);
            })->get(['id', 'name']);

            return response()->json($cities);
        } catch (\Exception $e) {
            Log::error('Error fetching cities: ' . $e->getMessage(), ['request' => $request->all()]);
            return response()->json(['error' => 'Failed to fetch cities: ' . $e->getMessage()], 500);
        }
    }

    public function getPostsByCity(Request $request)
    {
        try {
            $inductionPhaseId = $request->input('induction_phase_id');
            $cityId = $request->input('city_id');

            if (!$inductionPhaseId || !$cityId) {
                return response()->json(['error' => 'Induction phase ID and city ID are required'], 400);
            }

            // Fetch all posts for the induction phase with total application counts for the city
            $posts = Post::select('posts.id', 'posts.name', 'posts.post_abbreviation')
                ->where('posts.induction_phase_id', $inductionPhaseId)
                ->leftJoin('applied_posts', function ($join) use ($cityId) {
                    $join->on('posts.id', '=', 'applied_posts.post_id')
                         ->where('applied_posts.preferred_city_id', $cityId);
                })
                ->groupBy('posts.id', 'posts.name', 'posts.post_abbreviation')
                ->selectRaw('COALESCE(COUNT(applied_posts.id), 0) as application_count')
                ->get();

            // Fetch centers with assigned posts and application counts
            $centers = Center::where('city_id', $cityId)
                ->where('is_active', 1)
                ->with(['posts' => function ($query) use ($inductionPhaseId) {
                    $query->where('induction_phase_id', $inductionPhaseId)
                          ->select('posts.id', 'posts.name', 'posts.post_abbreviation')
                          ->withPivot('max_applicants');
                }])
                ->get(['id', 'name', 'code', 'address'])
                ->map(function ($center) use ($inductionPhaseId) {
                    $center->posts = $center->posts->map(function ($post) use ($center, $inductionPhaseId) {
                        $applicationCount = CentersAllotment::where('center_id', $center->id)
                            ->join('applied_posts', 'centers_allotments.applied_post_id', '=', 'applied_posts.id')
                            ->join('posts', 'applied_posts.post_id', '=', 'posts.id')
                            ->where('applied_posts.post_id', $post->id)
                            ->where('posts.induction_phase_id', $inductionPhaseId)
                            ->count();
                        $post->application_count = $applicationCount;
                        return $post;
                    });
                    return $center;
                });

            // Fetch all posts for the induction phase (for assignment dropdown)
            $allPosts = Post::where('induction_phase_id', $inductionPhaseId)
                ->get(['id', 'name', 'post_abbreviation']);

            return response()->json([
                'posts' => $posts,
                'centers' => $centers,
                'allPosts' => $allPosts,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching posts and centers: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Failed to fetch posts and centers: ' . $e->getMessage()], 500);
        }
    }

    public function assignPosts(Request $request)
    {
        try {
            $request->validate([
                'assignments' => 'required|array',
                'assignments.*.center_id' => 'required|exists:centers,id',
                'assignments.*.post_id' => 'required|exists:posts,id',
                'assignments.*.max_applicants' => 'nullable|integer|min:0',
            ]);

            DB::beginTransaction();

            foreach ($request->assignments as $assignment) {
                $exists = CenterPost::where('center_id', $assignment['center_id'])
                    ->where('post_id', $assignment['post_id'])
                    ->exists();

                if (!$exists) {
                    CenterPost::create([
                        'center_id' => $assignment['center_id'],
                        'post_id' => $assignment['post_id'],
                        'max_applicants' => $assignment['max_applicants'] ?? null,
                    ]);
                } else {
                    CenterPost::where('center_id', $assignment['center_id'])
                        ->where('post_id', $assignment['post_id'])
                        ->update(['max_applicants' => $assignment['max_applicants'] ?? null]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Posts assigned successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error assigning posts: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Failed to assign posts: ' . $e->getMessage()], 500);
        }
    }

    public function updateMaxApplicants(Request $request)
    {
        try {
            $request->validate([
                'center_id' => 'required|exists:centers,id',
                'post_id' => 'required|exists:posts,id',
                'max_applicants' => 'nullable|integer|min:0',
            ]);

            $updated = CenterPost::where('center_id', $request->center_id)
                ->where('post_id', $request->post_id)
                ->update(['max_applicants' => $request->max_applicants]);

            if (!$updated) {
                return response()->json(['error' => 'Post assignment not found'], 404);
            }

            return response()->json(['message' => 'Max applicants updated successfully']);
        } catch (\Exception $e) {
            Log::error('Error updating max applicants: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Failed to update max applicants: ' . $e->getMessage()], 500);
        }
    }

    public function removePost(Request $request)
    {
        try {
            $request->validate([
                'center_id' => 'required|exists:centers,id',
                'post_id' => 'required|exists:posts,id',
            ]);

            $deleted = CenterPost::where('center_id', $request->center_id)
                ->where('post_id', $request->post_id)
                ->delete();

            if (!$deleted) {
                return response()->json(['error' => 'Post assignment not found'], 404);
            }

            return response()->json(['message' => 'Post removed successfully']);
        } catch (\Exception $e) {
            Log::error('Error removing post: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Failed to remove post: ' . $e->getMessage()], 500);
        }
    }
}