<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppliedPosts;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {

        try {
            // Start with AppliedPosts and join related tables
            $query = AppliedPosts::query()
                ->with([
                    'user.candidateProfile',
                    'user.userContact',
                    'post'
                ])
                ->join('users', 'applied_posts.user_id', '=', 'users.id')
                ->join('candidate_profiles', 'users.id', '=', 'candidate_profiles.user_id')
                ->leftJoin('user_contacts', 'users.id', '=', 'user_contacts.user_id')
                ->leftJoin('posts', 'applied_posts.post_id', '=', 'posts.id')
                ->select(
                    'applied_posts.*',
                    'users.name as user_name',
                    'users.email',
                    'candidate_profiles.name as candidate_name',
                    'candidate_profiles.cnic',
                    'posts.name as post_title',
                    'posts.name as post_name'
                );

            // Apply filters
            if ($request->filled('name')) {
                $query->where(function($q) use ($request) {
                    $q->where('users.name', 'like', '%' . $request->name . '%')
                      ->orWhere('candidate_profiles.name', 'like', '%' . $request->name . '%');
                });
            }

            if ($request->filled('cnic')) {
                $query->where('candidate_profiles.cnic', 'like', '%' . $request->cnic . '%');
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

            // Apply sorting
            $sortField = $request->get('sort_field', 'applied_at');
            $sortDirection = $request->get('sort_direction', 'desc');

            // Map frontend sort fields to database columns
            $sortMapping = [
                'name' => 'candidate_profiles.name',
                'status' => 'applied_posts.status',
                'date' => 'applied_posts.applied_at',
                'applied_at' => 'applied_posts.applied_at'
            ];

            $dbSortField = $sortMapping[$sortField] ?? 'applied_posts.applied_at';
            $query->orderBy($dbSortField, $sortDirection);

            // Paginate results
            $perPage = min($request->get('per_page', 50), 100);
            $applications = $query->paginate($perPage);

            // Transform the data for frontend
            $applications->getCollection()->transform(function ($application) {
                return [
                    'id' => $application->id,
                    'name' => $application->candidate_name ?: $application->user_name,
                    'email' => $application->email,
                    'cnic' => $application->cnic,
                    'post' => $application->post_title ?: $application->post_name,
                    'status' => $application->status,
                    'date' => $application->applied_at,
                    'comments' => $application->rejection_reason, // Using rejection_reason as comments
                    'user_id' => $application->user_id,
                    'post_id' => $application->post_id,
                    'preferred_city_id' => $application->preferred_city_id,
                    'alternative_city_id' => $application->alternative_city_id,
                ];
            });

            return response()->json($applications);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch applications',
                'message' => $e->getMessage(),
                'data' => [],
                'total' => 0
            ], 500);
        }
    }

    public function show($id)
{
    try {
        $application = AppliedPosts::with([
            'user.candidateProfile',
            'user.userContact',
            'user.qualifications',
            'post'
        ])->findOrFail($id);

        // Transform single application data
        $transformedApplication = [
            'id' => $application->id,
            'name' => $application->user->candidateProfile->name ?? $application->user->name,
            'email' => $application->user->email,
            'cnic' => $application->user->candidateProfile->cnic ?? '',
            'post' => $application->post->title ?? $application->post->name ?? '',
            'status' => $application->status,
            'date' => $application->applied_at,
            'comments' => $application->rejection_reason,
            'user_id' => $application->user_id,
            'post_id' => $application->post_id,
            'preferred_city_id' => $application->preferred_city_id,
            'alternative_city_id' => $application->alternative_city_id,
            // Additional user details
            'father_name' => $application->user->candidateProfile->father_name ?? '',
            'gender' => $application->user->candidateProfile->gender ?? '',
            'date_of_birth' => $application->user->candidateProfile->date_of_birth ?? '',
            'phone' => $application->user->userContact->phone ?? '',
            'mobile' => $application->user->userContact->mobile ?? '',
            'permanent_address' => $application->user->userContact->permanent_address ?? '',
            // Qualifications
            'qualifications' => $application->user->qualifications->map(function ($qualification) {
                return [
                    'degree_name' => $qualification->degree_name,
                    'institute_name' => $qualification->institute_name,
                    'passing_year' => $qualification->passing_year,
                    'status' => $qualification->status,
                ];
            })->toArray(),
        ];

        return response()->json($transformedApplication);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Application not found',
            'message' => $e->getMessage()
        ], 404);
    }
}

    public function destroy($id)
    {
        try {
            $application = AppliedPosts::findOrFail($id);
            $application->delete();

            return response()->json([
                'message' => 'Application deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete application',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        try {
            // Get all applications with filters (without pagination)
            $query = AppliedPosts::query()
                ->join('users', 'applied_posts.user_id', '=', 'users.id')
                ->join('candidate_profiles', 'users.id', '=', 'candidate_profiles.user_id')
                ->leftJoin('user_contacts', 'users.id', '=', 'user_contacts.user_id')
                ->leftJoin('posts', 'applied_posts.post_id', '=', 'posts.id')
                ->select(
                    'applied_posts.*',
                    'users.name as user_name',
                    'users.email',
                    'candidate_profiles.name as candidate_name',
                    'candidate_profiles.cnic',
                    'candidate_profiles.father_name',
                    'posts.title as post_title',
                    'posts.name as post_name',
                    'user_contacts.phone',
                    'user_contacts.mobile'
                );

            // Apply same filters as index method
            if ($request->filled('name')) {
                $query->where(function($q) use ($request) {
                    $q->where('users.name', 'like', '%' . $request->name . '%')
                      ->orWhere('candidate_profiles.name', 'like', '%' . $request->name . '%');
                });
            }

            if ($request->filled('cnic')) {
                $query->where('candidate_profiles.cnic', 'like', '%' . $request->cnic . '%');
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

            $applications = $query->get();

            // Create CSV content
            $csvContent = "Name,Email,CNIC,Father Name,Post,Status,Applied Date,Phone,Mobile\n";

            foreach ($applications as $app) {
                $csvContent .= sprintf(
                    "%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                    $app->candidate_name ?: $app->user_name,
                    $app->email,
                    $app->cnic,
                    $app->father_name ?: '',
                    $app->post_title ?: $app->post_name,
                    $app->status,
                    $app->applied_at,
                    $app->phone ?: '',
                    $app->mobile ?: ''
                );
            }

            return response($csvContent, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="applications_' . date('Y-m-d') . '.csv"'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Export failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
{
    try {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected,reverted',
            'rejection_reason' => 'nullable|string|required_if:status,rejected|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $application = AppliedPosts::findOrFail($id);

        DB::transaction(function() use ($application, $request) {
            $application->status = $request->status;

            if ($request->status === 'rejected') {
                $application->rejection_reason = $request->rejection_reason;
            } else {
                $application->rejection_reason = null;
            }

            $application->save();
        });

        return response()->json([
            'message' => 'Application status updated successfully',
            'application' => [
                'id' => $application->id,
                'status' => $application->status,
                'comments' => $application->rejection_reason,
                'statusText' => ucfirst($application->status)
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to update application status',
            'message' => $e->getMessage()
        ], 500);
    }
}
}
