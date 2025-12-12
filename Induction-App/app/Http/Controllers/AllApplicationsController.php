<?php

namespace App\Http\Controllers;

use App\Models\AppliedPosts;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use Illuminate\Support\Facades\Response;

class AllApplicationsController extends Controller
{
    /**
     * Display the applications dashboard
     */
    public function index()
    {
        $posts = Post::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('Applications/Index', ['posts' => $posts]);
    }

    /**
     * Get paginated and filtered applications
     */
    public function getApplications(Request $request)
    {
        // Input validation with security rules
        $validator = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:10|max:100',
            'name' => 'nullable|string|max:255',
            'cnic' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'mobile' => 'nullable|string|max:20',
            'post' => 'nullable|exists:posts,id',
            'status' => 'nullable|in:pending,approved,rejected,reverted',
            'date' => 'nullable|date',
            'dateFrom' => 'nullable|date',
            'dateTo' => 'nullable|date|after_or_equal:dateFrom',
            'sort_field' => 'nullable|in:name,status,date,cnic',
            'sort_direction' => 'nullable|in:asc,desc',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input parameters',
                'messages' => $validator->errors(),
            ], 422);
        }

        try {
            $perPage = $request->input('per_page', 50);
            $sortField = $request->input('sort_field', 'date');
            $sortDirection = $request->input('sort_direction', 'desc');

            // Build secure query with proper joins
            $query = AppliedPosts::query()
                ->select(
                    'applied_posts.id',
                    'users.name',
                    'users.email',
                    'candidate_profiles.cnic',
                    'posts.name as post',
                    'applied_posts.status',
                    'applied_posts.applied_at as date',
                    'applied_posts.rejection_reason as comments',
                    'user_contacts.mobile'
                )
                ->join('users', 'applied_posts.user_id', '=', 'users.id')
                ->join('candidate_profiles', 'users.id', '=', 'candidate_profiles.user_id')
                ->join('posts', 'applied_posts.post_id', '=', 'posts.id')
                ->leftJoin('user_contacts', 'users.id', '=', 'user_contacts.user_id');

            // Apply filters with SQL injection protection
            if ($request->filled('name')) {
                $query->where('users.name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('cnic')) {
                $query->where('candidate_profiles.cnic', 'like', '%' . $request->cnic . '%');
            }

            if ($request->filled('email')) {
                $query->where('users.email', 'like', '%' . $request->email . '%');
            }

            if ($request->filled('mobile')) {
                $query->where('user_contacts.mobile', 'like', '%' . $request->mobile . '%');
            }

            if ($request->filled('post')) {
                $query->where('applied_posts.post_id', $request->post);
            }

            if ($request->filled('status')) {
                $query->where('applied_posts.status', $request->status);
            }

            if ($request->filled('date')) {
                $query->whereDate('applied_posts.applied_at', $request->date);
            }

            if ($request->filled('dateFrom')) {
                $query->whereDate('applied_posts.applied_at', '>=', $request->dateFrom);
            }

            if ($request->filled('dateTo')) {
                $query->whereDate('applied_posts.applied_at', '<=', $request->dateTo);
            }

            // Apply sorting
            $sortMap = [
                'name' => 'users.name',
                'status' => 'applied_posts.status',
                'date' => 'applied_posts.applied_at',
                'cnic' => 'candidate_profiles.cnic'
            ];

            $query->orderBy($sortMap[$sortField] ?? 'applied_posts.applied_at', $sortDirection);

            // Get paginated results
            $applications = $query->paginate($perPage);

            // Get statistics
            $statistics = $this->getStatistics($request);

            // Log the access
            Log::info('Applications accessed', [
                'user_id' => Auth::id(),
                'filters' => $request->only(['name', 'cnic', 'post', 'status', 'date']),
                'timestamp' => now()
            ]);

            return response()->json([
                'data' => $applications->items(),
                'total' => $applications->total(),
                'current_page' => $applications->currentPage(),
                'last_page' => $applications->lastPage(),
                'per_page' => $applications->perPage(),
                'statistics' => $statistics
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch applications', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'error' => 'Failed to fetch applications',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Get application statistics
     */
    private function getStatistics(Request $request)
    {
        $query = AppliedPosts::query();

        // Apply same filters as main query
        if ($request->filled('post')) {
            $query->where('post_id', $request->post);
        }

        if ($request->filled('dateFrom')) {
            $query->whereDate('applied_at', '>=', $request->dateFrom);
        }

        if ($request->filled('dateTo')) {
            $query->whereDate('applied_at', '<=', $request->dateTo);
        }

        return [
            'total' => $query->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
            'pending' => (clone $query)->whereNull('status')->orWhere('status', 'pending')->count(),
            'reverted' => (clone $query)->where('status', 'reverted')->count(),
        ];
    }

    /**
     * Show single application details
     */
   public function show($id)
{
    try {
        // Validate ID
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:applied_posts,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid application ID'
            ], 404);
        }

        $application = AppliedPosts::query()
            ->with(['user.candidateProfile', 'post', 'user.userContact', 'user.qualifications.degreeType'])
            ->findOrFail($id);

        // Log access
        Log::info('Application details viewed', [
            'application_id' => $id,
            'user_id' => Auth::id(),
            'timestamp' => now()
        ]);

        $profile = $application->user->candidateProfile;
        $contact = $application->user->userContact;

        return response()->json([
            // Application Details
            'id' => $application->id,
            'post' => $application->post->name,
            'status' => $application->status,
            'applied_at' => $application->applied_at,
            'rejection_reason' => $application->rejection_reason,

            // User Basic Info
            'name' => $application->user->name,
            'email' => $application->user->email,

            // Profile Details
            'father_name' => $profile->father_name ?? 'N/A',
            'cnic' => $profile->cnic ?? 'N/A',
            'gender' => $profile->gender ?? 'N/A',
            'marital_status' => $profile->marital_status ?? 'N/A',
            'date_of_birth' => $profile->date_of_birth ?? null,
            'religion' => $profile->religion ?? 'N/A',
            'disability' => $profile->disability ?? 'N/A',
            'photo_path' => $profile->photo_path ?? null,

            // Contact Details
            'phone' => $contact->phone ?? 'N/A',
            'mobile' => $contact->mobile ?? 'N/A',
            'postal_address' => $contact->postal_address ?? 'N/A',
            'permanent_address' => $contact->permanent_address ?? 'N/A',

            // Qualifications
            'qualifications' => $application->user->qualifications->map(function($q) {
                return [
                    'degree_type' => $q->degreeType->name ?? 'N/A',
                    'degree_name' => $q->degree_name,
                    'institute_name' => $q->institute_name,
                    'passing_year' => $q->passing_year,
                    'status' => $q->status
                ];
            })
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to fetch application details', [
            'application_id' => $id,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'error' => 'Failed to fetch application details',
            'message' => config('app.debug') ? $e->getMessage() : 'An error occurred'
        ], 500);
    }
}

    /**
     * Update application status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make(array_merge($request->all(), ['id' => $id]), [
            'id' => 'required|integer|exists:applied_posts,id',
            'status' => 'required|in:approved,rejected,reverted,pending',
            'rejection_reason' => 'required_if:status,rejected|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input',
                'messages' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $application = AppliedPosts::findOrFail($id);
            $oldStatus = $application->status;

            $application->status = $request->status;

            if ($request->status === 'rejected' && $request->filled('rejection_reason')) {
                $application->rejection_reason = $request->rejection_reason;
            }

            $application->updated_by = Auth::id();
            $application->save();

            // Log the status change
            Log::info('Application status updated', [
                'application_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'updated_by' => Auth::id(),
                'reason' => $request->rejection_reason ?? null,
                'timestamp' => now()
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Application status updated successfully',
                'application' => [
                    'id' => $application->id,
                    'status' => $application->status,
                    'comments' => $application->rejection_reason
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update application status', [
                'application_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'error' => 'Failed to update status',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Bulk update application statuses
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1|max:100',
            'ids.*' => 'integer|exists:applied_posts,id',
            'status' => 'required|in:approved,rejected,reverted,pending',
            'rejection_reason' => 'required_if:status,rejected|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input',
                'messages' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $updateData = [
                'status' => $request->status,
                'updated_by' => Auth::id(),
                'updated_at' => now()
            ];

            if ($request->status === 'rejected' && $request->filled('rejection_reason')) {
                $updateData['rejection_reason'] = $request->rejection_reason;
            }

            $affected = AppliedPosts::whereIn('id', $request->ids)
                ->update($updateData);

            // Log bulk action
            Log::info('Bulk status update performed', [
                'application_ids' => $request->ids,
                'status' => $request->status,
                'affected_count' => $affected,
                'updated_by' => Auth::id(),
                'timestamp' => now()
            ]);

            DB::commit();

            return response()->json([
                'message' => "Successfully updated {$affected} application(s)",
                'affected' => $affected
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Bulk update failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'error' => 'Bulk update failed',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Bulk delete applications
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1|max:100',
            'ids.*' => 'integer|exists:applied_posts,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input',
                'messages' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Log before deletion
            Log::warning('Bulk deletion initiated', [
                'application_ids' => $request->ids,
                'deleted_by' => Auth::id(),
                'timestamp' => now()
            ]);

            $deleted = AppliedPosts::whereIn('id', $request->ids)->delete();

            DB::commit();

            return response()->json([
                'message' => "Successfully deleted {$deleted} application(s)",
                'deleted' => $deleted
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Bulk deletion failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'error' => 'Bulk deletion failed',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Delete single application
     */
    public function destroy($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:applied_posts,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Invalid application ID'], 404);
            }

            $application = AppliedPosts::findOrFail($id);

            Log::warning('Application deleted', [
                'application_id' => $id,
                'deleted_by' => Auth::id(),
                'timestamp' => now()
            ]);

            $application->delete();

            return response()->json([
                'message' => 'Application deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete application', [
                'application_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Failed to delete application',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Export applications to CSV
     */
    public function export(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'cnic' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'mobile' => 'nullable|string|max:20',
            'post' => 'nullable|exists:posts,id',
            'status' => 'nullable|in:pending,approved,rejected,reverted',
            'date' => 'nullable|date',
            'dateFrom' => 'nullable|date',
            'dateTo' => 'nullable|date|after_or_equal:dateFrom',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid export parameters',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $query = AppliedPosts::query()
                ->select(
                    'applied_posts.id',
                    'users.name',
                    'users.email',
                    'candidate_profiles.cnic',
                    'posts.name as post_name',
                    'applied_posts.status',
                    'applied_posts.applied_at as date',
                    'applied_posts.rejection_reason as comments',
                    'user_contacts.mobile',
                    'user_contacts.permanent_address'
                )
                ->join('users', 'applied_posts.user_id', '=', 'users.id')
                ->join('candidate_profiles', 'users.id', '=', 'candidate_profiles.user_id')
                ->join('posts', 'applied_posts.post_id', '=', 'posts.id')
                ->leftJoin('user_contacts', 'users.id', '=', 'user_contacts.user_id');

            // Apply filters (same as getApplications)
            if ($request->filled('name')) {
                $query->where('users.name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('cnic')) {
                $query->where('candidate_profiles.cnic', 'like', '%' . $request->cnic . '%');
            }

            if ($request->filled('email')) {
                $query->where('users.email', 'like', '%' . $request->email . '%');
            }

            if ($request->filled('mobile')) {
                $query->where('user_contacts.mobile', 'like', '%' . $request->mobile . '%');
            }

            if ($request->filled('post')) {
                $query->where('applied_posts.post_id', $request->post);
            }

            if ($request->filled('status')) {
                $query->where('applied_posts.status', $request->status);
            }

            if ($request->filled('date')) {
                $query->whereDate('applied_posts.applied_at', $request->date);
            }

            if ($request->filled('dateFrom')) {
                $query->whereDate('applied_posts.applied_at', '>=', $request->dateFrom);
            }

            if ($request->filled('dateTo')) {
                $query->whereDate('applied_posts.applied_at', '<=', $request->dateTo);
            }

            // Limit export to prevent memory issues
            $applications = $query->limit(10000)->get();

            // Create CSV
            $csv = Writer::createFromString();
            $csv->insertOne([
                'ID', 'Name', 'Email', 'CNIC', 'Post', 'Status',
                'Applied Date', 'Comments', 'Mobile', 'Permanent Address'
            ]);

            foreach ($applications as $application) {
                $csv->insertOne([
                    $application->id,
                    $application->name,
                    $application->email,
                    $application->cnic,
                    $application->post_name,
                    $application->status ?? 'Pending',
                    $application->date,
                    $application->comments ?? 'No comments',
                    $application->mobile ?? 'N/A',
                    $application->permanent_address ?? 'N/A',
                ]);
            }

            // Log export
            Log::info('Applications exported', [
                'user_id' => Auth::id(),
                'record_count' => $applications->count(),
                'timestamp' => now()
            ]);

            $filename = 'applications_' . now()->format('Y-m-d_His') . '.csv';

            return Response::make($csv->getContent(), 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);

        } catch (\Exception $e) {
            Log::error('Export failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'error' => 'Export failed',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }
}
