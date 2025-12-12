<?php

namespace App\Http\Controllers;

use App\Models\center;
use App\Models\cities;
use App\Models\Post;
use App\Models\ExamSchedule;
use App\Models\CenterPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CenterPostAssignmentController extends Controller
{
    /**
     * Display a listing of exam station cities with center counts.
     */
    public function index()
    {

        // Get all cities marked as test centers with their center counts and approved applicants
        $cities = cities::where('test_center', true)
            ->withCount(['centers', 'AppliedPosts as approved_applicants_count' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->with('district.province')
            ->orderBy('name')
            ->get();

        return inertia('Admin/CenterPosts/Index', [
            'cities' => $cities,
        ]);
    }

    /**
     * Get all centers for a specific city with their assigned posts.
     */
    public function getCentersByCity($cityId)
    {
        $centers = center::where('city_id', $cityId)
            ->where('is_active', true)
            ->with(['cities', 'postsWithSchedule' => function ($query) {
                $query->with('examSchedules');
            }])
            ->withCount('posts')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'centers' => $centers,
        ]);
    }

    /**
     * Get available posts that can be assigned to a center.
     */

    public function getAvailablePosts(center $center)
    {
        // Get ALL posts with their assignment status
        $posts = Post::with(['category', 'activeSchedule'])
            ->withCount(['AppliedPosts as approved_applicants_count' => function ($query) use ($center) {
                $query->where('status', 'approved')
                      ->where('preferred_city_id', $center->city_id);
            }])
            ->get()
            ->map(function ($post) use ($center) {
                // Check if this post is already assigned to the center
                $assignment = $center->posts()->where('posts.id', $post->id)->first();
                
                $post->is_assigned = $assignment ? true : false;
                $post->assignment_id = $assignment ? $assignment->pivot->id : null;
                $post->current_max_applicants = $assignment ? $assignment->pivot->max_applicants : null;
                
                return $post;
            });

        return response()->json([
            'posts' => $posts
        ]);
    }

    /**
     * Get exam schedules for a specific post.
     */
    public function getSchedulesByPost($postId)
    {
        $schedules = ExamSchedule::where('post_id', $postId)
            ->where('is_active', true)
            ->with('post')
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'schedules' => $schedules,
        ]);
    }

    /**
     * Assign posts to a center.
     */
    public function assignPosts(Request $request)
    {
        $validated = $request->validate([
            'center_id' => 'required|exists:centers,id',
            'assignments' => 'required|array|min:1',
            'assignments.*.post_id' => 'required|exists:posts,id',
            'assignments.*.exam_schedule_id' => 'nullable|exists:exam_schedules,id',
            'assignments.*.max_applicants' => 'nullable|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $center = center::findOrFail($validated['center_id']);
            $assignedCount = 0;

            foreach ($validated['assignments'] as $assignment) {
                // Check if this post is already assigned to this center
                $existing = CenterPost::where('center_id', $center->id)
                    ->where('post_id', $assignment['post_id'])
                    ->first();

                if ($existing) {
                    // Update existing assignment
                    $existing->update([
                        'exam_schedule_id' => $assignment['exam_schedule_id'] ?? null,
                        'max_applicants' => $assignment['max_applicants'] ?? null,
                        'assigned_by' => Auth::id(),
                    ]);
                } else {
                    // Create new assignment
                    CenterPost::create([
                        'center_id' => $center->id,
                        'post_id' => $assignment['post_id'],
                        'exam_schedule_id' => $assignment['exam_schedule_id'] ?? null,
                        'max_applicants' => $assignment['max_applicants'] ?? null,
                        'assigned_by' => Auth::id(),
                    ]);
                    $assignedCount++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully assigned {$assignedCount} post(s) to {$center->name}",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign posts: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing post assignment.
     */
    public function updateAssignment(Request $request, $centerPostId)
    {
        $validated = $request->validate([
            'exam_schedule_id' => 'nullable|exists:exam_schedules,id',
            'max_applicants' => 'nullable|integer|min:1',
        ]);

        try {
            $centerPost = CenterPost::findOrFail($centerPostId);

            $centerPost->update([
                'exam_schedule_id' => $validated['exam_schedule_id'] ?? $centerPost->exam_schedule_id,
                'max_applicants' => $validated['max_applicants'] ?? $centerPost->max_applicants,
                'assigned_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assignment updated successfully',
                'assignment' => $centerPost->load(['post', 'examSchedule']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update assignment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove a post assignment from a center.
     */
    public function removeAssignment($centerPostId)
    {
        try {
            $centerPost = CenterPost::findOrFail($centerPostId);
            $centerPost->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post assignment removed successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove assignment: ' . $e->getMessage(),
            ], 500);
        }
    }
}
