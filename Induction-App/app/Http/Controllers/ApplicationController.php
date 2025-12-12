<?php

namespace App\Http\Controllers;

use App\Models\AppliedPost;
use App\Models\AppliedPosts;
use App\Models\Candidate\qualification;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationController extends Controller
{

    public function index(): Response
    {


        $user = auth()->user();
        $myJobsApplication = $user->load([
            'AppliedPosts' => function ($query) {
                $query->with(['post'])
                    ->orderBy('applied_at', 'desc');
            },
            'candidateProfile'
        ]);

        return Inertia::render('Candidate/Index', [
            'myJobsApplication' => $myJobsApplication,
            'posts' => \App\Models\Post::all(),
            'testCities' => \App\Models\cities::all(),
        ]);
    }

    public function show(AppliedPosts $appliedPost): Response
    {
        // Load all necessary relationships
        $appliedPost->load([
            'post.category',  // Add category relationship
            'preferredCity',
            'alternativeCity',
            'user.candidateProfile',
            'user.qualifications.degreeType', // Load degreeType relationship
            'user.userContact'
        ]);

        // Optional: Add this to debug what's being sent
        \Log::info('Application Data new:', [
            'application' => $appliedPost->toArray()
        ]);

        return Inertia::render('Candidate/ApplicationDetail', [
            'application' => $appliedPost,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('candidate.dashboard')],
                ['name' => 'Application Details', 'url' => null],
            ]
        ]);
    }


    public function download($id)
    {
        $appliedPost = AppliedPosts::findOrFail($id);

        if ($appliedPost->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        $appliedPost->load([
            'post',
            'user.candidateProfile',
            'user.userContact',
            'user.qualifications.degreeType'
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.application', [
            'application' => $appliedPost
        ]);

        return $pdf->download('application-' . $appliedPost->id . '.pdf');
    }


    public function payment(AppliedPosts $appliedPost, Request $request)
    {

        if ($appliedPost->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        if (!in_array($appliedPost->status, ['approved', 'Roll Number Slip Generated'])) {
            return back()->with('error', 'Payment not available for this application status.');
        }

        return redirect()->back()->with('success', 'Payment feature will be implemented soon.');
    }
}
