<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\post_category;
use App\Models\QualificationType;
use App\Models\InductionPhase;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\post_level;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DB;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Admin/Posts/Index', [
            'posts' => Post::with(['category', 'qualificationType', 'inductionPhase'])
                ->latest()
                ->get(),
            'filters' => request()->only(['search']),
        ]);
    }

    public function create()
    {

        return Inertia::render('Dashboard/Admin/Posts/Create', [
            'inductionPhases' => InductionPhase::all(),
            'categories' => post_category::all(),
            'qualificationTypes' => QualificationType::all(),
            'postLevels' => post_level::all(),
        ]);
    }

    public function store(StorePostRequest $request)
    {
        try {
            DB::beginTransaction();

            $post = Post::create($request->validated());

            DB::commit();

            return redirect()
                ->route('posts.index')
                ->with('success', 'Post created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to create post. Please try again.');
        }
    }

    public function show(Post $post)
    {
        return Inertia::render('Dashboard/Admin/Posts/Show', [
            'post' => $post->load(['category', 'qualificationType', 'inductionPhase']),
        ]);
    }

    public function edit(Post $post)
    {
        return Inertia::render('Dashboard/Admin/Posts/Edit', [
            'post' => $post,
            'inductionPhases' => InductionPhase::all(),
            'categories' => post_category::all(),
            'qualificationTypes' => QualificationType::all(),
            'postLevels' => post_level::all(),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            DB::beginTransaction();

            $post->update($request->validated());

            DB::commit();

            return redirect()
                ->route('posts.index')
                ->with('success', 'Post updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post update failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to update post. Please try again.');
        }
    }

    public function destroy(Post $post)
    {
        try {
            $post->delete();
            return back()->with('success', 'Post deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Post deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete post.');
        }
    }
}
