<?php

namespace App\Http\Controllers;

use App\Models\post_category;
use App\Http\Requests\StorePostCategoryRequest;
use App\Http\Requests\UpdatePostCategoryRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Admin/PostCategory/Index', [
            'postCategories' => post_category::latest()->get(),
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Admin/PostCategory/Create');
    }

    public function store(StorePostCategoryRequest $request)
    {
        post_category::create($request->validated());

        return redirect()->route('PostCategory.index')
            ->with('success', 'Post Category created successfully.');
    }

    public function show(post_category $postCategory)
    {
        return Inertia::render('Dashboard/Admin/PostCategory/Show', [
            'postCategory' => $postCategory,
        ]);
    }

    public function edit(post_category $postCategory)
    {
        return Inertia::render('Dashboard/Admin/PostCategory/Edit', [
            'postCategory' => $postCategory,
        ]);
    }

    public function update(UpdatePostCategoryRequest $request, post_category $postCategory)
    {
        $postCategory->update($request->validated());

        return redirect()->route('PostCategory.index')
            ->with('success', 'Post Category updated successfully.');
    }

    public function destroy(post_category $postCategory)
    {
        $postCategory->delete();

        return redirect()->route('PostCategory.index')
            ->with('success', 'Post Category deleted successfully.');
    }
}
